<?php

namespace lib\Core\Manager;

use lib\Core\IncluderService;

class ManagerDefines {

  /**
   * Определить пути для системы
   *
   * @param  $PATH - Откуда начинать считать
   * @access Public
   **/
  public function systemPath($PATH){

    // Основные пути системы
    $ENGINE = FOLDER_ENGINE;
    $LIB = FOLDER_LIB;
    $VENDOR = 'vendor';
    $CACHE_COOKIE = 'cookie';
    $MODELS = 'models';
    $APPLICATIONS = 'applications';
    $APPLICATIONS_LIB = 'lib';
    $CONFIG = 'config';
    $CACHE = 'cache';
    $CACHE_TWIG = 'twig';
    $LOG_FOLDER = 'logs';
    $CACHE_SWIFT = 'swift';
    $UPLOAD_DIR = 'upload';
    $ODM_DIR = 'ODM';
    $ORM_DIR = 'ORM';
    $PROXIES_DIR = 'Proxies';
    $HYDRATORS_DIR = 'Hydrators';

    // Определить путь Движка
    define('PATH_ENGINE', $PATH  . $ENGINE . DS);

    // Определить путь к общим библиотекам
    define('PATH_LIB', $PATH  . $ENGINE . DS . $LIB . DS);

    // Определить путь к общим стороним библиотекам
    define('PATH_VENDOR', $PATH  . $ENGINE . DS . $VENDOR . DS);

    // Определить путь к приложениям системы
    define('PATH_APPLICATIONS', $PATH  . $APPLICATIONS . DS);

    define('PATH_APPLICATIONS_LIB', $PATH  . $APPLICATIONS_LIB . DS);

    // Определить путь к конфигам
    define('PATH_CONFIG', PATH_ENGINE . $CONFIG . DS);

    // Определить путь для паки c моделями
    define('PATH_MODELS', $PATH . $MODELS . DS);

    define('PATH_MODELS_ODM', PATH_MODELS . $ODM_DIR . DS);
    define('PATH_MODELS_ORM', PATH_MODELS . $ORM_DIR . DS);

    define('PATH_ODM_PROXIES',      PATH_MODELS_ODM . $PROXIES_DIR . DS);
    define('PATH_ODM_HYDRATORS',    PATH_MODELS_ODM . $HYDRATORS_DIR . DS);
    define('PATH_ODM_REPOSITORIES', $PATH);
    define('PATH_ODM_YAML_SCHEME',  PATH_MODELS_ODM . 'yaml' . DS);

    // Путь к кешу
    define('PATH_CACHE', $PATH . $CACHE . DS);

    // Путь к кешу скомпелированых Twig шаблонов
    define('PATH_CACHE_TWIG', PATH_CACHE . $CACHE_TWIG);

    // Путь к кешу swift
    define('PATH_CACHE_SWIFT', PATH_CACHE . $CACHE_SWIFT);

    // Путь к кешу swift
    define('PATH_CACHE_COOKIE', PATH_CACHE . $CACHE_COOKIE);

    //Путь к логу системы
    define('PATH_LOG', PATH_ENGINE . $LOG_FOLDER);

    // Путь к закрузок
    define('UPLOAD_DIR', $PATH . $UPLOAD_DIR);

    IncluderService::connectEngineFileSystem();
  }

  /**
   * Определить пути для публичных фалов системы(css,js, etc.)
   *
   * @param  $PATH - Откуда начинать считать
   * @access Public
   **/
  public function systemPublicPath($PATH){

    # Основные пути системы
    $TEMPLATES = 'skins';
    $FILES_STORE = 'filesStore';

    # Определить путь для публичной части системы
    define('PATH_PUBLIC_SYSTEM', $PATH);

    # Определить путь для паки c скинами
    define('PATH_TEMPLATES', PATH_PUBLIC_SYSTEM . DS . $TEMPLATES . DS);

    # Путь к files store
    define('PATH_PUBLIC_FILES_STORE', PATH_PUBLIC_SYSTEM . DS . $FILES_STORE);

  }

  /**
   * Определить пути для системы
   *
   * @param  $skinName - имя папки со скинами
   * @access Public
   **/
  public function skin($skinName){

    $TEMPLATES = 'templates';
    $FIELDS = 'fields';

    # Определить путь текущего скина
    define('SKIN_NAME', $skinName);

    # Определить путь текущего скина
    define('PATH_SKIN', PATH_TEMPLATES . $skinName . DS);

    # Определить путь к дополнительным шаблонам
    define('PATH_SKIN_TEMPLATES', PATH_SKIN . $TEMPLATES . DS);

    # Определить путь к шаблонам полей форм
    define('PATH_SKIN_FIELDS', PATH_SKIN . $FIELDS . DS);
  }

  /**
   * Переменая для безопасности выполнения скриптов
   *
   * @param  $code - любая последовательность символов
   * @access Public
   **/
  public function systemCode($code){
    define('SYSTEM_CODE', $code);
  }

  /**
   * Установить путь к контролерам приложения
   *
   * @param  $path
   * @access Public
   **/
  public function setControllersPath($path){
    define('PATH_TO_APP_CONTROLLERS', $path);
  }

}