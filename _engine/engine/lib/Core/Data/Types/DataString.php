<?php

namespace lib\Core\Data\Types;

use lib\Core\Data\DataNode;
use lib\EngineExceptions\SystemException;
use lib\Core\Data\DataMethod;
use lib\Core\Data\DataType;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */
class DataString extends DataType {

  /**
   * @return DataMethod[]
   */
  public function getValidators(){

    return array(
      'req'=>new DataMethod($this, 'req'),
      'min'=>new DataMethod($this, 'min'),
      'max'=>new DataMethod($this, 'max'),
      'onlyLetNumSpec'=>new DataMethod($this, 'onlyLetNumSpec'),
      'email'=>new DataMethod($this, 'email')
    );
  }

  /**
   * @return DataMethod[]
   */
  public function getFilters(){

    return array(
      'plainText'=>new DataMethod($this, 'plainText'),
      'trim'=>new DataMethod($this,  'trim'),
      'text'=>new DataMethod($this,  'text'),

    );
  }

  /**
   * @return array
   */
  public function getDefaultFilters(){

    return array(
      'trim'=>true,
      'text'=>true
    );
  }

  /**
   * @return array
   */
  public function getDefaultValidators(){
    return array();
  }

  /**
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function req(DataNode $node, $config){

    if(!$config){ return; }

    $value = $node->getValue();

    if(empty($value)){
      $this->throwError('Field '.$node->getName(). ' required');
    }
  }

  /**
   * @param DataNode $node
   * @param $minLen
   *
   * @internal param $configValue
   *
   * @return void
   */
  public function min(DataNode $node, $minLen){

    if(!$minLen){ return; }
    $value = $node->getValue();
    if(empty($value)){ return; }

    if(mb_strlen($value) < $minLen){
      $this->throwError('Value of field: '.$node->getName(). ' must be grate that '.$minLen);
    }
  }

  /**
   * @param DataNode $node
   * @param $maxLen
   *
   * @return void
   */
  public function max(DataNode $node, $maxLen){

    if(!$maxLen){ return; }

    if(mb_strlen($node->getValue()) > $maxLen){
      $this->throwError('Value of field: '.$node->getName(). ' must be less that '.$maxLen);
    }
  }

  /**
   * @param DataNode $node
   * @param $config
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  public function onlyLetNumSpec(DataNode $node, $config){

    if(!$config){ return; }

    $result = $this->validateByRegex($node->getValue(), '/^[0-9a-zA-Z_\-.,:()]+$/');

    if(!$result){
      $this->throwError('Not valid format for: '.$node->getName().' you may use: numbers, latin lathers, -, ",",.,:()');
    }
  }

  /**
   * @param \lib\Core\Data\DataNode $node
   * @param $config
   */
  public function email(DataNode $node, $config){

    if(!$config){ return; }

    $result = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $node->getValue());
    if(!$result){
      $this->throwError('Not valid email format for field: '.$node->getName());
    }
  }

/** -------------------------------------------------------------------- */
#  Filters
/** -------------------------------------------------------------------- */

  /**
   * May be low or high
   *
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function plainText(DataNode $node, $config){

    if(!$config){ return; }

    $result = $this->filterVar($node->getValue(), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    $node->setValue($result);
  }

  /**
   * May be low or high
   *
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function text(DataNode $node, $config){

    if(!$config){ return; }

    $result = $this->filterVar($node->getValue(), FILTER_SANITIZE_STRING, !FILTER_FLAG_STRIP_LOW);
    $node->setValue($result);
  }

  /**
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function trim(DataNode $node, $config){

    if(!$config){ return; }

    $node->setValue(trim($node->getValue()));
  }
}
