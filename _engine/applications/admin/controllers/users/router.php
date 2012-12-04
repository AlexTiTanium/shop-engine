<?php

use lib\Core\Core;

$router = Core::getController('users')->getRouter();

$router->addEventListener('LoginListener')->When(array('action'=>'login', 'type'=>'json'));

// Default
$router->addEventListener('DefaultListener')->When(array('type'=>'json'));