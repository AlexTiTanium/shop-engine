<?php

namespace lib\View;

use lib\Captcha\Captcha;

class ViewCaptcha implements IView {
  
  public function toString(){
    $captcha = new Captcha();
    $captcha->show();
  }

  public function set($name, $value = false){
    // TODO: Implement set() method.
  }
}