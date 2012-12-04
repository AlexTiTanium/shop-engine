<?php

namespace lib\Core\Data;

use lib\Core\Collections\ArrayCollection;
use lib\EngineExceptions\SystemException;


/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 21:15
 * To change this template use File | Settings | File Templates.
 */
class DataNodeCollection extends ArrayCollection {

  /**
   * @param string $key
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return DataNode
   */
  public function get($key){

    $return = parent::get($key);

    if($return == null){
      throw new SystemException('Index "'.$key.'" not found');
    }
  }

  /**
   * @param string $key
   * @param DataNode $dataNode
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  public function set($key, $dataNode){

    if(!$dataNode instanceof DataNode){
      throw new SystemException('dataNode must be instance of DataNode');
    }

    parent::set($key, $dataNode);
  }

  /**
   *
   * @param mixed $value
   *
   * @return bool|void
   * @throws \lib\EngineExceptions\SystemException
   */
  public function add($value){

    throw new SystemException('add not allow there');
  }
}