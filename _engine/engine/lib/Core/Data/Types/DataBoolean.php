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
class DataBoolean extends DataType {

  /**
   * @return DataMethod[]
   */
  public function getValidators(){

    return array(
      'req'=>new DataMethod($this, 'req')
    );
  }

  /**
   * @return DataMethod[]
   */
  public function getFilters(){

    return array(
      'trim'=>new DataMethod($this,  'trim'),
      'boolean'=>new DataMethod($this, 'toBoolean')
    );
  }

  /**
   * @return array
   */
  public function getDefaultFilters(){

    return array(
      'trim'=>true,
      'boolean'=>true
    );
  }

  /**
   * @return array
   */
  public function getDefaultValidators(){

    return array(
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

    if(is_null($value)){
      $this->throwError('Field '.$node->getName(). ' required');
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
  public function toBoolean(DataNode $node, $config){

    if(!$config){ return; }
    if(is_null($node->getValue())){ return; }

    $node->setValue((bool)$node->getValue());
  }
}
