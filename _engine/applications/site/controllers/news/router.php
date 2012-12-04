<?php

use lib\Core\Core;

$controller = Core::getController('news');

//$controller->ControllerRouter->addAction('showNewsById')->notEmpty('id')->addTitle('Новости')->When(array());
//$controller->ControllerRouter->addAction('allNews')->addTitle('Новости')->When(array());

throw new \lib\EngineExceptions\SystemException('Раздел находится в разработке');