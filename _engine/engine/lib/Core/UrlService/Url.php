<?php

namespace lib\Core\UrlService;

use lib\Core\Manager;
use lib\Debugger\Debugger;
use lib\Core\UrlService;
use lib\EngineExceptions\SystemException;

class Url {

  protected $application;
  protected $controller;
  protected $action;
  protected $event;
  protected $id;
  protected $page = 0;
  protected $params = array();
  protected $get = array();
  protected $type = 'html';

  /**
   * Создать урл
   *
   * @param bool|array|string $url
   *
   * @access Public
   */
  public function __construct($url = false){

    if($url === false) {
      return;
    }

    $this->set($url);
  }

  /**
   * @param $key
   *
   * @return bool
   * @throws \lib\EngineExceptions\SystemException
   */
  public function isEmpty($key){

    if(!property_exists(__CLASS__, $key)) {
      throw new SystemException('Bad property name:' . $key);
    }

    if(empty($this->$key)) {
      return true;
    }

    return false;
  }

  /**
   * @param $key
   *
   * @return mixed
   * @throws SystemException
   */
  public function getProperty($key){

    if(!property_exists(__CLASS__, $key)) {
      throw new SystemException('Bad property name:' . $key);
    }

    return $this->$key;
  }

  /**
   * Установить текущие пути
   *
   * @param  bool|array|string $value - установить значения для этого класса
   *
   * @return Url
   * @access Public
   */
  public function set(array $value){

    foreach($value as $key => $val) {

      if(property_exists(__CLASS__, $key)){
        $this->$key = $val;
      }else{
        $this->addParam($key, $val);
      }

    }

    if(isset($this->get['page'])){
      $this->page = intval($this->get['page']);
      unset($this->get['page']);
    }

    if(isset($this->get['id'])){
      $this->id = $this->get['id'];
      unset($this->get['id']);
    }

    return $this;
  }

  /**
   * Преобразовать в Array
   *
   * @param Array|bool $set - Если нужно что-то добавить, но только в ответ не изменяя сам объект
   *
   * @return Array
   * @access Public
   */
  public function toArray($set = false){

    $return = get_object_vars($this);

    if($set and is_array($set)) {
      $return = $set + $return;
    }

    return $return;
  }

  /**
   * Преобразовать в Строку URL
   *
   * @param Array|bool $set - Если нужно что-то добавить, но только в ответ не изменяя сам объект
   *
   * @return String URL
   * @access Public
   */
  public function toString($set = false){

    $array = $this->toArray($set);

    $builder = array();

    if(isset($array['application']) and $array['application'] != Manager::$UrlService->getDefaultApplication()){
      array_push($builder, $array['application']);
    }

    array_push($builder, $array['controller']);
    array_push($builder, $array['action']);
    array_push($builder, $array['event']);
    array_push($builder, implode('/', $this->params));

    $builder = implode('/', array_filter($builder));
    $builder .= $this->type ? '.'.$this->type : '';

    if(!empty($array['get'])) {

      if($array['id']){ $array['get']['id'] = $array['id']; }

      $getBuilder = http_build_query($array['get'], '', '&');

      if($getBuilder) {
        $builder .= '?'.$getBuilder;
      }
    }

    return $builder;
  }

  /**
   * @return string
   */
  public function getAction(){
    return $this->action;
  }

  /**
   * @return string
   */
  public function getApplication(){
    return $this->application;
  }

  /**
   * @return string
   */
  public function getController(){
    return $this->controller;
  }

  /**
   * @return string
   */
  public function getEvent(){
    return $this->event;
  }

  /**
   * @param $key
   *
   * @return mixed
   */
  public function get($key = false){
    if($key === false) {
      return $this->get;
    }
    return isset($this->get[$key]) ? $this->get[$key] : null;
  }

  /**
   * @return string
   */
  public function getId(){
    return $this->id;
  }

  /**
   * @return int
   */
  public function getPage(){
    return $this->page;
  }

  /**
   * @return string
   */
  public function getType(){
    return $this->type;
  }

  /**
   * @param $action
   */
  public function setAction($action){
    $this->action = $action;
  }

  /**
   * @param $controller
   */
  public function setController($controller){
    $this->controller = $controller;
  }

  /**
   * @param $application
   */
  public function setApplication($application){
    $this->application = $application;
  }

  /**
   * @param $key
   * @param $value
   */
  public function addParam($key, $value){

    $this->params[$key] = $value;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getParams($key = null){

    if($key===null){
      return $this->params;
    }

    if(isset($this->params[$key])){
      return $this->params[$key];
    }

    return null;
  }

  /**
   * @param $type
   */
  public function setType($type){
    $this->type = $type;
  }

  /**
   * @param $id
   */
  public function setId($id){
    $this->id = $id;
  }

  /**
   * @param $params
   */
  public function setParams($params){
    $this->params = $params;
  }

  /**
   * @param $page
   */
  public function setPage($page){
    $this->page = $page;
  }

  /**
   * @param $event
   */
  public function setEvent($event){
    $this->event = $event;
  }
}