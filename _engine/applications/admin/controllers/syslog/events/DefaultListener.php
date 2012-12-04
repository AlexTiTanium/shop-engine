<?php

use lib\Core\Events;
use lib\View\View;
use lib\EngineExceptions\SystemException;
use lib\Core\Manager;
use lib\Core\Log;
use lib\Session\Session;
use lib\Core\Data;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 10:36
 * To change this template use File | Settings | File Templates.
 */
class DefaultListener extends Events {

  private $log;

  /**
   * Get log data
   */
  public function getLog(){

    $this->view->set('data', $this->getPreparedLog());
    $this->view->set('count', count($this->getLogArray()));
  }

  /**
   * Clear log
   */
  public function clearLog(){

    $this->post->checkToken();

    Log::truncate();
  }

  /**
   * @return string
   */
  private function getPreparedLog(){

    $log = $this->getLogArray();

    foreach($log as &$value){
      $value = '<div>'.$value.'</div>';
    }

    return  implode('', $log);
  }

  /**
   * @return array
   */
  private function getLogArray(){

    if($this->log){ return $this->log; }

    return $this->log = Log::read();
  }

  /**
   * @throws lib\EngineExceptions\SystemException
   */
  public function defaultEvent(){
    throw new SystemException('Bad event');
  }
}
