<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class FileUploadElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'fileUploadElement';
  public $label = 'label';
  public $type = 'file';
  public $rel = false;
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
    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}