<?php

use lib\Core\Manager;
use lib\Core\Config;
use lib\Debugger\Debugger;
use lib\View\View;
use lib\Core\Core;
use lib\Core\UrlService;
use lib\Doctrine\DoctrineOdm;

define('DS', DIRECTORY_SEPARATOR);

define('PATH', dirname(__FILE__));

define('FOLDER_ENGINE',   'engine');
define('FOLDER_LIB',      'lib');

define('PATH_SYSTEM',     PATH . DS . '..' . DS . '_engine' . DS);
define('PATH_MANAGER',    PATH_SYSTEM . FOLDER_ENGINE . DS . FOLDER_LIB . DS . 'Core' . DS);

define('DEBUG_MODE', true);

error_reporting(-1);
ini_set('display_errors', true);

require_once(PATH_MANAGER . 'Manager.php');

Manager::$UrlService->mapQuery($_SERVER['REQUEST_URI'], Config::loadSystem('routing')->get());

//DoctrineOrm::setConnection();
DoctrineOdm::setConnection(Config::loadSystem('mongoDb')->get('Connection')->value('default'));

Core::call(Manager::$UrlService->getCurrentUrl());

echo View::getCurrent()->toString();

Debugger::information();
