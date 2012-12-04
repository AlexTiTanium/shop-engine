<?php

namespace controllers\pages\lib;

use lib\Core\Controller;
use lib\Templates\TemplatesManager;
use lib\Core\ControllerRouter;

class PagesController extends Controller {

  /**
   * Настройка модуля
   *
   * @access Public
   **/
  protected function setDefinition(){
    $this->setName('pages');
    $this->setAllowedTypes('html', 'php', 'json');
    $this->setRouter(new ControllerRouter($this));
  }

  /**
   * Вызовы после настройки
   *
   * @access Public
   **/
  public function setUp(){
    TemplatesManager::addPath($this->templates);
  }

  /**
   * При вызове модуля
   *
   * @access Public
   **/
  protected function onCall(){

  }

}