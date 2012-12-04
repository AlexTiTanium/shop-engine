<?php

namespace lib\Core\Data;

use lib\EngineExceptions\SystemException;
use lib\Debugger\Debugger;
use lib\Core\Data;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 21:08
 * To change this template use File | Settings | File Templates.
 */
class DataNode {

  /**
   * @var \lib\Core\Data
   */
  private $data;

  /**
   * @var string
   */
  private $name;

  /**
   * @var array
   */
  private $config;

  /**
   * @var DataNodeCollection
   */
  private $nodeCollection;

  /**
   * @var DataType
   */
  private $dataType;

  /**
   * @param string $name
   * @param Data $data
   * @param mixed $config
   * @param DataNodeCollection $nodeCollection
   */
  public function __construct($name, Data $data, $config, DataNodeCollection $nodeCollection ){

    $this->name = $name;
    $this->data = $data;
    $this->config = $config;
    $this->nodeCollection = $nodeCollection;
    $this->dataType = $this->factory($this->getNodeType());

    $this->dataType->runDefaultFilters();
    $this->dataType->runDefaultValidators();
  }

  /**
   * @return string
   */
  public function getName(){
    return $this->name;
  }

  /**
   * Run validations for node
   */
  public function runValidators(){

    $validators = $this->getValidators();

    foreach($validators as $validatorMethodName=>$validatorValue){
      $this->dataType->runValidator($validatorMethodName, $validatorValue);
    }
  }

  /**
   * Run filters for node
   */
  public function runFilters(){

    $filters = $this->getFilters();

    foreach($filters as $filterMethodName=>$filterValue){
      $this->dataType->runFilter($filterMethodName, $filterValue);
    }
  }

  /**
   * @return DataMethod[]
   */
  public function getValidators(){

    if(is_string($this->config) or !isset($this->config['validators']) or empty($this->config['validators'])){
      return array();
    }

    return $this->config['validators'];
  }

  /**
   * @return DataMethod[]
   */
  public function getFilters(){

    if(is_string($this->config) or !isset($this->config['filters']) or empty($this->config['filters'])){
      return array();
    }

    return $this->config['filters'];
  }

  /**
   * @return string
   * @throws \lib\EngineExceptions\SystemException
   */
  public function getNodeType(){

    if(is_string($this->config)){
      return $this->config;
    }

    if(!isset($this->config['type'])){
      throw new SystemException('Type must be defined');
    }

    return $this->config['type'];
  }

  /**
   * @return \lib\Core\Data\DataNodeCollection
   */
  public function getNodeCollection(){
    return $this->nodeCollection;
  }

  /**
   * @return mixed
   */
  public function getValue(){

    return $this->data->getValue($this->getName());
  }

  /**
   * @param mixed $value
   */
  public function setValue($value){

    $this->data->setValue($this->getName(), $value);
  }

  /**
   * @return mixed
   */
  public function getConfig(){
    return $this->config;
  }

  /**
   * @param string $type
   *
   * @return DataType
   * @throws \lib\EngineExceptions\SystemException
   */
  private function factory($type){

    $className = 'lib\Core\Data\Types\Data'.ucfirst($type);

    $object = new $className($this);

    if(!$object instanceof DataType){
      throw new SystemException('Validation object must be DataType');
    }

    return $object;
  }
}