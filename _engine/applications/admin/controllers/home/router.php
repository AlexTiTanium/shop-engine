<?php

use lib\Core\Core;

$router = Core::getController('home')->getRouter();

$router->addAction('default')->When(array());