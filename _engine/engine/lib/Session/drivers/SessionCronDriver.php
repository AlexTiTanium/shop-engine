<?php

namespace lib\Session\drivers;

use lib\EngineExceptions\SystemException;
use lib\Core\Log;
use lib\Core\Config;
use lib\Session\ISession;

class SessionCronDriver implements ISession {

  private $sid;
  private $authorization = false;
  private $sessionId = 'SessionCronDriver';

  /**
   *
   * @return \lib\Session\drivers\SessionCronDriver
   */
  public function __construct(){
    $this->sid = 'cron';
  }

  /**
   * CronSessionDriver::start()
   *
   * @param mixed $login
   * @param mixed $password
   * @param bool $rememberMe
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  public function start($login, $password, $rememberMe = false){

    if(!Config::get('cron')) {
      throw new SystemException('Конфигурация крона не загружена');
    }

    $loginConfig = Config::get('cron')->login;
    $passwordConfig = Config::get('cron')->password;

    if(!$loginConfig) {
      throw new SystemException('Нет логина доступа');
    }
    if(!$passwordConfig) {
      throw new SystemException('Нет пароля доступа');
    }

    if(!$login or empty($login)) {
      throw new SystemException('нет парамтра login');
    }
    if(!$password or empty($password)) {
      throw new SystemException('нет парамтра password');
    }

    if($loginConfig === $login and $passwordConfig === $password) {
      $this->authorization = true;
      //Log::write('Cron session for ' . $login . ' start');
      return true;
    }

    throw new SystemException('Авторизация провалилась неверный логин или пароль');
  }

  /**
   * CronSessionDriver::update()
   *
   * @return bool
   */
  private function update(){
    return true;
  }

  /**
   * CronSessionDriver::gc()
   *
   * @return void
   */
  private function gc(){

  }

  /**
   * CronSessionDriver::setVar()
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setVar($key, $value){
    $_SESSION['vars'][$key] = $value;
  }

  /**
   * CronSessionDriver::deleteVar()
   *
   * @param mixed $key
   * @return void
   */
  public function deleteVar($key){
    if(isset($_SESSION['flashVars'][$key])) {
      unset($_SESSION['flashVars'][$key]);
    }
    if(isset($_SESSION['vars'][$key])) {
      unset($_SESSION['vars'][$key]);
    }
  }

  /**
   * CronSessionDriver::setFlash()
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setFlash($key, $value){
    $_SESSION['flashVars'][$key] = $value;
  }

  /**
   * CronSessionDriver::getVar()
   *
   * @param mixed $key
   * @return bool
   */
  public function getVar($key){

    if(isset($_SESSION['flashVars'][$key])) {
      $temp = $_SESSION['flashVars'][$key];
      unset($_SESSION['flashVars'][$key]);
      return $temp;
    }

    if(isset($_SESSION['vars'][$key])) {
      return $_SESSION['vars'][$key];
    }

    return false;
  }

  /**
   * CronSessionDriver::getUser()
   *
   * @return bool
   */
  public function getUser(){
    return true;
  }

  /**
   * CronSessionDriver::getSid()
   *
   * @return string
   */
  public function getSid(){
    return $this->sid;
  }

  /**
   * CronSessionDriver::close()
   *
   * @return bool
   */
  public function close(){
    unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
    $this->authorization = false;
    return true;
  }

  /**
   * CronSessionDriver::isLogged()
   *
   * @return bool
   */
  public function isLogged(){
    return $this->authorization;
  }


  public function pushFlash($key, $value) {
    if(!isset($_SESSION['flashVars'][$key])){
      $_SESSION['flashVars'][$key] = array();
    }

    array_push($_SESSION['flashVars'][$key], $value);
  }
}
