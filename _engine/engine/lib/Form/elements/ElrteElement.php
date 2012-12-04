<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Plugin\Plugin;
use lib\Form\IFormElement;

class ElrteElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'elrteElement';
  public $label = 'label';
  public $type = 'tiny';
  public $value;
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

    Plugin::get('elRTE')->load();

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}