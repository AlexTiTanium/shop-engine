<?php

namespace lib\Doctrine;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Mapping\Driver\YamlDriver;
use Doctrine\ODM\MongoDB\Configuration;

class DoctrineOdm extends Doctrine {

  /**
   * @var DocumentManager
   */
  private static $manager;

  /**
   * Получить менеджер
   *
   * @return \Doctrine\ODM\MongoDB\DocumentManager
   */
  public static function getManager(){
    return self::$manager;
  }

  public static function setConnection($dbConfig){

    $config = new Configuration();

    $config->setProxyDir(PATH_ODM_PROXIES);
    $config->setProxyNamespace('ODM\Proxies');
    $config->setHydratorNamespace('ODM\Hydrators');
    $config->setHydratorDir(PATH_ODM_HYDRATORS);
    $config->setMetadataCacheImpl(self::getCache());
    $config->setDefaultDB($dbConfig['base']);

    if(DEBUG_MODE){
      $config->setLoggerCallable(function(array $log){
        \lib\Debugger\Debugger::addOdmEvent($log);
      });
    }

    $driverImpl = new YamlDriver(array(PATH_ODM_YAML_SCHEME), '.yml');

    $config->setMetadataDriverImpl($driverImpl);

    //$config->setAutoGenerateProxyClasses(DEBUG_MODE);
    //$config->setAutoGenerateHydratorClasses(DEBUG_MODE);

    // setup the mongodb connection
    $connection = new Connection(null, array(), $config);

    self::$manager = DocumentManager::create($connection, $config);
  }

  /**
   * @static
   * @param null $document
   * @return \Doctrine\ODM\MongoDB\Query\Builder
   */
  public static function createQueryBuilder($document = null){
    return self::getManager()->createQueryBuilder($document);
  }

}