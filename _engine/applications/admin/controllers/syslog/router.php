<?php

use lib\Core\Core;

$router = Core::getController('syslog')->getRouter()->logged();;

// Default
$router->addEventListener('DefaultListener')->When(array());