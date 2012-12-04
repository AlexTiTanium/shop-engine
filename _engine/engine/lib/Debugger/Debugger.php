<?php

namespace lib\Debugger;

use FirePHPCore\FirePHP;
use ErrorException;
use lib\Doctrine\DoctrineSqlLogger;
use lib\Core\Manager;
use lib\Core\UrlService;

class Debugger {

  /**
   * @var FirePHP $FB
   */
  private static $fireBug;

  /**
   * @var array
   */
  private static $odmLog = array();

  /**
   * @var array
   */
  private static $callLog = array();

  /**
   * @var null|\lib\Doctrine\DoctrineSqlLogger
   */
  private static $ormLogger = null;

  /**
   * @var \Exception
   */
  private static $traceException = array();

  /**
   * Собирает классы в композицию
   *
   * @access Public
   **/
  public static function construct(){

    self::$fireBug = FirePHP::getInstance(true);

    $options = array('maxObjectDepth' => 10, 'maxArrayDepth' => 20, 'useNativeJsonEncode' => true, 'includeLineNumbers' => true);

    self::$fireBug->setOptions($options);

    self::$fireBug->registerErrorHandler(false);
  }

  /**
   * @static
   * @param \lib\Doctrine\DoctrineSqlLogger $ormLogger
   */
  public static function setOrmLogger(DoctrineSqlLogger $ormLogger){
    self::$ormLogger = $ormLogger;
  }

  /**
   * @static
   * @param array $log
   * @return mixed
   */
  public static function addOdmEvent(array $log){

    if(!self::getEnabled()) {
      return;
    }

    self::$odmLog[] = $log;
  }

  /**
   * @static
   *
   * @param $message
   * @param $object
   *
   * @return mixed
   */
  public static function addCall($message, $object){

    if(!self::getEnabled()) {
      return;
    }

    self::$callLog[] = array('message'=>$message, 'object'=>clone $object);
  }

  /**
   * @param \Exception $traceException
   */
  public static function setTraceException(\Exception $traceException)
  {
    self::$traceException[] = $traceException;
  }

  /**
   * @static
   * @return void
   */
  public static function odmLogPrint(){

    if(!self::getEnabled()) {
      return;
    }
    if(empty(self::$odmLog)) {
      return;
    }

    self::$fireBug->group('ODM Queries', array('Collapsed' => true, 'Color' => '#C000FF'));

    foreach(self::$odmLog as $value){
      self::info($value);
    }

    self::$fireBug->groupEnd();
  }

  /**
   * @static
   * @return void
   */
  public static function callLogPrint(){

    if(!self::getEnabled()) {
      return;
    }
    if(empty(self::$callLog)) {
      return;
    }

    self::$fireBug->group('Controllers call log', array('Collapsed' => true, 'Color' => '#E89D1B'));

    foreach(self::$callLog as $value){
      self::info($value['object'], $value['message']);
    }

    self::$fireBug->groupEnd();
  }

  /**
   * Вывести запросы
   *
   * @access Public
   **/
  public static function ormLogPrint(){

    if(!self::getEnabled()) {
      return;
    }

    if(self::$ormLogger == null) {
      return;
    }

    self::$fireBug->group('ORM Queries', array('Collapsed' => true, 'Color' => '#0000FF'));

    foreach(self::$ormLogger->getLogger() as $node) {

      self::$fireBug->group($node->query, array('Collapsed' => true, 'Color' => '#CCCCCC'));

      self::info($node->query,  'Query');
      self::info($node->params, 'Params');
      self::info($node->types,  'Types');
      self::info($node->time,   'Time');

      self::$fireBug->groupEnd();
    }

    Debugger::info(self::$ormLogger->getTotalDbTime(), 'Total time');

    self::$fireBug->groupEnd();
  }

  /**
   * Вывести информацию
   *
   * @access Public
   **/
  public static function information(){

    if(!self::getEnabled()) {
      return;
    }

    self::callLogPrint();
    self::odmLogPrint();
    self::ormLogPrint();

    self::$fireBug->group('Additional information', array('Collapsed' => true, 'Color' => '#FF00FF'));

    if(isset($_SESSION)) {
      Debugger::info($_SESSION, 'Session');
    }

    Debugger::info(Manager::$UrlService->getCurrentUrl(),    'URL');
    Debugger::info(Debugger::Time(),    'Time');
    Debugger::info(Debugger::Memory(),  'Memory');

    self::$fireBug->groupEnd();
  }

