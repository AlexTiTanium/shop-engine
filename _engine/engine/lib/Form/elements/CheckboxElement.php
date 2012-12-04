<?php

namespace lib\Form\elements;

use lib\Form\IFormElement;
use lib\Templates\TemplatesManager;

class CheckboxElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $formId;
  public $class;
  public $style;
  public $template = 'checkboxElement';
  public $label = 'label';
  private $value = false;
  public $inform;

  public $error = false;

  # Validation
  public $validation = array();

  public function __construct($name, array $config){

    $this->name = $name;

    foreach($config as $key => $value) {
      if($key == 'value') {
        $this->$key = (bool)$value;
        continue;
      }
      $this->$key = $value;
    }
  }

  public function __set($name, $value){
    if($name == 'value') {
      $this->value = (bool)$value;
    }
  }

  public function __get($name){
    if($name == 'value') {
      return (bool)$this->value;
    }
  }

  public function __isset($name){
    if($name == 'value') {
      return true;
    }
  }

  public function toString(){

    if(empty($this->id)){
      $this->id = $this->formId.'-'.$this->name;
    }

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    $element->set('value', $this->value);
    return $element->toString();
  }

}