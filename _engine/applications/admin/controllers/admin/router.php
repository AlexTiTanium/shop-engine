<?php

use lib\Core\Core;

$router = Core::getController('admin')->getRouter()->logged();

// Default
$router->addEventListener('DefaultListener')->When(array('type'=>'json'));