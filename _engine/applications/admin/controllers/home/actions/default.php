<?php

use lib\Session\Session;
use lib\View\View;

$view = View::getHtmlView();

if(Session::isLogged()){
  $view->setTemplate('desktop');
}else{
  $view->setTemplate('authorizer');
}