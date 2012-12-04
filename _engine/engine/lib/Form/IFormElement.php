<?php

namespace lib\Form;

interface IFormElement {

  public function __construct($name, array $config);
  public function toString();

}
