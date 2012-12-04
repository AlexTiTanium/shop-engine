<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class RulesElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'rulesElement';
  public $label = 'label';
  public $type = 'text';
  public $rel = false;
  public $value;
  public $inform;
  public $rulesUrl = '/';
  public $placeHolder = false;

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