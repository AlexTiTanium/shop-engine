<?php

namespace lib\Core\Storage;
use lib\Core\Manager;
use lib\EngineExceptions\SystemException;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 12.12.12
 * Time: 16:37
 * To change this template use File | Settings | File Templates.
 */
class StorageFile implements IStorageFile {

  private $fileWithExtensionId;
  private $fileName;
  private $fileExtension;
  private $storeId;
  private $prefix;
  /**
   * @var IStorage
   */
  private $storage;

  /**
   * @param $storeId
   * @param string $fileWithExtensionId - someName.png
   * @param IStorage $storage
   */
  public function __construct($storeId, $fileWithExtensionId, IStorage $storage){

    $this->storeId = $storeId;
    $this->fileWithExtensionId = $fileWithExtensionId;
    $this->storage = $storage;

    $this->getName();
    $this->getExtension();
  }

  /**
   * Get file name with out extension
   *
   * @return string
   */
  public function getName() {

    if (!$this->fileName) {
      $this->fileName = pathinfo($this->fileWithExtensionId, PATHINFO_FILENAME);
    }

    return $this->fileName;
  }

  /**
   * Get file name with out extension
   *
   * @return string
   */
  public function getStoreId() {

    return $this->storeId;
  }

  /**
   * Get file extension
   *
   * @return string
   */
  public function getExtension() {

    if(!$this->fileExtension){
      $this->fileExtension = pathinfo($this->fileWithExtensionId, PATHINFO_EXTENSION);
    }

    return  $this->fileExtension;
  }

  /**
   * @return string
   */
  public function getPrefix() {

    return $this->prefix ? $this->prefix.':' : '';
  }

  /**
   * @param $prefix
   * @return void
   */
  public function setPrefix($prefix) {

    $this->prefix = $prefix;
  }

  /**
   * @param mixed $content
   */
  public function write($content) {

    $this->storage->write($this, $content);
  }

  /**
   * @return mixed
   */
  public function read() {

    return $this->storage->read($this);
  }


}
