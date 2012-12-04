<?php

use lib\Core\UrlService;
use lib\Cache\Cache;
use lib\Templates\TemplatesManager;
use lib\View\View;
use lib\Core\Core;
use lib\Session\Session;
use lib\Form\Form;

$url = UrlService::get(ENGINE);

$view = View::get($url->type);
$view->setCss('page');

$controllerUsers = Core::getController('users');
$controller = Core::getController('pages');
$action = $controller->URL->action;

$mapper = array(

  'about'=>'about',
  'for_executor'=>'executor',
  'for_customer'=>'customer',
  'faq'=>'faq',
  'contact'=>'contact',

);

if(!isset($mapper[$action])){
  $view->setTemplate('404');
}else{
  $view->setTemplate($mapper[$action]);
}

if(!Session::isLogged()){
  $view->setJs('watermark/jquery.watermark');
  $formConfig = $controllerUsers->Forms->yaml('login');
  $formConfig['login']['template'] = 'loginForm';
  $form = new Form($formConfig);
  $view->set('loginForm', $form->toString());
}