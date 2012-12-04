<?php

namespace lib\Form;

use lib\Session\Session;
use lib\Core\UrlService;
use lib\EngineExceptions\SystemException;
use lib\Core\Manager;

class Form {

  private static $token;

  private $name;
  private $elements = array();
  private $form = array();
  private $url = '/';
  private $btnName = 'Submit';
  private $onError;
  private $data;
  private $templateVars = array();
  private $autoJsValidation = true;
  private $template = null;


  public $savedSuccess;

  public $inform;
  public $errors = array();
  public $error;

  private $formObjects = array();
  private $clear;

  private $vars = array();

  /**
   * Form::getToken()
   *
   * @return string md5(SYSTEM_CODE + SESSION_SID + IP)
   */
  private static function getToken(){
    if(self::$token){ return self::$token; }
    return self::$token = Manager::$Token->get();
  }

  /**
   * @param $key
   * @param $value
   */
  public function setTemplateVar($key, $value) {
    $this->templateVars[$key] = $value;
  }

  /**
   * Form::__construct()
   *
   * @param array $config
   * @throws SystemException
   * @return \lib\Form\Form
   */
  public function __construct($config){
    //Debugger::log($config,'label');
    if(!$config) {
      throw new SystemException('Данные для постороения формы небыли загружены');
    }

    $keys_with_name = array_keys($config);
    $this->name = $keys_with_name[0];

    $url = UrlService::get();
    $urlExtra = $url->getParams();
    $this->onError($url->toString());

    if(isset($urlExtra['saved']) and Session::getVar('savedFrom')) {
      $this->savedSuccess = true;
    }

    if(isset($config[$this->name]['action'])){
      $url->setAction($config[$this->name]['action']);
    }

    $this->sendTo($url->toString());

    if(isset($config[$this->name]['autoJsValidation'])){
      $this->autoJsValidation = $config[$this->name]['autoJsValidation'];
    }

    if(!isset($config[$this->name]['elements'])){
      $this->form = $config[$this->name];
      return;
    }

    $this->elements = $config[$this->name]['elements'];

    unset($config[$this->name]['elements']);

    $this->form = $config[$this->name];

    foreach($this->elements as $key => $value) {
      $this->formObjects[$key] = $this->factory($value['element'], $key, $value);
    }

    //Debugger::log($this, 'form');
  }

  /**
   * Form::getElement()
   *
   * @param $name
   * @throws \lib\EngineExceptions\SystemException
   * @return IFormElement
   */
  public function getElement($name){
    if(isset($this->formObjects[$name])) {
      return $this->formObjects[$name];
    }
    throw new SystemException('Не могу найти элемент с именем: ' . $name . ' в форме ' . $this->name);
  }

  /**
   * Form::addElement()
   *
   * @param $type
   * @param $name
   * @param $config
   * @throws \lib\EngineExceptions\SystemException
   * @return array
   */
  public function addElement($type, $name, $config){
    if(!isset($this->formObjects[$name])) {
      return $this->formObjects[$name] = $this->factory($type, $name, $config);
    }
    throw new SystemException('Элемент с именем: ' . $name . ' уже существует в форме ' . $this->name);
  }

  /**
   * Form::setElement()
   *
   * @param $name
   * @param $element
   * @throws \lib\EngineExceptions\SystemException
   * @return array
   */
  public function setElement($name, $element){
    if(isset($this->formObjects[$name])) {
      return $this->formObjects[$name] = $element;
    }
    throw new SystemException('Не могу найти элемент с именем: ' . $name . ' в форме ' . $this->name);
  }

  /**
   * Form::changeElement()
   *
   * @param $name
   * @param $changeTo
   * @param $value
   * @throws \lib\EngineExceptions\SystemException
   * @return array
   */
  public function changeElement($name, $changeTo, $value){
    if(isset($this->formObjects[$name])) {
      return $this->formObjects[$name] = $this->factory($changeTo, $name, $value);
    }
    throw new SystemException('Не могу найти элемент с именем: ' . $name . ' в форме ' . $this->name);
  }

  /**
   * Form::changeForm()
   *
   * @param $name
   * @param $value
   * @return array
   */
  public function changeForm($name, $value){
    return $this->form[$name] = $value;
  }

  /**
   * Form::setValue()
   *
   * @return void
   */
  private function setValue(){

    if($this->clear) {
      return;
    }

    $savedData = Session::getVar($this->name);

    foreach($this->formObjects as $element) {
      if(isset($this->data[$element->name])) {
        if(empty($_POST) or isset($_POST[$element->name])) {
          $element->value = $this->data[$element->name];
        } else {
          $element->value = false;
        }
      }

      if(isset($savedData[$element->name])) {
        if(empty($_POST) or isset($_POST[$element->name])) {
          $element->value = $savedData[$element->name];
        } else {
          $element->value = false;
        }
      }

      if(isset($_POST[$element->name])) {
        $element->value = $_POST[$element->name];
      }

      if(isset($savedData['error' . $element->name])) {
        $element->error = $savedData['error' . $element->name];
      }
    }

  }

  /**
   * Form::cleare()
   *
   * @return void
   */
  public function clear(){

    Session::deleteVar($this->name);

    foreach($this->formObjects as $element) {
      $element->value = '';
      $element->error = false;
    }

    $this->clear = true;
  }

