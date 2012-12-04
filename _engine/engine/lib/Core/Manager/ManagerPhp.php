<?php

namespace lib\Core\Manager;

use lib\Debugger\Debugger;
use lib\Core\Manager;
use lib\Core\Log;
use lib\Core\IncluderService;
use lib\Core\UrlService;
use lib\Session\Session;

class ManagerPhp {

  /**
   * Установить порядок обработки уровня ошибок в php
   * @param 0  - Отключить
   * @param -1 - Все ошибки
   * @param E_ERROR | E_WARNING | E_PARSE              - Report simple running errors
   * @param E_ERROR | E_WARNING | E_PARSE | E_NOTICE   - Reporting E_NOTICE can be good too (to report uninitialized variables or catch variable name misspellings ...)
   * @param E_ALL ^ E_NOTICE                           - Report all errors except E_NOTICE This is the default value set in php.ini
   * @param E_ALL                                      - Report all errors
   * @return int
   * @access Public
   **/
  public function errorReporting($value){
    return error_reporting($value);
  }

  /**
   * @param bool $debugMode
   */
  public function setMode($debugMode = false){
    if($debugMode){
      $this->errorReporting(-1);
      $this->displayErrors(true);
      $this->displayStartupErrors(true);
      $this->xdebug(true);
    }else {
      Manager::$Php->errorReporting(0);
      Manager::$Php->displayErrors(false);
      Manager::$Php->displayStartupErrors(false);

      Debugger::disable();
    }

  }

  /**
   * Установить часовой пояс
   *
   * @param  string $timezone
   * @param  http://ua2.php.net/manual/en/timezones.php
   * @param  Europe/Moscow
   * @param  Europe/Kiev
   * @return bool
   * @access Public
   **/
  public function timezoneSet($timezone = 'Europe/Moscow'){
    define('SYSTEM_TIME_ZONE', $timezone);
    return date_default_timezone_set($timezone);
  }

  /**
   * Контроль за отображением ошибок при старте
   *
   * @param bool $bool
   *
   * @return string
   */
  public function displayStartupErrors($bool = true){
    return ini_set('display_startup_errors', $bool);
  }

  /**
   * Установить обработчик исключений
   *
   * @param $object
   * @internal param string $methodName
   * @return string
   */
  public function  setExceptionHandler($object){
    return set_exception_handler(array($object, 'handleException'));
  }

  /**
   * Установить обработчик ошибок
   *
   * @return string
   */
  public function  setErrorHandler(){
    // error handler function

    return set_error_handler(array(&$this, 'myErrorHandler'));
  }

  public function myErrorHandler($errno, $errstr, $errfile, $errline){

    if(!class_exists('lib\Core\Log')){
      die('Error #'.$errno.' '.$errstr . ' in: '. $errfile .' line: ' . $errline);
    }

    switch($errno) {
      case E_USER_ERROR:
        Log::write('Fatal error #'.$errno.' '.$errstr, $errfile, $errline);
        exit(1);
        break;

      case E_USER_WARNING:
        Log::write('Warning #'.$errno.' '.$errstr, $errfile, $errline);
        break;

      case E_USER_NOTICE:
        Log::write('Notice #'.$errno.' '.$errstr, $errfile, $errline);
        break;

      default:
        Log::write('Unknown error #'.$errno.' '.$errstr, $errfile, $errline);
        break;
    }

    Debugger::errorCached($errno, $errstr, $errfile, $errline, $this);
    Debugger::information();

    /* Don't execute PHP internal error handler */
    return false;
  }

  /**
   * Контроль за отображением ошибок
   *
   * @param bool $bool
   *
   * @return string
   */
  public function displayErrors($bool = true){
    return ini_set('display_errors', $bool);
  }

  /**
   * Редирект
   *
   * @param string $url
   **/
  public function redirect($url = '/'){
    if(is_array($url)) {
      $url = UrlService::get()->toString($url);
    }

    header('location: ' . $url);
    exit();
  }

  /**
   * getIp
   *
   *
   * @return string
   */
  public function getIp(){
    if(!isset($_SERVER['REMOTE_ADDR'])) {
      return '0.0.0.0';
    }
    return $_SERVER['REMOTE_ADDR'];
  }

  /**
   * getL ongIp
   *
   *
   * @return int
   */
  public function getLongIp(){
    return ip2long($this->getIp());
  }

  /**
   * myGetDate
   *
   * @param $timestamp
   * @return array
   */
  public function myGetDate($timestamp){

    $GMdateParts = false;

    if(is_null($timestamp)) {
      $timestamp = time();
    }

    $dateParts = array('mday' => 'j', 'wday' => 'w', 'yday' => 'z', 'mon' => 'n', 'year' => 'Y', 'hours' => 'G', 'minutes' => 'i', 'seconds' => 's', 'weekday' => 'l', 'month' => 'F', 0 => 'U');

    while(list($part, $format) = each($dateParts)) {
      $GMdateParts[$part] = gmdate($format, $timestamp);
    }

    return $GMdateParts;
  }

  /**
   * @param boolean $on
   * @return void
   */
  public function xdebug($on){
    if(!$on) {
      return;
    }
    
    ini_set('xdebug.collect_params', '4');
    ini_set('xdebug.collect_vars', 'on');
    ini_set('xdebug.dump_globals', 'on');
    //ini_set('xdebug.show_local_vars', 'on');  -- may memory leak

  }

  public function setIncludePath($path) {
    ini_set('include_path', get_include_path() . PATH_SEPARATOR . $path);
  }

}