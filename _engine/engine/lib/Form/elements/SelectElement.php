<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\View\View;
use lib\Form\IFormElement;

class SelectElement implements IFormElement {

  # Properties
  public $id;
  public $formId;
  public $name;
  public $class;
  public $style;
  public $template = 'selectElement';
  public $label = 'label';
  public $multiple = false;
  public $size = 1;
  public $value;
  public $items;
  public $inform;

  public $onSelect = false;

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

    View::current()->setJs('multiSelect/jquery.multiselect.min');
    View::current()->setJsCss('multiSelect/css/jquery.multiselect');
    View::current()->setJs('multiSelect/i18n/jquery.multiselect.ru');

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}