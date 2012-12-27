<?php

use lib\Core\Core;

$router = Core::getController('storage')->getRouter();

// Default
$router->addEventListener('StorageListener')->When(array());