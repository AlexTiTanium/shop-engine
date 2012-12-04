<?php

namespace lib\Core\Data;
use lib\EngineExceptions\SystemException;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */
class DataMethod {

  /**
   * @var DataType
   */
  private $dataTypeObject;

  /**
   * @var string
   */
  private $methodName;

  /**
   * @param DataType $dataTypeObject
   * @param $methodName
   */
  public function __construct(DataType $dataTypeObject, $methodName){

    $this->dataTypeObject = $dataTypeObject;
    $this->methodName = $methodName;
  }

  /**
   * @param DataNode $node
   *
   * @param $value
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  public function callMethod(DataNode $node, $value = false){

    $methodName = $this->methodName;

    if(!method_exists($this->dataTypeObject, $this->methodName)){
      throw new SystemException('Method in DataType: '.$this->methodName);
    }

    $this->dataTypeObject->$methodName($node, $value);
  }
}
