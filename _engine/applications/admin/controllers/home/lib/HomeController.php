<?php

use lib\Core\Controller;
use lib\Templates\TemplatesManager;
use lib\Core\ControllerRouter;

class HomeController extends Controller {

  /**
   * Настройка
   *
   * @access Public
   **/
  protected function setDefinition(){
    $this->setName('home');
    $this->setAllowedTypes('html', 'php', 'json');
    $this->setRouter(new ControllerRouter($this));
  }

  /**
   * Вызовы после настройки
   *
   * @access Public
   **/
  public function setUp(){
    //TemplatesManager::addPath($this->templates->getPath());
  }

  /**
   * При вызове
   *
   * @access Public
   **/
  protected function onCall(){

  }

}