<?php
/**
 * TiT Framework
 * User: Alexander
 * Date: 05.04.11
 * Time: 14:33
 *
 * Class create and register autoLoader, may accept prefix and namespace based classes names.
 * Usage:
 * $autoLoader = new AutoLoader();
 * $autoLoader->addNamespace('Tests',  '/Engine/Test/');
 * $autoLoader->addNamespace('Engine', array(/Engine/, /home/user/));
 *
 * $autoLoader->addPrefix('Doctrine', array('/Engine/Lib/Doctrine/', '/Engine/Models/') );
 * $autoLoader->addPrefix('Swift', '/Engine/Lib/Doctrine/');
 *
 * @use \Exception
 * @throw \AutoLoadException
 */

namespace lib\Core;

use Exception;

class AutoloaderException extends Exception {
}

class Autoloader {

  /**
   * @var array
   */
  private $namespaces = array();

  /**
   * @var array
   */
  private $prefixes = array();

  /**
   * @var boolean
   */
  private $throwExceptions = false;

  /**
   * @var string
   */
  private $lastPath = '';


  /**
   * @param boolean $throw - This parameter specifies should autoLoader throw exceptions on error.
   * @param boolean $prepend - If true, loader will prepend the autoLoader on the autoLoad stack instead of appending it.
   */
  public function __construct($throw = true, $prepend = false){
    $this->throwExceptions = $throw;
    $this->registerAutoLoader($throw, $prepend);
  }

  /**
   * There we are register spl autoLoader.
   *
   * @throw \AutoLoadException
   *
   * @param boolean $throw - This parameter specifies should autoLoader throw exceptions on error.
   * @param boolean $prepend - If true, loader will prepend the autoLoader on the autoLoad stack instead of appending it.
   *
   * @throws AutoloaderException
   * @return void
   */
  private function registerAutoLoader($throw, $prepend){
    if(spl_autoload_register(array($this, 'load'), $throw, $prepend) === false) {
      throw new AutoloaderException('Could not register spl loader');
    }
  }

  /**
   * Un register this autoLoader
   *
   * @throws AutoloaderException
   * @return void
   */
  public function unRegisterAutoLoader(){
    if(spl_autoload_unregister(array($this, 'load')) === false) {
      throw new AutoloaderException('Could not un register spl loader');
    }
  }

  /**
   * If we wont load classes by prefix. For example we need load Doctrine_Record,
   * Doctrine it is prefix, we call addPrefix('Doctrine', '/libs/Doctrine/') or
   * addPrefix('Doctrine', array('/libs/Doctrine/', '/libs/Doctrine2/'))
   *
   * @param string $prefix
   * @param $dirs
   *
   * @return \lib\Core\Autoloader
   */
  public function addPrefix($prefix, $dirs){

    if(!isset($this->prefixes[$prefix])) {
      $this->prefixes[$prefix] = array();
    }

    if(is_array($dirs)) {
      $this->prefixes[$prefix] = array_merge($this->prefixes[$prefix], $dirs);
      return $this;
    }

    array_push($this->prefixes[$prefix], $dirs);

    return $this;
  }

  /**
   * If we wont load classes by namespace. For example we need load Libs/Doctrine/Record,
   * by 'using Libs\Doctrine\Record' and Record in the '/home/Libs/Doctrine/'
   * Doctrine it is prefix, we call addPrefix('Doctrine', '/home/') or
   * addPrefix('Doctrine', array('/home2/', '/home/'))
   *
   * @param string $namespace
   * @param array|string $dirs
   *
   * @return \lib\Core\Autoloader
   */
  public function addNamespace($namespace, $dirs){

    if(!isset($this->namespaces[$namespace])) {
      $this->namespaces[$namespace] = array();
    }

    if(is_array($dirs)) {
      $this->namespaces[$namespace] = array_merge($this->namespaces[$namespace], $dirs);
      return $this;
    }

    array_push($this->namespaces[$namespace], $dirs);

    return $this;
  }

  /**
   * Method for loading class
   *
   * @param  string $class
   *
   * @return mixed|null
   * @throws AutoloaderException
   */
  private function load($class){

    if($path = $this->getFullPath($class)) {
      return require_once $path;
    }

    if($this->throwExceptions) {
      throw new AutoloaderException('Loader not found class: ' . $class.' in path('.$this->lastPath.')');
    }

    return null;
  }

  /**
   *  Determine prefix or namespace based path. Returns full path to the class
   *
   * @param string $class
   * @return string - path to the file
   */
  private function getFullPath($class){

    if('\\' == $class[0]) {
      $class = substr($class, 1);
    }

    $position = strrpos($class, '\\');
    if(false !== $position) {
      return $this->getByNamespace($class, $position);
    }

    return $this->getByPrefix($class);
  }

  /**
   * If class based on namespace, determine file path.
   *
   * @param string $class
   * @param  int $position
   *
   * @return string|bool
   */
  private function getByNamespace($class, $position){

    $namespace = substr($class, 0, $position);
    foreach($this->namespaces as $currentNamespace => $dirs) {
      foreach($dirs as $dir) {
        if(0 !== strpos($namespace, $currentNamespace)) {
          continue;
        }
        $className = substr($class, $position + 1);

        $file = $dir . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $this->lastPath = $file;

        //echo 'Namespace: '.$file."\n<br />";
        if(file_exists($file)) {
          return $file;
        }
      }
    }

    return false;
  }

  /**
   * If class based on prefix, determine file path.
   *
   * @param  string $class
   *
   * @return bool|string
   */
  private function getByPrefix($class){

    foreach($this->prefixes as $prefix => $dirs) {
      foreach($dirs as $dir) {

        if(0 !== strpos($class, $prefix)) {
          continue;
        }

        $file = $dir . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        //echo 'Prefix: ' . $file . "\n";
        $this->lastPath = $file;
        if(file_exists($file)) {
          return $file;
        }
      }
    }

    return false;
  }
}