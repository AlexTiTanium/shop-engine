<?php

namespace lib\Core\Data;

use lib\Core\Data;
use lib\Core\Manager;
use lib\EngineExceptions\SystemException;

class DataValidator {

  private $config;

  /**
   * @var DataNodeCollection
   */
  private $nodeCollection;

  /**
   * @param $config
   */
  public function __construct($config){

    $this->config = $config;
    $this->nodeCollection = new DataNodeCollection();
  }

  /**
   * @param \lib\Core\Data $data
   *
   * @param bool $checkToken
   */
  public function validate(Data $data, $checkToken = true){

    if($checkToken){
      $data->checkToken();
    }

    foreach($this->config as $name=>$itemConfig){

      if(!$data->isExist($name)){
        $data->setNullVar($name);
      }

      $node = new DataNode($name, $data, $itemConfig, $this->nodeCollection);
      $this->nodeCollection->set($name, $node);
    }

    /**
     * @var DataNode $node
     */
    foreach($this->nodeCollection as $node){

      $node->runFilters();
      $node->runValidators();
    }
  }

}