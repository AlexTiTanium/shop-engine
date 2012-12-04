<?php

namespace lib\Session;

use lib\EngineExceptions\SystemException;
use lib\Core\Manager;

class Session {

  const MESSAGE_TYPE_NOTICE = 'notice';
  const MESSAGE_TYPE_INFO = 'info';
  const MESSAGE_TYPE_SUCCESS = 'success';
  const MESSAGE_TYPE_ERROR = 'error';

  /**
   * How long store session
   * @var int
   */
  public static $rememberMeTime = 1209600; // 2 weeks

  /**
   * @var ISession
   */
  private static $sessionDriver;

  /**
   * Session::getSid()
   *
   * @return string - md5(sid.SYSTEM_CODE)
   */
  public static function genSid(){
    return md5(session_id() . SYSTEM_CODE);
  }

  /**
   * Session::regenerateSid()
   *
   */
  public static function regenerateSid(){
    session_regenerate_id(true);
  }

  /**
   * Session::open()
   *
   * @param \lib\Session\ISession $driver
   * @return \lib\Session\ISession
   */
  public static function open(ISession $driver){
    return self::$sessionDriver = $driver;
  }

  /**
   * Session::start()
   *
   * @param mixed $login
   * @param mixed $password
   * @param bool $rememberMe
   * @return mixed
   */
  public static function start($login, $password, $rememberMe = false){
    return self::$sessionDriver->start($login, $password, $rememberMe);
  }

  /**
   * Session::setVar()
   *
   * @param mixed $key
   * @param mixed $value
   * @return
   */
  public static function setVar($key, $value){
    return self::$sessionDriver->setVar($key, $value);
  }

  /**
   * Session::deleteVar()
   *
   * @param mixed $key
   * @return
   */
  public static function deleteVar($key){
    return self::$sessionDriver->deleteVar($key);
  }

  /**
   * Session::setFlash()
   *
   * @param mixed $key
   * @param mixed $value
   * @return
   */
  public static function setFlash($key, $value){
    return self::$sessionDriver->setFlash($key, $value);
  }

  /**
   * Session::getVar()
   *
   * @param mixed $key
   * @return
   */
  public static function getVar($key){
    return self::$sessionDriver->getVar($key);
  }

  /**
   * Session::getUser()
   *
   * @return \Documents\User
   */
  public static function getUser(){
    return self::$sessionDriver->getUser();
  }

  /**
   * Session::getSid()
   *
   * @return
   */
  public static function getSid(){
    return self::$sessionDriver->getSid();
  }

  /**
   * Session::isLogged()
   *
   * @return
   */
  public static function isLogged(){
    return self::$sessionDriver->isLogged();
  }

  /**
   * Session::close()
   *
   * @return
   */
  public static function close(){
    return self::$sessionDriver->close();
  }

  /**
   * @static
   * @param string $title
   * @param string $msg
   * @param string $type - see Sessions::MESSAGE_TYPE_ const
   */
  public static function addMessage($title, $msg, $type = Session::MESSAGE_TYPE_NOTICE) {
    self::$sessionDriver->pushFlash('messages', array('type'=>$type, 'title'=>$title,'text'=>$msg));
  }

}