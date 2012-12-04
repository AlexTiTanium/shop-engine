<?php

namespace lib\Core;

use lib\Core\UrlService\Url;
use lib\EngineExceptions\SystemException;
use lib\Debugger\Debugger;

class Core {

  /**
   * @var Controller[]
   */
  static private $controllers = array();

  /**
   * Core::call()
   *
   * @param Url $url
   *
   * @throws SystemException
   * @return void
   */
  static public function call(Url $url){

    self::startApplication($url->getApplication());

    if(self::isControllerExist($url->getController())){
      $controller = self::getController($url->getController());
      $url->setController($controller->getName());
      $controller->call($url);
    }else{
      Debugger::warn('Controller: '.$url->getController().' not found');
      Manager::$Headers->error404();
      throw new SystemException('Ошибка 404. Запрошеная вами страница не существует');
    }
  }

  /**
   * Core::getController()
   *
   * @static
   * @param string $name controller name
   * @return Controller
   */
  static public function getController($name){

    $name = strtolower($name);

    if(isset(self::$controllers[$name])){ return self::$controllers[$name]; }
    $controller = IncluderService::$controllers->setPath($name.'/lib')->object(ucfirst($name) . 'Controller');

    return self::$controllers[$name] = new $controller();
  }

  /**
   * Core::startApplication()
   *
   * @param string $name
   * @return void
   */
  static private function startApplication($name){

    if(!defined('CURRENT_APPLICATION')){
      define('CURRENT_APPLICATION', $name);
      IncluderService::requireApplication($name);
    }
  }

  /**
   * @param string $name
   *
   * @return boolean
   */
  static private function isControllerExist($name){

    return IncluderService::$controllers->setPath($name)->isExist('router', 'php');
  }

}