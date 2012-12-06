<?php

use lib\Core\Events;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductListener extends Events {


  protected function setUp(){


  }

  public function update(){

    $data = $this->post->getJsonRequest('data');

  }

  public function create(){

    $data = $this->post->getJsonRequest('data');

  }

  public function destroy(){

    $data = $this->post->getJsonRequest('data');

  }

  /**
   *
   */
  public function defaultEvent(){


  }

}