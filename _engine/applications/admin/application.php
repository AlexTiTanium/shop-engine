<?php

use lib\Core\Manager;
use lib\Doctrine\DoctrineSessionStorage;
use lib\Session\drivers\SessionDoctrineDriver;
use lib\Session\Session;
use lib\View\View;
use lib\Core\Config;
use lib\Core\Core;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService;

Manager::$Define->skin('admin');
Manager::$Define->setControllersPath(PATH_APPLICATIONS.CURRENT_APPLICATION.DS.'controllers'.DS);

IncluderService::connectApplicationFileSystem();

Manager::getAutoloader()
  ->addNamespace('controllers',   PATH_APPLICATIONS.CURRENT_APPLICATION.DS)
  ->addNamespace('admin',         PATH_APPLICATIONS);

TemplatesManager::addPath(IncluderService::$skin->getPath());
TemplatesManager::addPath(IncluderService::$skin->setPath('templates')->getPath());

Session::open(new SessionDoctrineDriver('admin_session',
  new DoctrineSessionStorage('Documents\Admin', 'Documents\Admins\SessionStorage')
));

View::setConstant('IMAGES',             '/skins/'.SKIN_NAME.'/resources/images');
View::setConstant('RESOURCES_PATH',     '/skins/'.SKIN_NAME.'/resources');
View::setConstant('ROOT_PATH',          '/skins/'.SKIN_NAME);
View::setConstant('DESKTOP_PATH',       '/skins/'.SKIN_NAME.'/desktop');
View::setConstant('AUTHORIZER_PATH',    '/skins/'.SKIN_NAME.'/authorizer');
View::setConstant('TOKEN',              Manager::$Token->get());
View::setConstant('SITE_NAME',          Config::loadSystem('system')->get('siteName'));