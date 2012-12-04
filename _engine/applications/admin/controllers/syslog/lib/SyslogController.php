<?php

use lib\Core\Controller;
use lib\View\View;
use lib\Templates\TemplatesManager;
use lib\Core\ControllerRouter;

class SyslogController extends Controller {

  /**
   * Настройка
   *
   * @access Public
   **/
  protected function setDefinition(){
    $this->setName('syslog');
    $this->setAllowedTypes('json');
    $this->setRouter(new ControllerRouter($this));
    View::setCurrent(View::getJsonView());
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