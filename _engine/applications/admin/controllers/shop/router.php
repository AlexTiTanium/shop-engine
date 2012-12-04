<?php

use lib\Core\Core;

$router = Core::getController('shop')->getRouter()->logged();

$router->addEventListener('ShopProductTypesTreeListener')->When(array('action'=>'shopTypesTree','type'=>'json'));
$router->addEventListener('ShopProductPropertiesListener')->When(array('action'=>'shopTypesProperties','type'=>'json'));
$router->addEventListener('ShopCatalogListener')->When(array('action'=>'shopCatalog','type'=>'json'));
$router->addEventListener('ShopProductsListener')->When(array('action'=>'products','type'=>'json'));


// Default
#$router->addEventListener('DefaultListener')->When(array('type'=>'json'));