<?php

namespace lib\Core;

use lib\Core\Router\Map;
use lib\Core\UrlService\Url;
use lib\Debugger\Debugger;
use lib\Core\Router\RouteFactory;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 24.11.12
 * Time: 11:12
 * To change this template use File | Settings | File Templates.
 */
class RouterService {

  private $map;
  private $defaults;
  private $url;
  private $cacheId = 'system_routes';

  public function __construct(){

    $this->map = new Map(new RouteFactory());
  }

  public function setRouteMap($routerMap){

    foreach($routerMap as $ruleName=>$rule){
      $this->defaults[$ruleName] = isset($rule['values']) ? $rule['values'] : array();
    }

    if(Manager::$Cache->contains($this->cacheId)){
      $routes = Manager::$Cache->fetch($this->cacheId);
      $this->map->setRoutes(unserialize($routes));
      return;
    }

    foreach($routerMap as $ruleName=>$rule){

      if(isset($rule['extend'])){

        $extendedRoutes =  isset($routerMap[$rule['extend']]) ? $routerMap[$rule['extend']] : array();
        $extendedRoutesParams = $extendedRoutes['params'];
        $extendedRoutes = $extendedRoutes['patterns'];

        $this->map->attach(isset($rule['attach']) ? $rule['attach'] : '/', array(
          'routes'=> $extendedRoutes,
          'params'=>$extendedRoutesParams,
          'values'=>isset($rule['values']) ? $rule['values'] : array()
        ));

      }elseif(is_array($rule['patterns'])){
        $this->map->attach(null , array(
          'routes'=>isset($rule['patterns']) ? $rule['patterns'] : array(),
          'params'=>isset($rule['params']) ? $rule['params'] : array(),
          'values'=>isset($rule['values']) ? $rule['values'] : array()
        ));
      }else{
        $this->map->add($ruleName, isset($rule['patterns']) ? $rule['patterns'] : array(), array(
          'params'=>isset($rule['params']) ? $rule['params'] : array(),
          'values'=>isset($rule['values']) ? $rule['values'] : array()
        ));
      }
    }

    $routes = $this->map->getRoutes();
    Manager::$Cache->save($this->cacheId, serialize($routes), 200);
  }

  public function match($query){

    if($this->url){ return $this->url; }

    $url = new Url();
    $get = array();

    parse_str(parse_url($query, PHP_URL_QUERY), $get);
    $query = parse_url($query, PHP_URL_PATH);

    if(substr($query, -1) == '/'){
      $query = substr($query, 0, -1);
    }

    $route = $this->map->match($query, $_SERVER);

    if($get){ $url->set(array('get'=>$get)); }

    if($route and $route->getName()){
      $url->set($this->defaults[$route->getName()]);
    }else{
      $url->set($this->defaults['default']);
    }

    if($route){
      $url->set($route->getValues());
    }

    return $this->url = $url;
  }

}