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
class LoginListener extends Events {

  const MAX_TRY_FOR_BAN = 3;

  /**
   * Return num of bad trays
   *
   * @param $login
   *
   * @return int|string
   */
  private function getNumOfTry($login){

    $cache = Manager::$Cache;
    $key = $this->getKey($login);

    $try  = $cache->fetch($key);

    if(!$try){
      return 0;
    }

    return $try;
  }

  /**
   * Return key for geting data from cache
   *
   * @param $login
   *
   * @return string
   */
  private function getKey($login){

    $ip = Manager::$Php->getLongIp();
    return 'fail2ban'.$ip.$login;
  }

  /**
   * Save bad try
   *
   * @param $login
   */
  private function badLoginTry($login){

    $cache = Manager::$Cache;
    $key = $this->getKey($login);

    $try = $this->getNumOfTry($login) + 1;

    $cache->save($key, $try, 60);
  }

  /**
   * Check if no dad for this login
   *
   * @param $login
   *
   * @throws lib\EngineExceptions\SystemException
   */
  private function checkBan($login){

    $try = $this->getNumOfTry($login);

    if($try > self::MAX_TRY_FOR_BAN){
      $_POST['password'] = substr($_POST['password'], 0, 2) . '*******';
      throw new SystemException('Слишком много неудачных попыток, попробуйте позже.');
    }
  }

  /**
   * There login event
   *
   * @throws lib\EngineExceptions\SystemException
   */
  public function defaultEvent(){

    $data = $this->post->validate($this->getConfig('loginData'));

    $this->checkBan($data->getRequired('login'));

    $logged = Session::start($data->getRequired('login'), $data->getRequired('password'));

    if(!$logged) {
      $this->badLoginTry($data->getRequired('login'));
      $_POST['password'] = substr($data->getRequired('password'), 0, 2) . '*******';
      Log::write('Неудачная попытка авторизации в панель администратора');
      throw new SystemException('Неверный логин или пароль');
    }

    $this->view->set(array('msg'=>'ok'));
  }
}
