<?php

namespace lib\Profiler;


use lib\Debugger\Debugger;

class Profiler {

  const fileName = 'profiler.txt';
  public static $allowLog = true;
  public static $maxLogFileSize = 2;
  public static $text = '';

  /**
   * @param $isPreExecute
   * @param $query
   * @param $params
   * @internal param string $message
   * @return bool
   */
  public static function save($isPreExecute,  $query, $params){
    
    if(!self::$allowLog) {
      return false;
    }

    self::addField('Date', date('d-m-Y H:i:s'));
    self::addField('Url query', QUERY);
    self::addField('Mysql query', $query);
    self::addArray('Query param', $params);

    if(isset($_GET)) {
      self::addArray('GET', $_GET);
    }

    if(isset($_POST)) {
      self::addArray('POST', $_POST);
    }

    if(isset($_SESSION)) {
      self::addArray('SESSION', $_SESSION);
    }

    self::addField('Time', Debugger::Time());

    $memoryUsage = number_format(((memory_get_peak_usage(true)/1024)/1024), 2, ',', ' ');

    if($memoryUsage > 20){
      self::addField('Memory', '<b><font color="red">'.$memoryUsage.' MB</font></b>');
    }else{
      self::addField('Memory', $memoryUsage.' MB');
    }

    self::addArray('Trace', debug_backtrace());
    
    self::$text .= '<hr>' . "\n";

    $file = PATH_LOG . DS . self::fileName.'_'.md5($query.print_r($params, 1)).'.html';

    if($isPreExecute){
      $fp = fopen($file, 'a');
      flock($fp, LOCK_EX);

      fwrite($fp, self::$text);

      flock($fp, LOCK_UN);
      fclose($fp);
    }else{
      if(file_exists($file)){
        unlink($file);
      }
    }
    
    self::$text = '';

    return true;
  }

  /**
   * Log::addField()
   *
   * @param string $fieldName
   * @param string $field
   *
   * @return void
   */
  private static function addField($fieldName, $field){
    self::$text .= '<b>' . $fieldName . ': </b>' . str_replace("\n", '<br>', $field) . ' <br> ';
  }

  /**
   * Log::addArray()
   *
   * @param string $fieldName
   * @param array $fieldArray
   *
   * @return void
   */
  private static function addArray($fieldName, $fieldArray){
    if(empty($fieldArray)) {
      return;
    }
    self::$text .= '<b>' . $fieldName . ': </b> <br>' . str_replace("\n", '<br>', self::arrayRec($fieldArray)) . ' <br> ';
  }

  /**
   * Log::arrayRec()
   *
   * @param array $array
   *
   * @param int $level
   * @return string
   */
  private static function arrayRec($array, $level = 1){

    if(empty($array)) {
      return '';
    }

    $text = '';
    $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
    $nbsp = '';

    for($i = 0; $i < $level; $i++) {
      $nbsp .= $spacer;
    }

    foreach($array as $key => $value) {
      if(is_array($value)) {
        $text .= $nbsp . '[' . $key . ']=><b>array(</b><br>' . self::arrayRec($value, $level + 1) . '' . $nbsp . '<b>)</b><br>';
        continue;
      }

      if(is_object($value)){ $value = get_class($value); }

      $text .= $nbsp . '[' . $key . ']=>' . $value . '<br>';
    }

    return $text;
  }

  /**
   * Log::read()
   *
   * @return array
   */
  public static function read(){
    if(!file_exists(PATH_LOG . DS . self::fileName)) {
      return array();
    }
    return array_reverse(file(PATH_LOG . DS . self::fileName));
  }

  /**
   * Log::truncate()
   *
   * @return bool
   */
  public static function truncate(){
    if(!file_exists(PATH_LOG . DS . self::fileName)) {
      return true;
    }
    return unlink(PATH_LOG . DS . self::fileName);
  }

}