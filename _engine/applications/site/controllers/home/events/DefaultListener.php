<?php

use lib\Core\Events;
use lib\EngineExceptions\SystemException;

class DefaultListener extends Events {


  /**
   *
   */
  public function defaultEvent(){

    $this->view->extendBy('home');
  }

}