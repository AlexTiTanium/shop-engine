<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Plugin\Plugin;
use lib\Form\IFormElement;

class EditAreaElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'editareaElement';
  public $label = 'label';
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

    Plugin::get('Editarea')->load();

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}