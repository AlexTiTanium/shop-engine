<?php

namespace lib\View;

use lib\Core\Log;

class ViewCron implements IView {

  /**
   * @param $text
   * @param bool $value - no used!!
   */
  public function set($text, $value = false){
    Log::write($text);
  }

  public function toString(){
    Log::write('Крон окнчил работу');
  }

}