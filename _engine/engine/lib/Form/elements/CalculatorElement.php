<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;
use lib\View\View;

class CalculatorElement implements IFormElement {

  # Properties
  public $id;
  public $formId;
  public $name;
  public $class;
  public $style;
  public $template = 'calculatorElement';
  public $label = 'label';
  public $type = 'text';
  public $rel = false;
  public $hide = false;
  public $percent = 7;
  public $minPrice = 0.10;
  public $value;
  public $inform;
  public $readonly = false;

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

    View::current()->setJs('numberFormatter/jquery.numberformatter-1.2.3.min');
    View::current()->setJs('videoLeadCalculator');

    $element = TemplatesManager::load($this->template);
    $element->set($this);
    return $element->toString();
  }

}