<?php

namespace lib\Form\elements;

use lib\View\View;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService;
use lib\Form\IFormElement;

class EditableListElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'editableListElement';
  public $label = 'label';
  public $multiple = false;
  public $prefix = '';
  public $value;
  public $items;
  public $inform;

  public $error = false;

  # Validation
  public $validation = array();

  private static $isJsIncluded = false;

  public function __construct($name, array $config){

    $this->name = $name;

    foreach($config as $key => $value) {
      $this->$key = $value;
    }
  }

  public function toString(){

    if(!self::$isJsIncluded) {
      View::get('html')->setJs(IncluderService::$skin->js->asmselect->getPath('jquery.asmselect', 'jsLink'));
      View::get('html')->setCss(IncluderService::$skin->js->asmselect->getPath('jquery.asmselect', 'cssLink'));
      self::$isJsIncluded = true;
    }

    $this->prefix = json_encode($this->prefix);

    $element = TemplatesManager::load($this->template);
    $element->set($this);

    return $element->toString();
  }

}