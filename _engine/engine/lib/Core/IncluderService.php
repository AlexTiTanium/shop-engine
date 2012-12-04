<?php

namespace lib\Core;

use lib\Core\Manager;
use lib\Core\IncluderService\Includer;
use SimpleXMLElement;
use lib\Yaml\Yaml;
use lib\Debugger\Debugger;
use lib\EngineExceptions\SystemException;

class IncluderService {

  /**
   * @var string
   */
  const ROOT_PATH = '';

  /**
   * Класс вызова файлов из папки скинов
   * @var Includer $Skin
   */
  public static $skin;

  /**
   * Пути к контроллерам
   * @var Includer $controllers
   */
  public static $controllers;

  /**
   * Paths to system configs
   * @var Includer $controllers
   */
  public static $systemConfigs;


  public static function connectEngineFileSystem(){

    if(defined('PATH_CONFIG')) {
      self::$systemConfigs = new Includer(PATH_CONFIG);
    }

  }

  /**
   * Подключить фаловую систему приложения, вызывать после обявления констант
   * PATH_TO_APP_CONTROLLERS, PATH_PLUGINS, PATH_SKIN
   *
   * @access public
   * @throws SystemException
   */
  public static function connectApplicationFileSystem(){

    if(defined('PATH_TO_APP_CONTROLLERS')) {
      self::$controllers = new Includer(PATH_TO_APP_CONTROLLERS);
    } else {
      throw new SystemException('Не задан путь к контроллерам приложения');
    }

    if(defined('PATH_SKIN')) {
      self::$skin = new Includer(PATH_SKIN);
    }

  }

  /**
   * @param string $src
   *
   * @return bool
   */
  public static function isExternalResource($src){

    $http = substr($src, 0, 7);
    $https = substr($src, 0, 8);

    if($http == 'http://' || $https == 'https://'){
      return true;
    }

    return false;
  }

  /**
   * @param $path
   * @return string
   */
  public static function prepareRelativePath($path){

    self::isFileExist($path);

    $path = str_replace(PATH, self::ROOT_PATH, $path);
    if(Manager::$IsWindows) {
      $path = str_replace('\\', '/', $path);
    }

    return $path;
  }

  /**
   * Запустить приложение
   *
   * @param $name
   * @throws SystemException
   * @internal param String $path - путь к файлу
   * @return bool
   */
  public static function requireApplication($name){

    if(!self::isFileExist(PATH_APPLICATIONS . $name . DS . 'application.php')) {
      throw new SystemException('Не удалось запустить приложение, не найден файл запуска');
    }

    self::includeOncePHP(PATH_APPLICATIONS . $name . DS . 'application.php');
  }

  /**
   * Проверить наличие файла
   *
   * @param  String $path - путь к файлу
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  public static function isFileExist($path){

    if(file_exists($path)) {
      return true;
    }

    Debugger::fileNotFound($path);
    throw new SystemException('File not found: '.$path);
  }

  /**
   * Подключаем  результат выполнения php скрипта
   *
   * @param  String $path - путь к файлу
   * @return String
   **/
  public static function getPHPExecutionResult($path){
    ob_start();
    include($path);
    return ob_get_clean();
  }

  /**
   * Подключаем скрипт php
   *
   * @param  String $path - путь к файлу
   * @access Protected
   * @return bool
   **/
  public static function includePHP($path){
    self::isFileExist($path);
    include($path);
  }

  /**
   * Подключаем скрипт php один раз
   *
   * @param  String $path - путь к файлу
   * @access Protected
   * @return bool
   **/
  public static function includeOncePHP($path){
    self::isFileExist($path);
    include_once($path);
  }

  /**
   * Подключаем html
   *
   * @param  String $path - путь к файлу
   * @access Protected
   * @return String
   **/
  public static function includeHTML($path){
    self::isFileExist($path);
    return file_get_contents($path);
  }

  /**
   * Подключаем xml
   *
   * @param  String $path - путь к файлу
   * @access Protected
   * @return SimpleXMLElement
   */
  public static function includeXML($path){
    self::isFileExist($path);
    return new SimpleXMLElement(file_get_contents($path));
  }

  /**
   * Получаем объекты
   *
   * @param $path
   * @param String $nameClass
   * @return string
   */
  public static function getObject($path, $nameClass){

    self::isFileExist($path);

    include_once($path);

    return $nameClass;
  }

  /**
   * Подключаем скрипты JS через ссылку
   *
   * @param  String $path - путь к файлу
   * @param bool|String $extraCase - дополнительное условие  <!--['.$extraCase.']>...<![endif]-->
   * @return String
   */
  public static function includeJS($path, $extraCase = false){

    if($extraCase) {
      return '<!--[' . $extraCase . ']><script type="text/javascript" language="javascript" src="' . $path . '"></script><![endif]-->';
    }

    return '<script type="text/javascript" language="javascript" src="' . $path . '"></script>';
  }

  /**
   * Подключаем скрипты CSS через ссылку
   *
   * @param  String $path - путь к файлу
   * @return String
   **/
  public static function includeCSS($path){

    return '<link rel="stylesheet" href="' . $path . '" type="text/css" />';
  }

  /**
   * Создать тег изображения
   *
   * @param  String $path - путь к файлу
   * @param string $alt
   * @return String
   */
  public static function includeImage($path, $alt=''){
    $path = self::prepareRelativePath($path);
    return '<img alt="'.$alt.'" src="' . $path . '" />';
  }

  /**
   * Распарсить Yaml файл, вернуть массив
   *
   * @param  String $path - путь к файлу
   * @return String
   **/
  public static function includeYaml($path){
    self::isFileExist($path);
    return Yaml::load($path);
  }

}