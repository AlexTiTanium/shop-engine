<?php

namespace lib\Core;

use lib\EngineExceptions\SystemException;
use lib\Debugger\Debugger;
use lib\View\View;
use lib\Core\UrlService;
use lib\Core\Manager;
use lib\Session\Session;
use lib\Core\Controller;

/**
 * @throws SystemException
 */
class ControllerRouter {

  /**
   * Автоматически редиректить на страницу логина
   * @var bool
   */
  public static $autoRedirect = false;

  /**
   * Страница авторизации
   * @var string
   */
  public static $loginPage = '/home.html';

  /**
   * Текущий Action
   * @var string
   */
  protected $currentAction;

  /**
   * Текущий лисенер
   * @var bool
   */
  protected $eventListener = false;

  /**
   * Текущий controller
   * @var Controller $controller
   */
  protected $controller;

  /**
   * If need change state other router
   * @var ControllerRouter
   */
  protected $context;

  /**
   * Выполнилнить ли текущий запрос
   * @var bool
   */
  protected $execute;

  /**
   * Выполнялся ли запрос для текущего модуля (всего не только для текщего action)
   * @var bool
   */
  protected $executed;

  /**
   * Выполнялся ли запрос для текущего action(только для текщего action)
   * @var bool
   */
  protected $isExecutedThisAction;

  /**
   * Условия выполнения
   * @var array
   */
  protected $when;

  /**
   * Конструктор создает роутер на основе модуля
   *
   * @access public
   * @param Controller $controller
   **/
  public function __construct(Controller $controller){
    $this->controller = $controller;
    $this->executed = false;
  }

  /**
   * Change state router
   */
  public function setExecuted(){
    $this->executed = true;
  }

  /**
   * Только зарегеные
   *
   * @access protected
   *
   * @param bool $redirect
   * @return ControllerRouter
   */
  public function logged($redirect = false){

    if($this->executed) {
      return $this;
    }

    if(!Session::isLogged()) {
      $this->execute = false;
      if($redirect or self::$autoRedirect) {
        Session::setFlash('loginBackUrl', Manager::$UrlService->getCurrentUrl()->toString());
        Manager::$Php->redirect(self::$loginPage);
      }
    }
    return $this;
  }

  /**
   * Только не зарегеные
   *
   * @access protected
   *
   * @return ControllerRouter
   */
  public function notLogged(){

    if($this->executed) {
      return $this;
    }

    if(Session::isLogged()) {
      $this->execute = false;
    }
    return $this;
  }

  /**
   * Set state this router to other router
   *
   * @param ControllerRouter $router
   * @return ControllerRouter
   */
  public function updateContext(ControllerRouter $router){

    if($this->executed) {
      $router->setExecuted();
    }

    return $this;
  }

  /**
   * Если параметр запроса не пустой
   *
   * @param $paramKey
   * @return ControllerRouter
   */
  public function notEmpty($paramKey){

    if($this->executed) {
      return $this;
    }

    $url = $this->controller->getUrl();

    if($url->isEmpty($paramKey) and $url->get($paramKey) === null) {
      $this->execute = false;
    }

    return $this;
  }

  /**
   * Добавить Action
   *
   * @access public
   * @param string $name - имя Action файла
   * @return ControllerRouter
   **/
  public function addAction($name){


    if($this->executed) {
      return $this;
    }

    //echo $name."\n";
    $this->isExecutedThisAction = false;
    $this->execute = true;
    if($this->executed) {
      $this->execute = false;
    }

    $this->currentAction = $name;
    $this->eventListener = false;
    $this->when = false;

    return $this;
  }

  /**
   * Добавить addEventLisener
   *
   * @access public
   * @param string $name - имя $nameEvents файла
   * @return ControllerRouter
   **/
  public function addEventListener($name){

    if($this->executed) {
      return $this;
    }

    $this->isExecutedThisAction = false;
    $this->execute = true;
    if($this->executed) {
      $this->execute = false;
    }

    $this->currentAction = $name;
    $this->eventListener = true;
    $this->when = false;

    return $this;
  }

  /**
   * Условие выполнения
   *
   * @access protected
   * @param array|bool $array $array
   * @return ControllerRouter
   */
  public function when($array = false){

    if($this->executed) {
      return $this;
    }

    //echo '-'.('when')."\n";
    if($this->execute) {
      //  echo '--'.('when')."\n";
      $this->when = $array;

      //if($array==false and !$this->executed){ $this->execute = true; }

      if(!$this->check('action')) {
        return $this;
      }
      if(!$this->check('event')) {
        return $this;
      }
      if(!$this->check('type')) {
        return $this;
      }

      // echo '-----'.($this->execute)."\n";

      if($this->execute) {
        $this->execute();
      }
    }

    return $this;
  }

  /**
   * @param $closure
   *
   * @return ControllerRouter
   */
  public function Then($closure){
    $closure();
    return $this;
  }

  /**
   * Выполнить и продолжить выполнение
   *
   * @access public
   * @return ControllerRouter
   **/
  public function executeNext(){

    if($this->isExecutedThisAction) {
      $this->executed = false;
    }

    return $this;
  }

  /**
   * Добавить title
   *
   * @access protected
   * @param $title
   * @return ControllerRouter
   */
  public function addTitle($title){

    if($this->executed) {
      return $this;
    }

    if($this->execute) {
      View::getHtmlView()->addTitle($title);
    }
    return $this;
  }

  /**
   * Запуск Action
   *
   * @access protected
   * @return ControllerRouter
   **/
  public function execute(){

    if($this->executed) {
      return $this;
    }

    if($this->eventListener === true) {
      return $this->executeEvents();
    }

    $this->controller->getAction()->php($this->currentAction);
    $this->executed = true;
    $this->isExecutedThisAction = true;

    return $this;
  }

  /**
   * Запуск EventListener
   *
   * @access protected
   * @throws SystemException
   * @return ControllerRouter
   */
  public function executeEvents(){

    if($this->executed) {
      return $this;
    }

    $eventClassName = $this->controller->getEvents()->object($this->currentAction);
    $event = $this->controller->getUrl()->getEvent();
    $action = $this->controller->getUrl()->getAction();

    $events = new $eventClassName($this->controller);

    if(method_exists($events, $event)) {
      $events->$event();
      $this->executed = true;
    } elseif(method_exists($events, $action)) {
      $events->$action();
      $this->executed = true;  
    } elseif(method_exists($events, 'defaultEvent')) {
      $events->defaultEvent();
      $this->executed = true;
    } else {
      Debugger::error('Not found Event ' . $event, 'ControllerRouter Error');
    }

    return $this;
  }

  /**
   * Перегрузка роутера
   *
   * @return ControllerRouter
   **/
  public function reload(){
    $this->executed = false;
  }

  /**
   * Проверка условия
   *
   * @access protected
   * @param $param
   * @return bool|ControllerRouter
   */
  protected function check($param){

    if($this->executed) {
      return $this;
    }

    if($this->execute) {
      if(isset($this->when[$param])) {
        if($this->controller->getUrl()->getProperty($param) == $this->when[$param]) {
          return true;
        } else {
          return $this->execute = false;
        }
      }
    }
    return true;
  }

}