<?php

use lib\Core\Events;
use lib\Core\Manager;
use lib\Core\Data;

class StorageListener extends Events {

  protected function setUp(){

  }

  /**
   *
   */
  public function defaultEvent(){

    echo "Works fine";
  }

}