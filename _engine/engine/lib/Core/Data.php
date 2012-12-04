<?php

namespace lib\Core;

use lib\EngineExceptions\SystemException;
use lib\Core\Data\DataValidator;

class Data {

  private $data = array();

  private $scheme;
  private $dataValidator;

  /**
   * Data::__construct()
   *
   * @param array $data
   * @param array|bool $scheme
   */
  public function __construct(array $data, $scheme = false) {

    $this->data = $data;
    $this->scheme = $scheme;

    if($scheme){
      $this->dataValidator = new DataValidator($scheme);
      $this->dataValidator->validate($this);
    }
  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   * @internal param \lib\Core\Data $data
   *
   */
  public function checkToken(){

    $token = $this->getRequired('token');
    $currentToken = Manager::$Token->get();

    if($token != $currentToken){
      throw new SystemException('Bad token');
    }
  }

  /**
   * @param array $scheme
   *
   * @param bool $checkToken
   * @return Data
   */
  public function validate(array $scheme, $checkToken = true){

    $this->dataValidator = new DataValidator($scheme);
    $this->dataValidator->validate($this, $checkToken);

    return $this;
  }

  /**
   * @return array
   */
  public function toArray() {
    return $this->data;
  }

  /**
   * Data::getRequired()
   *
   * @param string $varName
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return mixed
   */
  public function getRequired($varName) {
    if (!isset($this->data[$varName])) {
      throw new SystemException('Var ' . $varName . ' is required');
    }
    return $this->data[$varName];
  }

  /**
   * Data::getNoEmpty()
   *
   * @param string $varName
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return mixed
   */
  public function getNoEmpty($varName) {
    if (!isset($this->data[$varName]) or empty($this->data[$varName])) {
      throw new SystemException('Var ' . $varName . ' is required and must be not empty');
    }
    return $this->data[$varName];
  }

  /**
   * Data::getBool()
   *
   * @param string $varName
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  public function getBool($varName) {
    if (!isset($this->data[$varName])) {
      return false;
    }
    return (bool)$this->data[$varName];
  }

  /**
   * @param string $varName
   * @param bool|array $validateScheme
   * @param bool $checkToken
   *
   * @return Data
   */
  public function getJsonRequest($varName, $validateScheme = false, $checkToken = true){

    if($checkToken){
      $this->checkToken();
    }

    $data = $this->getJson($varName);

    if($validateScheme){
      $data->validate($validateScheme, false);
    }

    return $data;
  }

  /**
   * @param string $varName
   *
   * @return Data
   */
  public function getJson($varName){

    $jsonString = $this->getRequired($varName);
    $data = new Data(json_decode($jsonString, true));

    return $data;
  }

  /**
   * Data::getRequiredOpt()
   *
   * @param string $varName
   *
   * @param array $options
   * @throws \lib\EngineExceptions\SystemException
   * @return mixed
   */
  public function getRequiredOpt($varName, array $options) {

    if (!isset($this->data[$varName]) or empty($this->data[$varName])) {
      throw new SystemException('Var ' . $varName . ' is requred');
    }

    $value = $this->data[$varName];

    if (array_search($value, $options) === false) {
      throw new SystemException('Var ' . $varName . ' must be:' . implode(', ', $options));
    }

    return $value;
  }

  /**
   * Data::getRequiredArrayValue()
   *
   * @param string $varName
   *
   * @param string|int $key
   * @throws \lib\EngineExceptions\SystemException
   * @return mixed
   */
  public function getRequiredArrayValue($varName, $key) {

    if (!isset($this->data[$varName][$key]) or empty($this->data[$varName][$key])) {
      throw new SystemException('Var ' . $varName . ' with key:' . $key . ' is requred');
    }
    $return = $this->data[$varName][$key];

    return $return;
  }

  /**
   * Data::get()
   *
   * @param string $varName
   *
   * @param int $default
   * @return mixed
   */
  public function get($varName, $default = null) {
    if (!isset($this->data[$varName]) or is_null($this->data[$varName])) {
      return $default;
    }
    return $this->data[$varName];
  }

  /**
   * Data::getValue()
   *
   * @param string $varName
   *
   * @return mixed
   */
  public function getValue($varName) {
    if (!isset($this->data[$varName])) {
      return null;
    }
    return $this->data[$varName];
  }

  /**
   * Data::setValue()
   *
   * @param string $varName
   * @param $value
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  public function setValue($varName, $value) {

    if (!array_key_exists($varName, $this->data)) {
      throw new SystemException('Var name not found: '.$varName.' '.print_r($this->data,true));
    }

    $this->data[$varName] = $value;
  }

  /**
   * Data::setValue()
   *
   * @param string $varName
   */
  public function setNullVar($varName) {

    $this->data[$varName] = null;
  }

  /**
   * Data::isExist()
   *
   * @param string $varName
   *
   * @return boolean
   */
  public function isExist($varName) {
    if (!array_key_exists($varName, $this->data)) {
      return false;
    }
    return true;
  }

  /**
   * @param $varName
   * @return mixed
   * @throws \lib\EngineExceptions\SystemException
   */
  public function getRequiredMd5Hash($varName) {

    $hash = $this->getRequired($varName);

    if (preg_match("#^[0-9a-f]{32}$#i", $hash)) {
      return $hash;
    }

    throw new SystemException('Var ' . $varName . ' is not valid md5 hash');
  }

  /**
   * @param $varName
   * @return mixed
   * @throws \lib\EngineExceptions\SystemException
   */
  public function getRequiredUrl($varName) {

    $url = $this->getRequired($varName);

    if (preg_match('%(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()[\]{};:\'".,<>?«»“”‘’]))%', $url)) {
      return $url;
    }

    throw new SystemException('Var ' . $varName . ' is not valid url');
  }

  /**
   * Check if data is list
   * @return bool
   */
  public function isList() {
      return !(is_array($this->data) && count(array_filter(array_keys($this->data),'is_string')) == count($this->data));
  }

  /**
   * @param callback $callback
   */
  public function map($callback){
    array_walk($this->data, function($value, $key) use ($callback){
      $callback(new Data($value), $key);
    });
  }

}