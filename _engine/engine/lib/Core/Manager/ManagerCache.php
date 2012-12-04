<?php

namespace lib\Core\Manager;

use lib\Core\Manager;
use lib\Core\Config;
use lib\Core\Cache\MemcacheCache;
use Memcache;
use lib\Core\Cache\Cache;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 18.09.12
 * Time: 1:21
 * To change this template use File | Settings | File Templates.
 */
class ManagerCache {

  /**
   * @var Cache
   */
  private $cache;

  /**
   * @return Cache
   */
  public function getCache(){

    if($this->cache){ return $this->cache; }

    $memCache = new Memcache();
    $memCache->connect('localhost', 11211);

    $this->cache = new MemcacheCache();
    $this->cache->setMemcache($memCache);
    $this->cache->setNamespace(Config::loadSystem('system')->get('siteName'));

    return $this->cache;
  }

  /**
   * Deletes a cache entry.
   *
   * @param string $id cache id
   * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
   */
  public function delete($id){
    return $this->getCache()->delete($id);
  }

  /**
   * Test if an entry exists in the cache.
   *
   * @param string $id cache id The cache id of the entry to check for.
   * @return boolean TRUE if a cache entry exists for the given cache id, FALSE otherwise.
   */
  public function contains($id){
    if(DEBUG_MODE){ return false; }
    return $this->getCache()->contains($id);
  }

  /**
   * Fetches an entry from the cache.
   *
   * @param string $id cache id The id of the cache entry to fetch.
   * @return string The cached data or FALSE, if no cache entry exists for the given id.
   */
  public function fetch($id){
    return $this->getCache()->fetch($id);
  }

  /**
   * Puts data into the cache.
   *
   * @param string $id The cache id.
   * @param string $data The cache entry/data.
   * @param int $lifeTime The lifetime. If != 0, sets a specific lifetime for this cache entry (0 => infinite lifeTime).
   * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
   */
  public function save($id, $data, $lifeTime = 0){
    return $this->getCache()->save($id, $data, $lifeTime);
  }

}