<?php

namespace lib\Core;

use lib\Core\Autoloader;
use lib\Core\Storage\LocalGridStorage;
use lib\EngineExceptions\ExceptionHandler;
use lib\Core\Manager\ManagerCache;
use lib\Core\Manager\ManagerCrypt;
use lib\Core\Manager\ManagerToken;
use lib\Core\Manager\ManagerPhp;
use lib\Core\Manager\ManagerTimers;
use lib\Core\Manager\ManagerDefines;
use lib\Core\Manager\ManagerHeaders;
use lib\Core\Manager\ManagerCommonOperations;
use lib\Core\UrlService;

class Manager {

  /**
   * Операционая система
   * @var boolean
   */
  public static $IsWindows;

  /**
   * Диспечер таймеров
   * @var ManagerTimers
   */
  public static $Timer;

  /**
   * Class common operations
   * @var ManagerCommonOperations
   */
  public static $Common;

  /**
   * Управления заголовками
   * @var ManagerHeaders
   */
  public static $Headers;

  /**
   * Блоки Define
   * @var ManagerDefines
   */
  public static $Define;

  /**
   * Упрвление интерпиритатором
   * @var ManagerPhp
   */
  public static $Php;

  /**
   * Управление Автозагрузчиком классов
   * @var Autoloader
   */
  public static $Autoloader;

  /**
   * Управление приложениями
   * @var ManagerToken
   */
  public static $Token;

  /**
   * Service for crypting data
   * @var ManagerCrypt
   */
  public static $Crypt;

  /**
   * @var ManagerCache
   */
  public static $Cache;

  /**
   * @var UrlService
   */
  public static $UrlService;

  /**
   * @var Storage
   */
  public static $Storage;


  /**
   * Собирает классы в композицию
   *
   * @access Public
   **/
  public static function construct(){

    # Определить под какой ОС работает сервер
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      self::$IsWindows = true;
    } else {
      self::$IsWindows = false;
    }

    # Управление Автозагрузчиком классов
    require_once(PATH_MANAGER . DS . 'Autoloader.php');
    self::$Autoloader = new Autoloader();


    self::setEnvironment();
  }

  public static function setEnvironment(){

    self::getAutoloader()
      ->addNamespace('lib',     PATH_SYSTEM . FOLDER_ENGINE . DS);

    self::initSysComponents();

    self::$Define->systemPath(PATH_SYSTEM);
    self::$Define->systemPublicPath(PATH);
    self::$Define->systemCode(Config::loadSystem('system')->value('systemCode'));

    self::getAutoloader()
      ->addNamespace('FirePHPCore', PATH_VENDOR)
      ->addNamespace('Doctrine',    PATH_VENDOR)
      ->addNamespace('Pagerfanta',  PATH_VENDOR)
      ->addPrefix('Twig',           PATH_VENDOR)
      ->addNamespace('CryptLib',    PATH_VENDOR)
      ->addNamespace('Upload',      PATH_VENDOR)
      ->addPrefix('Swift',          PATH_VENDOR . DS . 'Swift' . DS . 'classes' . DS)
      ->addNamespace('Symfony',     PATH_VENDOR . DS . 'Doctrine' . DS)
      ->addNamespace('Documents',   PATH_MODELS_ODM)
      //->addNamespace('Entities',    PATH_MODELS_ORM)
      ->addNamespace('models',      PATH_SYSTEM);

    self::$Php->setIncludePath(PATH_VENDOR);

    self::$Php->setExceptionHandler(new ExceptionHandler());
    self::$Php->setErrorHandler();
    self::$Php->timezoneSet(Config::loadSystem('system')->value('timeZone'));

    self::$Headers->setUnicode();
    self::$Headers->noCache();

    self::$Php->setMode(DEBUG_MODE);
  }

  /**
   * @static
   * @return Autoloader
   */
  public static function getAutoloader(){
    return self::$Autoloader;
  }

  /**
   *
   */
  public static function initSysComponents(){

    self::$Php = new ManagerPhp();
    self::$Common = new ManagerCommonOperations();
    self::$Headers = new ManagerHeaders();
    self::$Define = new ManagerDefines();
    self::$Timer = new ManagerTimers();
    self::$Token = new ManagerToken();
    self::$Crypt = new ManagerCrypt();
    self::$Cache = new ManagerCache();
    self::$UrlService = new UrlService(new RouterService());
    self::$Storage = new Storage(new LocalGridStorage());

    self::$Timer->start('total');
  }

}

Manager::construct();