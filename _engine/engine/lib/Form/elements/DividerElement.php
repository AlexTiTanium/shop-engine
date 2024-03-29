<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class DividerElement implements IFormElement {

  # Properties
  public $id;
  public $formId;
  public $name;
  public $class;
  public $style;
  public $template = 'dividerElement';
  public $label = 'label';

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