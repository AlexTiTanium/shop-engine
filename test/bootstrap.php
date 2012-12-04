<?php

use lib\Core\Manager;

define('FOLDER_ENGINE',   'engine');
define('FOLDER_LIB',      'lib');

define('PATH_SYSTEM',     PATH . DS . '..' . DS . '_engine' . DS);
define('PATH_MANAGER',    PATH_SYSTEM . FOLDER_ENGINE . DS . FOLDER_LIB . DS . 'Core' . DS);

require_once(PATH_MANAGER . 'Manager.php');

Manager::getAutoloader()
  ->addNamespace('lib',     PATH_SYSTEM . FOLDER_ENGINE . DS);

Manager::initSysComponents();

Manager::$Define->systemPath(PATH_SYSTEM);