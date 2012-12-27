<?php

use lib\Core\Controller;
use lib\View\View;
use lib\Templates\TemplatesManager;
use lib\Core\ControllerRouter;

class StorageController extends Controller {

  /**
   * Настройка
   *
   * @access Public
   **/
  protected function setDefinition(){
    $this->setName('storage');
    $this->setAllowedTypes('jpg', 'png');
    $this->setRouter(new ControllerRouter($this));
    View::setCurrent(View::getImageView());
  }

  /**
   * Вызовы после настройки
   *
   * @access Public
   **/
  public function setUp(){

  }

  /**
   * При вызове
   *
   * @access Public
   **/
  protected function onCall(){

  }

}