<?php

namespace lib\Core;

use lib\Core\UrlService\Url;
use lib\EngineExceptions\SystemException;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.11.12
 * Time: 13:36
 * To change this template use File | Settings | File Templates.
 */
class UrlService {

  /**
   * @var string
   */
  private $query;

  /**
   * @var Url
   */
  private $urlQuery;

  /**
   * @var RouterService
   */
  private $router;

  /**
   * @var string
   */
  private $defaultApplication;

  /**
   * @param RouterService $router
   */
  public function __construct(RouterService $router){

    $this->router = $router;
  }

  /**
   * @param string $query
   * @param array $routingMap
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  public function mapQuery($query, $routingMap){

    if(!isset($routingMap['default']['values']['application'])){
      throw new SystemException('You must set default application');
    }

    $this->defaultApplication = $routingMap['default']['values']['application'];

    $this->router->setRouteMap($routingMap);
    $this->urlQuery = $this->router->match($query);
  }

  /**
   * @return Url
   */
  public function getCurrentUrl(){

    if(!$this->urlQuery){ return null; }
    return clone $this->urlQuery;
  }

  /**
   * @return string
   */
  public function getDefaultApplication(){
    return $this->defaultApplication;
  }

  /**
   * @param bool|array $array
   *
   * @return String
   */
  public function toString($array = false){
    return $this->getCurrentUrl()->toString($array);
  }

  /**
   * @param array|boolean $array
   *
   * @return Array
   */
  public function toArray($array = false){
    return $this->getCurrentUrl()->toArray($array);
  }

  /**
   * @return string
   */
  public function getCurrentQuery(){
    return $this->query;
  }
}
