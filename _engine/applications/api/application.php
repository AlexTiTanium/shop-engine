<?php

use lib\Core\Manager;
use lib\Session\drivers\SessionWithoutAuthorizationDriver;
use lib\Session\Session;
use lib\Core\IncluderService;

Manager::$Define->setControllersPath(PATH_APPLICATIONS.CURRENT_APPLICATION.DS);

IncluderService::connectApplicationFileSystem();

Session::open(new SessionWithoutAuthorizationDriver('api_session'));

Manager::$Autoloader->addNamespace('Imagine', PATH_VENDOR);