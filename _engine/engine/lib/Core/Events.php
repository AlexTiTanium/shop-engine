<?php

namespace lib\Core;

use lib\Core\UrlService;
use lib\Core\UrlService\Url;
use lib\View\IView;
use lib\View\ViewHtml;
use models\Interfaces\IUserInterface;
use lib\Core\Controller;
use lib\Core\IncluderService\Includer;
use lib\Session\Session;
use lib\View\View;
use lib\Core\Data;
use lib\EngineExceptions\SystemException;

class Events {

  /**
   * @var Data
   */
  protected $post;

  /**
   * @var Data
   */
  protected $get;

  /**
   * @var Url
   */
  protected $url;

  /**
   * @var IView|ViewHtml
   */
  protected $view;

  /**
   * @var IUserInterface|null
   */
  protected $user = null;

  /**
   * @var Controller
   */
  protected $controller;

  /**
   * @var Includer
   */
  protected $config;

  /**
   * Events::__construct()
   *
   */
  public function __construct(Controller $controller){

    $this->post = new Data($_POST);
    $this->url = $controller->getUrl();
    $this->get = new Data($controller->getUrl()->get());
    $this->view = View::getCurrent();
    $this->controller = $controller;
    $this->config = $controller->getConfig();

    if( Session::isLogged()){
      $this->user = Session::getUser();
    }

    $this->setUp();
  }

  /**
   * @param string $nameConfig
   *
   * @return array
   */
  protected function getConfig($nameConfig){
    return $this->config->yaml($nameConfig);
  }

  /**
   * You may override this
   */
  protected function setUp(){

  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   */
  public function defaultEvent(){
    throw new SystemException('Default event need override');
  }

}