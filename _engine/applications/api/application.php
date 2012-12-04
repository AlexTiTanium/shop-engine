<?php

use lib\Core\Manager;
use lib\Session\drivers\SessionWithoutAuthorizationDriver;
use lib\Doctrine\DoctrineSessionStorage;
use lib\Session\drivers\SessionDoctrineDriver;
use lib\Core\Core;
use lib\Session\Session;
use lib\View\View;
use lib\Core\Config;
use lib\Core\ControllerRouter;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService;

Manager::$Define->setControllersPath(PATH_APPLICATIONS.CURRENT_APPLICATION.DS.'controllers'.DS);
Manager::$Define->skin('videolead');

IncluderService::connectApplicationFileSystem();

Manager::getAutoloader()
  ->addNamespace('controllers',  PATH_APPLICATIONS.CURRENT_APPLICATION.DS)
  ->addNamespace('site',         PATH_APPLICATIONS);

Session::open(new SessionWithoutAuthorizationDriver('api_session'));

TemplatesManager::addPath(IncluderService::$skin->getPath());

View::create('html','html');