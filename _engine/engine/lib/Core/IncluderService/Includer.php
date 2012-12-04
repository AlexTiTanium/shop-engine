<?php

namespace lib\Core\IncluderService;

use lib\Core\IncluderService;

class Includer {

  /**
   * временая переменая пути обращения к файлу
   *
   * @var string
   */
  private $path;

  /**
   * Папка в которой нужно искать каталоги и файлы
   *
   * @var string
   */
  private $basePath;

  /**
   * Конструктор
   *
   * @param $basePath
   */
  public function __construct($basePath){
    $this->basePath = $basePath;
  }

  /**
   * @param string $path
   * @return Includer
   */
  public function setPath($path){
    $clone = clone $this;
    $clone->path = $path.DS;
    return $clone;
  }

  /**
   * @return string
   */
  public function getPath(){
    return $this->basePath . $this->path;
  }

  /**
   * Проверить наличие файла
   *
   * @param $name
   * @param string $ext
   * @internal param string $extn
   * @example 'php','html','jsLink','cssLink','css','imageLink','image'...
   * @return boolean
   * @access Public
   */
  public function isExist($name, $ext = 'html'){

    $path = $this->basePath . $this->path . $name . '.' . $ext;

    if(file_exists($path)) {
      return true;
    }

    return false;
  }

  /**
   * Подключить php скрипт
   *
   * @param string $name - Имя скрипта без расширения
   * @param bool $once
   * @return void
   * @access Public
   */
  public function php($name, $once = false){
    $file = $this->basePath . $this->path . $name . '.php';

    if($once){
      IncluderService::includeOncePHP($file);
    }else{
      IncluderService::includePHP($file);
    }
  }

  /**
   * Прочитать Yaml файл в массив
   *
   * @param bool|String $name - Имя скрипта без расширения
   * @return String
   * @access Public
   */
  public function yaml($name){
    $file = $this->basePath . $this->path . $name . '.yml';
    return IncluderService::includeYaml($file);
  }

  /**
   * Подключить html скрипт
   *
   * @param string $name - Имя скрипта без расширения
   * @return string
   * @access Public
   */
  public function html($name){
    $file = $this->basePath . $this->path . $name . '.html';
    return IncluderService::includeHTML($file);
  }

  /**
   * Подключить php класс получить объект
   *
   * @param bool|String $name - Имя скрипта без расширения
   * @return String
   * @access Public
   */
  public function object($name){
    $file = $this->basePath . $this->path . $name . '.php';
    return IncluderService::getObject($file, $name);
  }

  /**
   * Подключить JS
   *
   * @param $path - Имя скрипта без расширения
   *
   * @return String
   * @access Public
   */
  public function js($path){

    if(!IncluderService::isExternalResource($path)){
      $path = $this->basePath . $this->path . $path . '.js';
      $path = IncluderService::prepareRelativePath($path);
    }

    return IncluderService::includeJS($path);
  }

  /**
   * Подключить CSS
   *
   * @param string $path - Имя скрипта без расширения
   * @return String
   * @access Public
   */
  public function css($path){

    if(!IncluderService::isExternalResource($path)){
      $path = $this->basePath . $this->path . $path . '.css';
      $path = IncluderService::prepareRelativePath($path);
    }

    return IncluderService::includeCSS($path);
  }

  /**
   * Создать тег изображения
   *
   * @param string $name - Имя скрипта без расширения
   * @return String
   * @access Public
   */
  public function image($name){
    $file = $this->basePath . $this->path . $name;
    return IncluderService::includeImage($file);
  }

}