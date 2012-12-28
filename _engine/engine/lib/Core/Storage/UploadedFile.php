<?php

namespace lib\Core\Storage;
use Upload\File;
use lib\EngineExceptions\SystemException;
use Upload\Storage\FileSystem;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 12.12.12
 * Time: 16:37
 * To change this template use File | Settings | File Templates.
 */
class UploadedFile implements IStorageFile {

  /**
   * @var File
   */
  private $file;

  /**
   * @var string
   */
  private $storageId;

  /**
   * @param string $storageId
   * @param string $name
   */
  public function __construct($storageId, $name){

    $this->file = new File($name);
    $this->file->setName(str_replace('.','_',uniqid('',true)));
    $this->storageId = $storageId;
  }

  /**
   * Add validator to file
   *
   * Size validation params: 5M (use "B", "K", M", or "G")
   * Type validation: "image/png" or array("image/png", "image/jpg", "image/gif" )
   * Extension validation: "png" or array("jpg", "png", "gif")
   *
   * @param string $validationName
   * @param mixed $param
   */
  public function addValidation($validationName, $param){

    switch($validationName){

      case 'size':
        $this->file->addValidations(new \Upload\Validation\Size($param));
        break;
      case 'type':
        $this->file->addValidations(new \Upload\Validation\Mimetype($param));
        break;
      case 'extension':
        $this->file->addValidations(new \Upload\Validation\Extension($param));
        break;
    }
  }

  /**
   * Get file name with out extension
   *
   * @return string
   */
  public function getName() {

    return $this->file->getName();
  }

  /**
   * Get file extension
   *
   * @return string
   */
  public function getExtension() {

    return $this->file->getExtension();
  }

  /**
   * Copy file to path
   *
   * @param string $path
   * @return string
   */
  public function copyTo($path) {
    $storage = new FileSystem($path);
    $storage->upload($this->file);

    return $this->file->getNameWithExtension();
  }

  /**
   * @return string
   */
  public function getPrefix() {
    return '';
  }

  /**
   * @param string $content
   * @throws SystemException
   * @return void
   */
  public function write($content) {

    throw new SystemException('Upload file object not support it');
  }

  /**
   * @throws SystemException
   * @return string
   */
  public function read() {

    throw new SystemException('Upload file object not support it');
  }

  /**
   * @param $prefix
   * @throws SystemException
   * @return void
   */
  public function setPrefix($prefix) {

    throw new SystemException('Upload file object not support it');
  }

  /**
   * @return string
   */
  public function getStoreId() {

    return $this->storageId;
  }
}
