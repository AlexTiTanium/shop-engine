<?php

use lib\Core\Core;

$router = Core::getController('shop')->getRouter();

$router->addEventListener('CatalogListener')->When(array('action'=>'catalog'));

// Default
#$router->addEventListener('DefaultListener')->When(array('type'=>'json'));