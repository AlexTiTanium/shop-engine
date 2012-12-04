<?php

namespace lib\Core\Manager;

use lib\Session\Session;
use lib\Core\Manager;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 18.09.12
 * Time: 1:21
 * To change this template use File | Settings | File Templates.
 */
class ManagerToken {

  private $token;

  private function generate(){
    return md5(SYSTEM_CODE . Session::getSid() . Manager::$Php->getIp());
  }

  public function get(){
    if(!$this->token){ $this->token = $this->generate(); }
    return $this->token;
  }

}