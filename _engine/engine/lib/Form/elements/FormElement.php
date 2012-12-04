<?php

namespace lib\Form\elements;

use lib\Session\Session;
use lib\Templates\TemplatesManager;
use lib\View\View;


class FormElement {

  public $method = 'post';

  /**
   * @example  multipart/form-data
   * @example  application/x-www-form-urlencoded
   * @example  text/plain
   **/
  public $encrypt = 'multipart/form-data';
  public $class;

  public $title;
  public $inform;

  public $template = 'formElement';

  public $allFields;
  public $placeHolders = array();
  public $submitBtnId;
  public $submitBtnName;
  public $cancelUrl;
  public $action;
  public $id;
  public $name;
  public $width;
  public $token;
  public $savedSuccess = false;
  public $successMsg = 'Данные формы успешно сохранены';
  public $templateVars = array();

  public $errors = array();
  public $error;

  public function __construct($name, array $config, $formName = false){
    $this->name = $name;
    foreach($config as $key => $value) {
      $this->$key = $value;
    }
    $savedData = Session::getVar($this->name);
    if(isset($savedData['errors'])) {
      $this->errors = $savedData['errors'];
    }
  }

  public function toString(){

    View::get('html')
      ->setCss('form')
      ->setJs('posabsolute/js/jquery.validationEngine')
      ->setJs('posabsolute/js/languages/jquery.validationEngine-ru')
      ->setJsCss('posabsolute/css/validationEngine.jquery');

    $element = TemplatesManager::load($this->template);

    $element->set($this);
    $element->set($this->templateVars);

    return $element->toString();
  }

}