<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Form\IFormElement;

class GroupCheckboxElement implements IFormElement {

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'groupCheckboxElement';
  public $label = 'label';
  public $value = array();
  public $items = array();
  public $inform;

  public $error = false;

  # Validation
  public $validation = array();

  /**
   * GroupCheckboxElement::__construct()
   *
   * @param mixed $name
   * @param mixed $config
   * @return \lib\Form\elements\GroupCheckboxElement
   */
  public function __construct($name, array $config){

    $this->name = $name;

    foreach($config as $key => $value) {
      $this->$key = $value;
    }
  }

  /**
   * GroupCheckboxElement::toString()
   *
   * @return string
   */
  public function toString(){
    $element = TemplatesManager::load($this->template);
    $element->set($this);
    $element->set('value', $this->value);
    return $element->toString();
  }

}