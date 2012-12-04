<?php

use lib\Core\Controller;
use lib\View\View;
use lib\Core\ControllerRouter;

class ShopController extends Controller {

  /**
    * Bootstrap controller
    *
    **/
  protected function setDefinition() {
    $this->setName('shop');
    $this->setAllowedTypes('json');
    $this->setRouter(new ControllerRouter($this));
    View::setCurrent(View::getJsonView());
  }

  /**
   * After bootstrap
   *
   **/
  public function setUp(){

  }

  /**
   * On call
   *
   **/
  protected function onCall(){

  }

}