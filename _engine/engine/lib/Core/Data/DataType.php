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
abstract class DataType {

  /**
   * @var DataNode
   */
  private $dataNode;

  /**
   * @var DataMethod[]
   */
  private $filters;

  /**
   * @var DataMethod[]
   */
  private $validators;

  /**
   * @var array
   */
  private $defaultFilters;

  /**
   * @var array
   */
  private $defaultValidators;


  /**
   * @param DataNode $dataNode
   */
  public function __construct(DataNode $dataNode){

    $this->dataNode = $dataNode;

    $this->filters = $this->getFilters();
    $this->validators = $this->getValidators();
    $this->defaultFilters = $this->getDefaultFilters();
    $this->defaultValidators = $this->getDefaultValidators();
  }

  /**
   * @return DataMethod[]
   */
  abstract public function getValidators();
  /**
   * @return DataMethod[]
   */
  abstract public function getFilters();
  /**
   * @return array
   */
  abstract public function getDefaultFilters();

  /**
   * @return array
   */
  abstract public function getDefaultValidators();

  /**
   *  Run default filters
   */
  public function runDefaultFilters(){

    $defaultFilters = $this->getDefaultFilters();
    $filters = $this->dataNode->getFilters();

    foreach($defaultFilters as $filterName=>$filterValue){

      // If filter defined in config, we not run default filter
      if(isset($filters[$filterName])){ continue; }

      $this->runFilter($filterName, $filterValue);
    }
  }

  /**
   *  Run default filters
   */
  public function runDefaultValidators(){

    $defaultValidators = $this->getDefaultValidators();
    $validators = $this->dataNode->getValidators();

    foreach($defaultValidators as $validatorName=>$validatorValue){

      // If filter defined in config, we not run default filter
      if(isset($validators[$validatorName])){ continue; }

      $this->runValidator($validatorName, $validatorValue);
    }
  }

  /**
   * @param string $name
   *
   * @return DataMethod
   * @throws \lib\EngineExceptions\SystemException
   */
  protected function getFilter($name){

    if(!isset($this->filters[$name]) or empty($this->filters[$name])){
      throw new SystemException('Filter: '.$name.' not found');
    }

    return $this->filters[$name];
  }

  /**
   * @param $name
   *
   * @return DataMethod
   * @throws \lib\EngineExceptions\SystemException
   */
  protected function getValidator($name){

    if(!isset($this->validators[$name]) or empty($this->validators[$name])){
      throw new SystemException('Validator: '.$name.' not found');
    }

    return $this->validators[$name];
  }

  /**
   * @param string $name
   * @param $value
   */
  public function runFilter($name, $value){

    $filterMethod = $this->getFilter($name);
    $filterMethod->callMethod($this->dataNode, $value);
  }

  /**
   * @param string $name
   * @param $value
   */
  public function runValidator($name, $value){

    $filterMethod = $this->getValidator($name);
    $filterMethod->callMethod($this->dataNode, $value);
  }

  /**
   * @param $value
   * @param $filter
   * @param $options
   *
   * @return mixed
   */
  protected function filterVar($value, $filter, $options = array()){
    return filter_var($value, $filter, $options);
  }

  /**
   * @param $value
   * @param $regex
   *
   * @return mixed
   */
  protected function validateByRegex($value, $regex){
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp'=>$regex)));
  }

  /**
   * @param $value
   * @param $function
   *
   * @return mixed
   */
  protected function filterByCallback($value, $function){
    return filter_var($value, FILTER_CALLBACK,  array('options' => $function));
  }

  /**
   * @param string $msg
   *
   * @throws DataValidationException
   */
  protected function throwError($msg){
    throw new DataValidationException($msg);
  }
}