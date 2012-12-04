<?php

use lib\Core\Manager;
use lib\Core\UrlService\Url;
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
Manager::$Define->skin('lotos');

IncluderService::connectApplicationFileSystem();

Manager::getAutoloader()
  ->addNamespace('controllers',  PATH_APPLICATIONS.CURRENT_APPLICATION.DS)
  ->addNamespace('site',         PATH_APPLICATIONS)
  ->addNamespace('shop',         PATH_APPLICATIONS_LIB);

Session::open(new SessionDoctrineDriver('users_session',
  new DoctrineSessionStorage('Documents\User', 'Documents\Users\SessionStorage')
));

TemplatesManager::addPath(IncluderService::$skin->getPath());
TemplatesManager::addPath(IncluderService::$skin->setPath('templates')->getPath());
TemplatesManager::addPath(IncluderService::$skin->setPath('fields')->getPath());

ControllerRouter::$autoRedirect = true;
ControllerRouter::$loginPage = '/users/login.html';

View::getHtmlView()->setTemplate('index');
View::getHtmlView()->setTitle(Config::loadSystem('system')->value('siteName'));

if(Session::isLogged()){
  View::getHtmlView()->set('isLogged', true);
}

View::setConstant('IMAGES',     '/skins/'.SKIN_NAME.'/images');
View::setConstant('JS_TEMPLATE','/skins/'.SKIN_NAME.'/templates/js');
View::setConstant('SITE_NAME',  Config::loadSystem('system')->value('siteName'));
View::setConstant('SITE_URL',   Config::loadSystem('system')->value('siteUrl'));

// Push notifications
//View::setConstant('MESSAGES',  Session::getVar('messages'));

Core::getController('shop')->call(new Url(array('action'=>'catalog', 'event'=>'sendCatalogToView')));