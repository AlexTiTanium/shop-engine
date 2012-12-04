<?php

namespace lib\Doctrine;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;
use lib\Debugger\Debugger;
use lib\Core\Config;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

class DoctrineOrm extends Doctrine {

  /**
   * @var EntityManager
   */
  private static $manager;

  /**
   * Получить менеджер
   *
   * @return \Doctrine\ORM\EntityManager
   */
  public static function getManager(){
    return self::$manager;
  }

  /**
   * Новое подключение к базе
   *
   * @param array $connectionConfig
   * @internal param \config\DBmysql $config
   * @internal param \lib\Doctrine\UpdateModels $bool
   * @return void
   */
  public static function setConnection($connectionConfig){

    $config = new Configuration();

    $config->setMetadataCacheImpl(Doctrine::getCache());

    $driverImpl = new YamlDriver(array(PATH_MODELS . 'ORM' . DS  . 'yaml'));

    $driverImpl->setFileExtension('.dcm.yml');
    $config->setMetadataDriverImpl($driverImpl);
    $config->setQueryCacheImpl(self::getCache());

    $config->setProxyDir(PATH_MODELS . 'ORM' . DS . 'Proxies');

    $config->setProxyNamespace('ORM\Proxies');

    $config->setAutoGenerateProxyClasses(DEBUG_MODE);

    if(DEBUG_MODE){
      $ormLogger = new DoctrineSqlLogger();
      Debugger::setOrmLogger($ormLogger);
      $config->setSQLLogger($ormLogger);
    }

    $dbParams = array(
          'driver'   => $connectionConfig['Type'],
          'user'     => $connectionConfig['UserName'],
          'password' => $connectionConfig['Pass'],
          'dbname'   => $connectionConfig['BaseName'],
          'host'     => $connectionConfig['Host']
      );

    self::$manager = EntityManager::create($dbParams, $config);

    $connection = self::$manager->getConnection();
    $platform = $connection->getDatabasePlatform();
    $platform->registerDoctrineTypeMapping('enum', 'string');
    $platform->registerDoctrineTypeMapping('set', 'string');

  }



  /**
   * Начать транзакцию
   *
   * @return void
   **/
  public static function beginTransaction(){
    self::getManager()->getConnection()->beginTransaction();
  }

  /**
   * Сохранить транзакцию
   *
   * @return void
   **/
  public static function commitTransaction(){
    self::getManager()->getConnection()->commit();
  }

  /**
   * Откатить транзакцию
   *
   * @return void
   **/
  public static function rollbackTransaction(){
    self::getManager()->getConnection()->rollback();
  }

  /**
   * @return \Doctrine\ORM\QueryBuilder
   */
  public static function createQueryBuilder(){
    return self::getManager()->createQueryBuilder();
  }

}