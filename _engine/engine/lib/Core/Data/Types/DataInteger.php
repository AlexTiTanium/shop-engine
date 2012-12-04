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
class DataInteger extends DataType {

  /**
   * @return DataMethod[]
   */
  public function getValidators(){

    return array(
      'req'=>new DataMethod($this, 'req'),
      'min'=>new DataMethod($this, 'min'),
      'max'=>new DataMethod($this, 'max'),
      'integer'=>new DataMethod($this, 'integer')
    );
  }

  /**
   * @return DataMethod[]
   */
  public function getFilters(){

    return array(
      'trim'=>new DataMethod($this,  'trim'),
      'integer'=>new DataMethod($this, 'toInteger')
    );
  }

  /**
   * @return array
   */
  public function getDefaultFilters(){

    return array(
      'trim'=>true,
      'integer'=>true
    );
  }

  /**
   * @return array
   */
  public function getDefaultValidators(){

    return array(
      'integer'=>true
    );
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
    if(is_null($node->getValue())){ return; }

    if($node->getValue() < $minLen){
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

    if($node->getValue() > $maxLen){
      $this->throwError('Value of field: '.$node->getName(). ' must be less that '.$maxLen);
    }
  }

  /**
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function integer(DataNode $node, $config){

    if(!$config){ return; }
    if(is_null($node->getValue())){ return; }

    $result = $this->filterVar($node->getValue(), FILTER_VALIDATE_INT);

    if(!$result){
      $this->throwError('Value of field: '.$node->getName(). ' must be integer');
    }
  }

/** -------------------------------------------------------------------- */
#  Filters
/** -------------------------------------------------------------------- */

  /**
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function trim(DataNode $node, $config){

    if(!$config){ return; }
    if(is_null($node->getValue())){ return; }

    $node->setValue(trim($node->getValue()));
  }

  /**
   * @param DataNode $node
   * @param $config
   *
   * @return void
   */
  public function toInteger(DataNode $node, $config){

    if(!$config){ return; }
    if(is_null($node->getValue())){ return; }

    $node->setValue((int)$node->getValue());
  }
}
