<?php

namespace lib\Core\Manager;

use lib\Session\Session;
use CryptLib\CryptLib;
use lib\Core\Manager;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 18.09.12
 * Time: 1:21
 * To change this template use File | Settings | File Templates.
 */
class ManagerCrypt {

  /**
   * @var \CryptLib\CryptLib
   */
  private $crypt;

  /**
   * @return \CryptLib\CryptLib
   */
  public function getCrypt(){

    if($this->crypt){ return $this->crypt; }

    return $this->crypt = new CryptLib();
  }

  /**
   * @param $password
   *
   * @return string hash
   */
  public function create($password){

    return $this->getCrypt()->createPasswordHash(md5($password . SYSTEM_CODE), '$2y$');
  }

  /**
   * @param $password
   * @param $hash
   *
   * @return bool - if false password is wrong
   */
  public function verify($password, $hash){

    return $this->getCrypt()->verifyPasswordHash(md5($password . SYSTEM_CODE), $hash);
  }
}