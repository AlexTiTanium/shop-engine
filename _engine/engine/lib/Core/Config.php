<?php

namespace lib\Core;

use lib\Core\Config\ArrayConfigProvider;
use lib\Core\Config\IConfigProvider;

class Config {

  /**
   * @static
   * @var IConfigProvider[]
   */
  private static $providers;

  /**
   * Загрузить конфиг
   *
   * @static
   *
   * @param string $name
   *
   * @return IConfigProvider
   */
  public static function loadSystem($name){

    if(isset(self::$providers[$name])){
      return self::$providers[$name];
    }

    $id = 'system_config_'.$name;

    if(Manager::$Cache->contains($id)){
      $data = Manager::$Cache->fetch($id);
    }else{
      $data = IncluderService::$systemConfigs->yaml($name);
      Manager::$Cache->save($id, $data, 200);
    }

    return self::$providers[$name] = new ArrayConfigProvider($data);
  }

}