  /**
   * Добавить варинг о том что файл не найден
   *
   * @param string $path
   * @return bool
   * @access Public
   **/
  public static function fileNotFound($path){

    if(!self::getEnabled()) {
      return;
    }
    self::$fireBug->group('Some files was not found ', array('Collapsed' => true, 'Color' => '#000000'));

    Debugger::warn($path, 'File not found');
    self::$fireBug->groupEnd();
  }

  /**
   * Вывести информацию об исключении
   *
   * @access Public
   *
   * @param $data
   * @return bool
   */
  public static function exception($data){

    if(!self::getEnabled()) {
      return;
    }

    self::$fireBug->group('We have exception', array('Collapsed' => false, 'Color' => '#CC00FF'));

    foreach($data as $key => $value) {
      Debugger::warn($value, $key);
    }

    if(!empty(self::$traceException)){
      foreach(self::$traceException as $exception){
        self::$fireBug->fb($exception);
      }
    }

    self::$fireBug->groupEnd();
  }

  /**
   * @static
   *
   * @param $errno
   * @param $errstr
   * @param $errfile
   * @param $errline
   * @param $errcontext
   */
  public static function errorCached($errno, $errstr, $errfile, $errline, $errcontext){

    if(!self::getEnabled()) {
      return;
    }

    self::$fireBug->group('We have error', array('Collapsed' => false, 'Color' => '#b34646'));

    if(!empty(self::$traceException)){
      foreach(self::$traceException as $exception){
        self::$fireBug->fb($exception);
      }
    }

    $exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);
    self::$fireBug->fb($exception);

    self::$fireBug->groupEnd();
  }

  /**
   * Включить дебагер
   *
   * @access Public
   **/
  public static function enable(){
    self::$fireBug->setEnabled(true);
  }

  /**
   * Выключить дебагер
   *
   * @access Public
   **/
  public static function disable(){
    self::$fireBug->setEnabled(false);
  }

  /**
   * Debugger::Time() - Показать время выполнения
   *
   * @return  String
   */
  public static function time(){
    return Manager::$Timer->getTimerFormatted('total');
  }

  /**
   * Debugger::Memory() - Сколько памяти использованно
   *
   * @return String
   */
  public static function memory(){
    return number_format(((memory_get_peak_usage() / 1024) / 1024), 2, ',', ' ') . ' MB';
  }

  /**
   * Check if logging is enabled
   *
   * @return boolean TRUE if enabled
   */
  public static function getEnabled(){
    return self::$fireBug->getEnabled();
  }

  /**
   * Enable and disable logging to Firebug
   *
   * @param bool $bool
   * @internal param bool $Enabled TRUE to enable, FALSE to disable
   * @return void
   */
  public static function setEnabled($bool = false){
    self::$fireBug->setEnabled($bool);
  }

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::INFO
   * @param mixed $Object
   * @param string $Label
   * @param array $Options
   * @return void
   */
  public static function info($Object, $Label = null, $Options = array()){
    self::$fireBug->info($Object, $Label, $Options);
  }

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::WARN
   * @param mixed $Object
   * @param string $Label
   * @param array $Options
   * @return void
   */
  public static function warn($Object, $Label = null, $Options = array()){
    self::$fireBug->warn($Object, $Label, $Options);
  }

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::ERROR
   * @param mixed $Object
   * @param string $Label
   * @param array $Options
   * @return void
   */
  public static function error($Object, $Label = null, $Options = array()){
    self::$fireBug->error($Object, $Label, $Options);
  }

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::LOG
   * @param mixed $Object
   * @param string $Label
   * @param array $Options
   * @return void
   */
  public static function log($Object, $Label = null, $Options = array()){
    self::$fireBug->log($Object, $Label, $Options);
  }

  /**
   * Log a trace in the firebug console
   *
   * @see FirePHP::TRACE
   * @param string $Label
   * @return void
   */
  public static function trace($Label = null){
    self::$fireBug->trace($Label);
  }

} Debugger::construct();