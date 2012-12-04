<?php

namespace controllers\news\lib;

use lib\Core\Controller;
use lib\Templates\TemplatesManager;
use lib\Core\ControllerRouter;

class NewsController extends Controller {

  /**
   * Настройка модуля
   *
   * @access Public
   **/
  protected function setDefinition() {
    $this->setName('news');
    $this->setAllowedTypes('html', 'php');
    $this->setRouter(new ControllerRouter($this));
  }

  /**
   * Вызовы после настройки
   *
   * @access Public
   **/
  public function setUp() {
    TemplatesManager::addPath($this->templates);
  }

  /**
   * При вызове модуля
   *
   * @access Public
   **/
  protected function onCall() {

  }

}