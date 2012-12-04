<?php

namespace lib\Core\Config;
use lib\Yaml\Yaml;
use lib\EngineExceptions\SystemException;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 24.11.12
 * Time: 8:39
 * To change this template use File | Settings | File Templates.
 */
class ArrayConfigProvider implements IConfigProvider {

  /**
   * @var array
   */
  private $data = null;

  /**
   * @param $data
   */
  public function __construct($data){

    $this->data = $data;
  }

  /**
   * @param null $key
   *
   * @return ArrayConfigProvider|string
   * @throws \lib\EngineExceptions\SystemException
   */
  public function get($key = null){

    if(is_null($key)){ return $this->data; }

    if(!isset($this->data[$key])){ throw new SystemException('Not found key:'.$key.' in config'); }

    if(!is_array($this->data[$key])){
      return $this->data[$key];
    }

    return new ArrayConfigProvider($this->data[$key]);
  }

  /**
   * @param $key
   *
   * @return mixed
   * @throws \lib\EngineExceptions\SystemException
   */
  public function value($key){

    if(!isset($this->data[$key])){ throw new SystemException('Not found key:'.$key.' in config'); }

    return $this->data[$key];
  }
}
