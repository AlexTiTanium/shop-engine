<?php

use lib\Core\UrlService;
use lib\View\View;
use lib\Session\Session;

$view = View::current();

if(Session::isLogged()){
  $view->set('userLogin', Session::getUser()->getLogin());
  $view->set('userBalance', Session::getUser()->getBalance());
  $view->set('userReserve', Session::getUser()->getReserve());
}