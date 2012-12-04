<?php

namespace lib\Doctrine;

use Memcache;
use Doctrine\Common\Cache\MemcacheCache;
use lib\Core\Config;

class Doctrine {

  /**
   * @var \Doctrine\Common\Cache\Cache
   */
  private static $cache = null;

  /**
   * @return \Doctrine\Common\Cache\Cache
   */
  public static function getCache(){

    if(self::$cache != null){ return self::$cache; }

    $memCache = new Memcache();
    $memCache->connect('localhost', 11211);

    self::$cache = new MemcacheCache();
    self::$cache->setMemcache($memCache);
    self::$cache->setNamespace(Config::loadSystem('system')->get('siteName'));

    return self::$cache;
  }

  /**
   * @param $entity
   * @internal param object $object The instance to make managed and persistent.
   */
  public static function persist($entity){
    static::getManager()->persist($entity);
  }

  /**
   * @param $entity
   * @internal param object $object The instance to make managed and persistent.
   */
  public static function remove($entity){
    static::getManager()->remove($entity);
  }

  /**
   * @static
   * @param object|null $entity
   */
  public static function flush($entity = null){
    static::getManager()->flush($entity);
  }

  /**
   * @static
   * @param string $repositoryName
   * @return \Doctrine\ODM\MongoDB\DocumentRepository|\Doctrine\ORM\EntityRepository
   */
  public static function getRepository($repositoryName){
    return static::getManager()->getRepository($repositoryName);
  }

  /**
   * @static
   * @param string $repositoryName
   * @param string $id
   * @return object $document
   */
  public static function find($repositoryName, $id){
    return static::getManager()->find($repositoryName, $id);
  }

}