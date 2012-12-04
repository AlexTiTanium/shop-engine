<?php

use lib\Core\Core;
use lib\Core\UrlService\Url;

$router = Core::getController('home')->getRouter();

$router->addEventListener('DefaultListener')->When(array())->Then(function(){
  Core::getController('shop')->call(new Url(array('action'=>'catalog', 'event'=>'renderForHome')));
});