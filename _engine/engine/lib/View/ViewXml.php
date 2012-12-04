<?php

namespace lib\View;

use lib\Xml\Xml;

class ViewXml{

  private $data = array();

  public function set(array $array){
    $this->data = $array;
  }

  public function toString(){
    $xmlObj = new Xml('<?xml version="1.0" encoding="utf-8" ?> <data></data>');
    $xmlObj->addArray($this->data);

    return $xmlObj->toString();
  }

}