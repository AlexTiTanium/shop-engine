<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class CaptchaElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $formId;
  public $class;
  public $style;
  public $template = 'captchaElement';
  public $label = 'label';
  public $rel = false;
  public $value;
  public $inform;
  public $url = '/form';

  public $error = false;

  # Validation
  public $validation = array();

  public function __construct($name, array $config){

    $this->name = $name;

    foreach($config as $key => $value) {
      $this->$key = $value;
    }
  }

  public function toString(){

    if(empty($this->id)){
      $this->id = $this->formId.'-'.$this->name;
    }

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}