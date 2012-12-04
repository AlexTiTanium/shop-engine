<?php

namespace lib\Core;

use lib\Core\Manager;
use lib\Debugger\Debugger;

class Log {

  const fileName = 'log.txt';
  public static $allowLog = true;
  public static $maxLogFileSize = 1; //Mb

  private static $text = '';

  /**
   * Log::write()
   *
   * @param string $content
   * @param bool $file
   * @param bool $line
   *
   * @return bool
   */
  public static function write($content, $file = false, $line = false){
    if(!self::$allowLog) {
      return false;
    }
    self::$text = '<hr>';

    self::addField('Date', date('d-m-Y H:i:s'));
    self::addField('Query', Manager::$UrlService->getCurrentQuery());

    self::addField('Message', $content);
    if($file) {
      self::addField('File', $file);
    }
    if($line) {
      self::addField('Line', $line);
    }
    self::addArray('GET', $_GET);
    self::addArray('POST', $_POST);

    if(isset($_SESSION)) {
      self::addArray('SESSION', $_SESSION);
    }

    self::addField('IP', Manager::$Php->getIp());
    self::addField('Time', Manager::$Timer->getTimerFormatted('total'));
    self::addField('Memory', number_format(((memory_get_peak_usage() / 1024) / 1024), 2, ',', ' ') . ' MB');

    self::$text .= '<hr>' . "\n";

    $fp = fopen(PATH_LOG . DS . self::fileName, 'a');
    flock($fp, LOCK_EX);

    fwrite($fp, self::$text);

    flock($fp, LOCK_UN);
    fclose($fp);

    self::$text = '';

    if(filesize(PATH_LOG . DS . self::fileName) > ((self::$maxLogFileSize * 1024) * 1024)) {
      self::truncate();
    }

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
   * @param int $level
   *
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
      if(is_array($value) or is_object($value)) {
        $text .= $nbsp . '[' . $key . ']=><b>array(</b><br>' . self::arrayRec($value, $level + 1) . '' . $nbsp . '<b>)</b><br>';
        continue;
      }
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