<?php

namespace lib\Templates;

/**
 * Prepare and load templates, connect filesystem
 *
 * @throws SystemException
 * @uses Template, Twig_Extension_Core, MyTwigCoreExtension, Project_Twig_Extension, Pages_Twig_Extension, Twig_Loader_Filesystem, Twig_Loader_String
 */

use Twig_Loader_String;
use Twig_Extension_Debug;
use lib\View\View;
use Twig_Loader_Filesystem;
use Twig_Extension_Optimizer;
use Twig_Extension_Escaper;
use Twig_Extension_Core;
use Twig_Environment;
use Twig_Autoloader;

use lib\EngineExceptions\SystemException;

class TemplatesManager {

  /**
   * Paths to templates
   *
   * @var array
   * @static
   * @access private
   */
  static public $pathToTpl = array();

  /**
   * Default template extension
   *
   * @var string
   * @static
   * @access private
   */
  static private $tplExtension = 'html';

  /**
   * Manager of templates, contains Template instances
   *
   * @var array
   * @static
   * @access private
   */
  static private $templates = array();

  /**
   * Twig files system Environment instance
   *
   * @static
   * @var Twig_Environment
   * @access private
   */
  static private $fileSystemEnvironment;

  /**
   * Twig string Environment instance
   *
   * @static
   * @var Twig_Environment
   * @access private
   */
  static private $stringEnvironment;

  /**
   * Environment options
   *
   * @var array
   */
  static public $options = array(
    // When set to true, the generated templates have a __toString() method
    // that you can use to display the generated nodes (default to false).
    'debug' => DEBUG_MODE,
    // An absolute path where to store the compiled templates,
    // or false to disable caching (which is the default).
    'cache' => PATH_CACHE_TWIG,
    // When developing with Twig, it's useful to recompile the template whenever
    // the source code changes. If you don't provide a value for the auto_reload option,
    // it will be determined automatically based on the debug value.
    'auto_reload' => DEBUG_MODE,
    // If set to true, auto-escaping will be enabled by default for all templates (default to true).
    // As of Twig 1.8, you can set the escaping strategy to use (html, js, false to disable, or a PHP callback that takes the template "filename" and must return the escaping strategy to use).
    'autoescape' => true,
    // A flag that indicates which optimizations to apply (default to -1 -- all optimizations are enabled; set it to 0 to disable).
    'optimizations' => -1, // 0 disable all
    // If set to false, Twig will silently ignore invalid variables (variables and or attributes/methods that do not exist) and replace them with a null value.
    // When set to true, Twig throws an exception instead (default to false).
    'strict_variables' => false
  );

  /**
   * Конструктор
   */
  static public function construct(){

    $environment = new Twig_Environment(null, self::$options);

    $environment->addExtension(new Twig_Extension_Core());
    $environment->addExtension(new Twig_Extension_Escaper(self::$options['autoescape']));
    $environment->addExtension(new Twig_Extension_Optimizer());

    if(DEBUG_MODE){
      $environment->addExtension(new Twig_Extension_Debug());
    }

    $environment->addExtension(new \lib\Templates\EngineExtensions\DateHelper());
    $environment->addExtension(new \lib\Templates\EngineExtensions\UrlHelper());
    $environment->addExtension(new \lib\Templates\EngineExtensions\ViewHelper());

    $environment->getExtension('core')->setTimezone(SYSTEM_TIME_ZONE);

    self::$fileSystemEnvironment = clone $environment;
    self::$stringEnvironment = clone $environment;

    unset($environment);

  }

  /**
   * Получить объект
   *
   * @param string $name
   * @throws \lib\EngineExceptions\SystemException
   * @return Template
   */
  static public function get($name){
    if(isset(self::$templates[$name])) {
      return self::$templates[$name];
    }

    throw new SystemException('Не найден объект шаблона');
  }

  /**
   * Проверить наличие шаблона
   *
   * @param string $name
   * @return boolean
   */
  static public function isIsset($name){
    if(isset(self::$templates[$name])) {
      return true;
    }
    return false;
  }

  /**
   * Загрузить шаблон или шаблн из памяти(Если $content != false )
   *
   * @param string $name
   * @param bool|string $content
   *
   * @return Template
   */
  static public function load($name, $content = false){

    if(!$content) {

      self::$fileSystemEnvironment->setLoader(new Twig_Loader_Filesystem(array_reverse(self::$pathToTpl)));
      return self::$templates[$name] = new Template(self::$fileSystemEnvironment->loadTemplate($name . '.' . self::$tplExtension));

    } else {

      self::$stringEnvironment->setLoader(new Twig_Loader_String());
      return self::$templates[$name] = new Template(self::$stringEnvironment->loadTemplate($content));

    }
  }

  /**
   * @return string
   */
  public static function getTplExtension(){
    return self::$tplExtension;
  }

  /**
   * Удалить шаблон
   *
   * @param string $name
   */
  static public function delete($name){
    if(self::isIsset($name)) {
      unset(self::$templates[$name]);
    }
  }

  /**
   * Установить путь к шаблонам
   *
   * @param string $path
   */
  static public function addPath($path){
    self::$pathToTpl[] = $path;
  }

}

TemplatesManager::construct();