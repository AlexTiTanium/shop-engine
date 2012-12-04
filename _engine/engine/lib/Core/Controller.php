<?php

namespace lib\Core;

use lib\EngineExceptions\SystemException;
use lib\Debugger\Debugger;
use lib\Core\UrlService;
use lib\Core\IncluderService;
use lib\Core\ControllerRouter;
use lib\Core\UrlService\Url;
use lib\Core\IncluderService\Includer;

/**
 * Controller
 *
 * @throws SystemException
 * @abstract
 */
abstract class Controller {

  /**
   * Название контроллера
   * @var string
   */
  protected $name;

  /**
   * Резрещёные типы
   * @var array
   */
  protected $typesAllowed = array();

  /**
   * C какими параметрами запущен контроллер
   * @var bool|Url
   */
  protected $url;

  /**
   * IncluderService class
   * @var Includer
   */
  protected $events;

  /**
   * includer actions
   * @var Includer
   */
  protected $action;

  /**
   * includer контроллера
   * @var Includer
   */
  protected $controller;

  /**
   * includer Forms
   * @var Includer
   */
  protected $config;

  /**
   * includer Templates
   * @var Includer
   */
  protected $templates;

  /**
   * @var ControllerRouter
   */
  protected $router;

  /**
   * @var array
   */
  protected $parameters = array();

  /**
   * Controller::setDefinition()
   *
   * @access protected
   * @return void
   */
  abstract protected function setDefinition();

  /**
   * Controller::setUp()
   *
   * @access protected
   * @return void
   */
  abstract protected function setUp();

  /**
   * Controller::onCall()
   *
   * @access protected
   * @return void
   */
  abstract protected function onCall();

  /**
   * Controller::__construct()
   *
   * @access public
   * @throws \lib\EngineExceptions\SystemException
   * @return Controller
   */
  public function __construct(){

    $this->setDefinition();

    if(!$this->name) {
      throw new SystemException('Не задано имя модуля, задать можно через $this->setName');
    }
    if(!$this->typesAllowed) {
      throw new SystemException('Не заданы допустимые расширения вызовов, задать можно через $this->setAllowedTypes');
    }
    if(!$this->router) {
      throw new SystemException('Не задан класс роутера, задать можно через $this->setRouter');
    }
    if(!$this->router instanceof ControllerRouter) {
      throw new SystemException('ControllerRouter должен быть потомком или самим классом роутер');
    }

    $this->events = IncluderService::$controllers->setPath($this->name.DS.'events');
    $this->action = IncluderService::$controllers->setPath($this->name.DS.'actions');
    $this->config = IncluderService::$controllers->setPath($this->name.DS.'config');
    $this->templates = IncluderService::$controllers->setPath($this->name.DS.'templates');
    $this->controller = IncluderService::$controllers->setPath($this->name);

    $this->setUp();
  }

  /**
   * Controller::setName()
   *
   * @param mixed $name
   * @return void
   */
  protected function setName($name){
    $this->name = $name;
  }

  /**
   * Controller::setAllowedTypes()
   *
   * @return void
   */
  protected function setAllowedTypes(){
    $this->typesAllowed = func_get_args();
  }

  /**
   * Controller::setRouter()
   *
   * @param mixed $Router
   * @return void
   */
  protected function setRouter($Router){
    $this->router = $Router;
  }

  /**
   * Controller::checkTypes()
   *
   * @return boolean
   */
  protected function checkTypes(){

    if(array_search($this->url->getType(), $this->typesAllowed) !== false) {
      return true;
    }

    return false;
  }

  /**
   * Controller::call()
   *
   * @param bool|Url $url
   *
   * @return void
   */
  public function call(Url $url = null){

    $this->url = $url;
    $this->url->setController($this->getName());

    Debugger::addCall('Controller: "'.$this->getName().'" call', $url);

    // Проверить типы вызова модуля
    if(!$this->checkTypes()) {
      Debugger::warn('Controller: "'.$this->getName().'" bad type call', 'Was called with content type: '.$url->getType().' allowed only:'.implode(', ',$this->typesAllowed));
      return;
    }

    // На вызове модуля
    $this->onCall();

    // Перегрузить роутер
    $this->router->reload();

    // Выполнить
    $this->controller->php('router');
  }

  /**
   * Controller::callAction()
   *
   * @param string $action
   * @return void
   */
  public function callAction($action){
    // Выполнить action
    $this->action->php($action);
  }

  /**
   * Controller::set()
   *
   * @param string $name
   * @param mixed  $var
   * @return void
   */
  public function set($name, $var){
    $this->parameters[$name] = $var;
  }

  /**
   * Controller::get()
   *
   * @param string $name
   * @throws \lib\EngineExceptions\SystemException
   * @return mixed
   */
  public function get($name){

    if(!isset($this->parameters[$name])) {
      throw new SystemException('Controller::get(), not found: "'.$name.'" in params');
    }

    return $this->parameters[$name];
  }

  /**
   * @return \lib\Core\IncluderService\Includer
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * @return \lib\Core\IncluderService\Includer
   */
  public function getController()
  {
    return $this->controller;
  }

  /**
   * @return \lib\Core\IncluderService\Includer
   */
  public function getEvents()
  {
    return $this->events;
  }

  /**
   * @return \lib\Core\IncluderService\Includer
   */
  public function getConfig()
  {
    return $this->config;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return \lib\Core\ControllerRouter
   */
  public function getRouter()
  {
    return $this->router;
  }

  /**
   * @return \lib\Core\IncluderService\Includer
   */
  public function getTemplates()
  {
    return $this->templates;
  }

  /**
   * @return bool|\lib\Core\UrlService\Url
   */
  public function getUrl()
  {
    return $this->url;
  }
}