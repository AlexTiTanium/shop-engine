<?php

use lib\Core\Controller;
use lib\Templates\TemplatesManager;
use lib\View\View;
use lib\Core\ControllerRouter;

class ShopController extends Controller {

  /**
    * Bootstrap controller
    *
    **/
  protected function setDefinition() {
    $this->setName('shop');
    $this->setAllowedTypes('html');
    $this->setRouter(new ControllerRouter($this));
  }

  /**
   * After bootstrap
   *
   **/
  public function setUp(){
    TemplatesManager::addPath($this->templates->getPath());
  }

  /**
   * On call
   *
   **/
  protected function onCall(){

  }

}