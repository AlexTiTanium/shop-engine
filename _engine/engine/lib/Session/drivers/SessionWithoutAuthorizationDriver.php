<?php

namespace lib\Session\drivers;

use lib\EngineExceptions\SystemException;
use lib\Session\Session;
use lib\Session\ISession;

class SessionWithoutAuthorizationDriver implements ISession {

  /**
   * @var string
   */
  private $sid;

  /**
   * @var string
   */
  private $sessionId = 'SessionCronDriver';

  /**
   *
   * @param string $sessionId
   * @throws \lib\EngineExceptions\SystemException
   * @return \lib\Session\drivers\SessionWithoutAuthorizationDriver
   */
  public function __construct($sessionId){

    $this->sessionId = $sessionId;

    if(!isset($_SESSION)) {
      if(!session_start()) {
        throw new SystemException('Немогу начать сессию');
      }
    }

    $this->sid = Session::genSid();
  }

  /**
   *
   * @param mixed $login
   * @param mixed $password
   * @param bool $rememberMe
   * @return bool
   */
  public function start($login, $password, $rememberMe = false){
    return true;
  }

  /**
   *
   * @return bool
   */
  private function update(){
    return true;
  }

  /**
   *
   * @return void
   */
  private function gc(){

  }

  /**
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setVar($key, $value){
    $_SESSION['vars'][$key] = $value;
  }

  /**
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
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setFlash($key, $value){
    $_SESSION['flashVars'][$key] = $value;
  }

  /**
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
   *
   * @return bool
   */
  public function getUser(){
    return true;
  }

  /**
   *
   * @return string
   */
  public function getSid(){
    return $this->sid;
  }

  /**
   *
   * @return bool
   */
  public function close(){
    unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
    return true;
  }

  /**
   *
   * @return bool
   */
  public function isLogged(){
    return true;
  }

  public function pushFlash($key, $value) {
    if(!isset($_SESSION['flashVars'][$key])){
      $_SESSION['flashVars'][$key] = array();
    }

    array_push($_SESSION['flashVars'][$key], $value);
  }
}