<?php

namespace lib\Core\Storage;
use Upload\File;
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
   * @param string $name
   */
  public function __construct($name){

    $this->file = new File($name);
    $this->file->setName(uniqid("",true));
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
   */
  public function copyTo($path) {
    $storage = new FileSystem($path);
    $storage->upload($this->file);
  }
}
