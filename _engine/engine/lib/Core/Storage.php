<?php

namespace lib\Core;

use lib\Core\Storage\IStorage;
use lib\Core\Storage\IStorageFile;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 10.12.12
 * Time: 18:34
 * To change this template use File | Settings | File Templates.
 */
class Storage {

  /**
   * @var IStorage
   */
  private $storage;

  public function __construct(IStorage $storage){
    $this->storage = $storage;
  }

  public function save(IStorageFile $file){
    return $this->storage->save($file);
  }

  public function get($storeRootId, $fileWithExtensionId){
    return $this->storage->get($storeRootId, $fileWithExtensionId);
  }

  public function read($storeRootId, $fileWithExtensionId){
    return $this->storage->read($storeRootId, $fileWithExtensionId);
  }

  public function write($storeRootId, $fileWithExtensionId, $content){
    $this->storage->write($storeRootId, $fileWithExtensionId, $content);
  }
}
