<?php

use lib\Core\Core;

$controller = Core::getController('pages');
$controllerUsers = Core::getController('users');

$controllerUsers->Router->addAction('login')->When(array('event'=>'login'));

$controller->Router->addAction('default')->When(array());