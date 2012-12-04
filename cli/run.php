<?php

use lib\Core\Manager;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use lib\Doctrine\DoctrineOrm;
use Symfony\Component\Console\Application;
use lib\Doctrine\DoctrineOdm;
use lib\Core\Config;
use lib\Debugger\Debugger;

define('DS', DIRECTORY_SEPARATOR);

define('PATH', dirname(__FILE__));

define('FOLDER_ENGINE',   'engine');
define('PUBLIC_HTML',     'public_html');
define('FOLDER_LIB',      'lib');
define('CLI_MODE',        true);

define('PATH_SYSTEM',         PATH . DS . '..' . DS . '_engine' . DS);
define('PATH_PUBLIC_HTML',    PATH . DS . '..' . DS . PUBLIC_HTML . DS);
define('PATH_MANAGER',        PATH_SYSTEM . FOLDER_ENGINE . DS . FOLDER_LIB . DS . 'Core' . DS);

define('DEBUG_MODE', false);

require_once(PATH_MANAGER . 'Manager.php');

Manager::getAutoloader()
  ->addNamespace('Console' , PATH . DS);

DoctrineOdm::setConnection(Config::loadSystem('mongoDb')->get('Connection')->value('default'));
//DoctrineOrm::setConnection(Config::loadSystem('mysql')->get('Connection')->value('default'));

$helperSet = new HelperSet(array(
    //'db' => new ConnectionHelper(DoctrineOrm::getManager()->getConnection()),
    //'em' => new EntityManagerHelper(DoctrineOrm::getManager()),
    'dm' => new DocumentManagerHelper(DoctrineOdm::getManager())
));

$cli = new Application('Engine Command Line Interface', '0.1');
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands(array(

  // System Commands
  new Console\Commands\AddAdminUserCommand(),
  new Console\Commands\UpdateOdmCommand(),

  // Code generator
  new Console\Commands\CodeGenerator\CreateControllerCommand(),
  new Console\Commands\CodeGenerator\CreateEventCommand(),
  new Console\Commands\CodeGenerator\CreateExtJsApplicationCommand(),
  new Console\Commands\CodeGenerator\CreateExtJsControllerCommand(),
  new Console\Commands\CodeGenerator\CreateExtJsModelCommand(),
  new Console\Commands\CodeGenerator\CreateExtJsStorageCommand(),

  // DBAL Commands
  new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
  new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

  // ORM Commands
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
  new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
  new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
  new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),

  // ODM
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateDocumentsCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateRepositoriesCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\ClearCache\MetadataCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\CreateCommand(),
  new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\DropCommand(),
));

$cli->run();