  /**
   * Form::factory()
   *
   * @param string $element
   * @param string $name
   * @param array $config
   * @throws \lib\EngineExceptions\SystemException
   * @return IFormElement
   */
  private function factory($element, $name, $config){
    $elementObject = '\lib\Form\elements\\'.ucfirst($element) . 'Element';

    if($this->autoJsValidation){
      $config = FormJsValidatorAdapter::addValidator($config);
    }

    $config['formId'] = $this->name;

    if(isset($config['class']) and is_string($config['class'])){
      $config['class'] .= ' engine-form-'.$element;
    }elseif(isset($config['class']) and is_array($config['class'])){
      foreach($config['class'] as &$class){
        $class .= ' engine-form-'.$element;
      }
    }else{
      $config['class'] = ' engine-form-'.$element;
    }

    return new $elementObject($name, $config);
  }

  /**
   * Form::delete()
   *
   * @param $name
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  public function delete($name){
    if(isset($this->formObjects[$name])) {
      unset($this->formObjects[$name]);
      return true;
    }
    throw new SystemException('Не могу найти элемент с именем: ' . $name . ' в форме ' . $this->name);
  }


  /**
   * Form::validate()
   *
   * @param bool $checkTokens
   * @param bool $checkTokens
   *
   * @return array|bool
   */
  public function validate($checkTokens = true){

    $post = false;

    $this->setValue();

    if($checkTokens ) {
      $this->checkToken($_POST['token']);

      if($this->error){
        $this->errorReport();
      }
    }

    foreach($this->formObjects as $element) {
      $validationElement = new FormValidator($element, $this);
      if(property_exists($validationElement, 'value') and !is_null($validationElement->value)){
        $post[$element->name] = $validationElement->value;
      }
    }

    if(empty($this->errors)) {
      foreach($this->formObjects as $element) {
        if(property_exists($element, 'value')){
          $this->vars[$element->name] = $element->value;
        }
      }
      return $post;
    }

    $this->errorReport();

    return false;
  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   */
  private function errorReport(){

    if(!$this->onError) {
      throw new SystemException($this->errors);
    }

    $error['errors'] = $this->errors;
    $error['error'] = $this->error;

    foreach($this->formObjects as $element) {
      $error[$element->name] = property_exists($element, 'value') ? $element->value : '';
      $error['error' . $element->name] = $element->error;
    }

    Session::setVar($this->name, $error);

    Manager::$Php->redirect($this->onError);
  }

  /**
   * @param $getToken
   * @return bool
   */
  private function checkToken($getToken){

    $token = self::getToken();

    if(!$token) {
     $this->error = 'Ключ формы сервера не получен';
      return false;
    }

    if(!isset($getToken)) {
      $this->error = 'Ключ формы клиента не получен';
      return false;
    }

    if($token !== $_POST['token']) {
      $this->error = 'Ключи формы не совпадают';
      return false;
    }

    return true;
  }

  /**
   * Form::successRedirect()
   *
   * @param array $url
   * @param string $msg
   * @return void
   */
  public function successRedirect(array $url = array(), $msg = 'Выполненно'){
    Session::addMessage('Выполнено', $msg, Session::MESSAGE_TYPE_SUCCESS);
    Manager::$Php->redirect($url);
  }

  /**
   * Form::sendTo()
   *
   * @param string $url
   * @return \lib\Form\Form
   */
  public function sendTo($url){
    $this->url = $url;
    return $this;
  }

  /**
   * Form::onError()
   *
   * @param string $url
   * @return \lib\Form\Form
   */
  public function onError($url){
    $this->onError = $url;
    return $this;
  }

  /**
   * Form::addBtn()
   *
   * @param string $name
   * @return \lib\Form\Form
   */
  public function addBtn($name){
    $this->btnName = $name;
    return $this;
  }

  /**
   * Form::setItems()
   *
   * @param string $elementName
   * @param mixed $items
   * @return \lib\Form\Form
   */
  public function setItems($elementName, $items){
    $element = $this->getElement($elementName);
    $element->items = $items;
    return $this;
  }

  /**
   * Form::setData()
   *
   * @param array $data
   * @return \lib\Form\Form
   */
  public function setData(array $data){
    $this->data = $data;
    return $this;
  }

  /**
   * Form::set()
   *
   * @param string $elementName
   * @param string $value
   * @return \lib\Form\Form
   */
  public function set($elementName, $value){
    $this->data[$elementName] = $value;
    return $this;
  }

  /**
   * Form::setError()
   *
   * @param string $msg
   * @return void
   */
  public function setError($msg){
    $this->vars['error'] = $msg;
    Session::setVar($this->name, $this->vars);
    Manager::$Php->redirect($this->onError);
  }

  public function setTemplate($templateName) {
    $this->template = $templateName;
  }

  /**
   * Form::toString()
   *
   * @return string
   */
  public function toString(){

    $result = '';
    $placeHolders = array();

    $this->setValue();

    foreach($this->formObjects as $element) {
      if(isset($element->placeHolder) and  $element->placeHolder) {
        $placeHolders[$element->placeHolder] = $element->toString();
        continue;
      }

      $result .= $element->toString();
    }

    /**
     * @var \lib\Form\elements\FormElement $form
     */
    $form = $this->factory('Form', $this->name, $this->form);

    $form->id = $this->name . 'Id';
    $form->allFields = $result;
    $form->action = $this->url;
    $form->submitBtnId = $this->name . 'Btn';
    $form->submitBtnName = $this->btnName;
    $form->savedSuccess = $this->savedSuccess;
    $form->placeHolders = $placeHolders;
    $form->templateVars = $this->templateVars;

    if($this->template){
      $form->template = $this->template;
    }

    if($this->inform) {
      $form->inform = $this->inform;
    }

    $form->token = self::getToken();
    $savedData = Session::getVar($this->name);

    if($savedData and isset($savedData['error'])) {
      $form->error = $savedData['error'];
    }

    Session::deleteVar($this->name);

    return $form->toString();
  }

} 