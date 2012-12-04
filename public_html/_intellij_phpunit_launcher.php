<?php

define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__));

define('PATH_TEST',     PATH . DS . '..' . DS . 'test' . DS);

error_reporting(-1);
ini_set('display_errors', true);

include PATH_TEST . '_intellij_phpunit_launcher.php';