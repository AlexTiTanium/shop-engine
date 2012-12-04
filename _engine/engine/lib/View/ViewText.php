<?php

namespace lib\View;

use lib\Core\Manager;

class ViewText implements IView {

  private $ort = false;
  private $text = array();

  /**
   * add ort header ok
   */
  public function ortOk(){
    $this->ort = 'Ok';
  }

  /**
   * @param $code - add ort header
   */
  public function ort($code){
    $this->ort = $code;
  }

  /**
   * @param $text
   * @param bool $value - not used
   */
  public function set($text, $value = false){
    $this->text[] = $text;
  }

  public function toString(){

    if($this->ort) {
      Manager::$Headers->ort($this->ort);
    }

    return implode("\n",$this->text);
  }

}