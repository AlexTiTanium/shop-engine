<?php

namespace lib\Session\drivers;

use lib\EngineExceptions\SystemException;
use lib\Session\Session;
use lib\Core\UrlService;
use lib\Core\Manager;
use lib\Session\ISession;
use lib\Session\ISessionStorage;

class SessionDoctrineDriver implements ISession {

  private $sid;

  /**
   * @var \models\Interfaces\ISessionInterface $sessionObject
   */
  private $sessionObject;

  /**
   * @var \models\Interfaces\IUserInterface $userObject
   */
  private $userObject;

  private $maxLifeTime = '36000';
  private $isLogged = false;

  /**
   * @var \lib\Session\ISessionStorage $sessionStorage
   */
  private $sessionStorage;


  private $sessionId = 'DoctrineDriver';

  /**
   * AdminDbSessionDriver::__construct()
   *
   * @param string $sessionId
   * @param \lib\Session\ISessionStorage $sessionStorage
   * @throws \lib\EngineExceptions\SystemException
   * @return \lib\Session\drivers\SessionDoctrineDriver
   */
  public function __construct($sessionId, ISessionStorage $sessionStorage){

    //$this->gc();

    $this->sessionStorage = $sessionStorage;
    $this->sessionId = $sessionId;

    if(!isset($_SESSION)) {
      if(!session_start()) {
        throw new SystemException('Немогу начать сессию');
      }
    }

    $this->sid = Session::genSid();

    if(isset($_SESSION[$this->sessionId])) {

      /**
       * @var \models\Interfaces\ISessionInterface $this->sessionObject
       */
      $this->sessionObject = $sessionStorage->findSessionById($_SESSION[$this->sessionId]);

      if(!$this->sessionObject) {
        unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
        //Manager::$Php->redirect(UrlService::get()->toString(array('controller' => 'main', 'action' => 'login')));
        //throw new SystemException('Ваша сессия завершина');
        return;
      }

      if($this->sessionObject->getSid() !== $this->sid) {
        $sessionStorage->delete($this->sessionObject);
        unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
        //Manager::$Php->redirect(UrlService::get()->toString(array('controller' => 'main', 'action' => 'login')));
        //throw new SystemException('Неверная сессия');
        return;
      }

      if((string)$this->sessionObject->getExpSession() < time()) {
        $sessionStorage->delete($this->sessionObject);
        unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
        //Manager::$Php->redirect(UrlService::get()->toString(array('controller' => 'main', 'action' => 'login')));
        //throw new SystemException('Session too old');
        return;
      }

      if($this->sessionObject->getIp() !== Manager::$Php->getIp()) {
        $sessionStorage->delete($this->sessionObject);
        unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);
        //Manager::$Php->redirect(UrlService::get()->toString(array('controller' => 'main', 'action' => 'login')));
        //throw new SystemException('Other session ip');
        return;
      }

      $this->isLogged = true;
      $this->update();
    }
  }

  /**
   * AdminDbSessionDriver::start()
   *
   * @param mixed $login
   * @param mixed $password
   * @param bool $rememberMe
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  public function start($login, $password, $rememberMe = false){

    $this->gc();

    if($this->isLogged) {
      throw new SystemException('Пользователь уже авторизирован');
    }

    /**
     * @var \models\Interfaces\IUserInterface $user
     */
    $user = $this->sessionStorage->findUserByLogin($login);

    if(!$user) {
      return false;
    }

    if(!$user->getEnable()) {
      throw new SystemException('Ваш акаунт заблокирован');
    }

    if(!$user->getIsActivated()){
      throw new SystemException('Ваш акаунт не активирован');
    }

    if(!Manager::$Crypt->verify($password, $user->getPassword())) {
      return false;
    }

    $this->sessionStorage->deleteSessionsBySid($this->sid);

    $this->sessionStorage->deleteSessionsByUserId($user->getId());

    /**
     * @var \models\Interfaces\ISessionInterface $session
     */
    $session = $this->sessionStorage->createSession($user);

    if($rememberMe){
      session_set_cookie_params(Session::$rememberMeTime);
      $this->maxLifeTime += Session::$rememberMeTime;
    }

    Session::regenerateSid();
    $this->sid = Session::genSid();

    $session->setSid($this->sid);
    $session->setUserId($user->getId());

    $session->setIp(Manager::$Php->getIp());
    $session->setNow('Online');
    $session->setExpOnline(time()+$this->maxLifeTime);

    if($rememberMe){
      $session->setRememberMe(true);
      $session->setExpSession(time()+Session::$rememberMeTime);
    }else{
      $session->setRememberMe(false);
      $session->setExpSession(time()+$this->maxLifeTime);
    }

    $this->sessionStorage->insert($session);

    $_SESSION[$this->sessionId] = $session->getId();

    $this->sessionObject = $session;

    return $this->isLogged = true;
  }

  /**
   * AdminDbSessionDriver::update()
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function update(){

    if(!$this->isLogged) {
      throw new SystemException('Пользователь должен быть авторизирован');
    }

    $halfSession = time()+$this->maxLifeTime/2;

    if((string)$this->sessionObject->getExpSession() > $halfSession){
      return;
    }

    if($this->sessionObject->getRememberMe() === true and $this->sessionObject->getNow() === 'Offline'){
      session_set_cookie_params(Session::$rememberMeTime);
      session_regenerate_id(false);
      $this->sid = Session::genSid();
    }

    $this->sessionObject->setSid($this->sid);
    $this->sessionObject->setIp(Manager::$Php->getIp());
    $this->sessionObject->setExpOnline(time()+$this->maxLifeTime);
    $this->sessionObject->setNow('Online');

    if($this->sessionObject->getRememberMe() === true){
      $this->sessionObject->setExpSession(time()+Session::$rememberMeTime);
    }else{
      $this->sessionObject->setExpSession(time()+$this->maxLifeTime);
    }

    $this->sessionStorage->update($this->sessionObject);
  }

  /**
   * AdminDbSessionDriver::gc()
   *
   * @return void
   */
  private function gc(){
    $this->sessionStorage->deleteOlder(time());
    $this->sessionStorage->offlineOlder(time());
  }

  /**
   * AdminDbSessionDriver::setVar()
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setVar($key, $value){
    $_SESSION['vars'][$key] = $value;
  }

  /**
   * AdminDbSessionDriver::deleteVar()
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
   * AdminDbSessionDriver::setFlash()
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function setFlash($key, $value){
    $_SESSION['flashVars'][$key] = $value;
  }

  /**
   * AdminDbSessionDriver::pushFlash()
   *
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function pushFlash($key, $value){

    if(!isset($_SESSION['flashVars'][$key])){
      $_SESSION['flashVars'][$key] = array();
    }

    array_push($_SESSION['flashVars'][$key], $value);
  }

  /**
   * AdminDbSessionDriver::getVar()
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
   * AdminDbSessionDriver::getUser()
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return \models\Interfaces\IUserInterface
   */
  public function getUser(){

    if($this->isLogged === false) {
      throw new SystemException('Пользователь должен быть авторизирован');
    }

    if(!$this->sessionObject) {
      $this->close();
      throw new SystemException('Не найден регистратор сесcии');
    }

    if(!$this->userObject) {
      $this->userObject = $this->sessionStorage->getUser($this->sessionObject);
    }

    if(!$this->userObject) {
      $this->close();
      throw new SystemException('Пользователь не найден ошибка сессии, сессия будет завершина');
    }

    return $this->userObject;
  }

  /**
   * AdminDbSessionDriver::getSid()
   *
   * @return string
   */
  public function getSid(){
    return $this->sid;
  }

  /**
   * AdminDbSessionDriver::close()
   *
   * @return void
   */
  public function close(){
    $this->gc();
    unset($_SESSION[$this->sessionId], $_SESSION['vars'], $_SESSION['flashVars']);

    $this->sessionStorage->delete($this->sessionObject);
  }

  /**
   * AdminDbSessionDriver::isLogged()
   *
   * @return bool
   */
  public function isLogged(){
    return $this->isLogged;
  }

}