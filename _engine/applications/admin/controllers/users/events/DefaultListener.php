<?php

use lib\Core\Events;
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

  /**
   * End session
   */
  public function quit(){

    Session::close();
    $this->view->set('ok');
  }

  /**
   * Session status
   */
  public function isAuthorized(){

    $this->view->set('isAuthorized', Session::isLogged());
  }

  /**
   * @throws lib\EngineExceptions\SystemException
   */
  public function defaultEvent(){
    throw new SystemException('Bad event');
  }
}
