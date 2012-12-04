<?php

namespace controllers\after\lib;

use lib\Core\Controller;
use lib\Core\ControllerRouter;

class AfterController extends Controller {

  /**
   * Настройка
   *
   * @access Public
   **/
  protected function setDefinition(){
    $this->setName('after');
    $this->setAllowedTypes('html', 'php', 'json');
    $this->setRouter(new ControllerRouter($this));
  }

  /**
   * Вызовы после настройки
   *
   * @access Public
   **/
  public function setUp(){
    //TemplatesManager::addPath($this->Templates);
  }

  /**
   * При вызове
   *
   * @access Public
   **/
  protected function onCall(){

  }

}