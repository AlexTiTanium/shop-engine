<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class OptSelectElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'optSelectElement';
  public $label = 'label';
  public $value;
  public $items;
  public $inform;

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
    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}