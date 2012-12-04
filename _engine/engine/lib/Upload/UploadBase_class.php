<?php

namespace lib\Upload;

use lib\Core\Manager;

class Upload {

  private $uploadsErrors = array(
    0 => "There is error file uploading",
    1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
    3 => "The uploaded file was only partially uploaded",
    4 => "No file was uploaded",
    6 => "Missing a temporary folder"
  );

  public $name;
  public $path;
  public $absPath;
  public $pathAndName;
  public $relativeFullName;
  public $relativePath;
  public $fullName;
  public $fullPath;
  public $extension;
  public $mime;
  public $size;

  public $isUploaded = false;

  private $fileTmpName;
  private $fileTmpSize;
  private $fileTmpExtension;
  public $fileTmpMime;

  private $fileName;
  private $fileSize;
  private $fileUploadDir;
  private $fileValidExtension;
  private $fileValidMime;

  private $fileUploadError;
  private $useGrids = false;

  public $error = false;

  /**
   * Загрузить файл
   *
   * @param array $upload
   * @internal param $string - FileName
   * @internal param $string - UploadDir
   * @internal param $string - ValidExtensions
   * @internal param $string - MaxFileSize
   * @internal param $bool - UseGrids
   *
   * @return \lib\Upload\Upload
   */
  public function __construct($upload = array('fileName' => '', 'uploadDir' => '', 'validExtensions' => '', 'validMime' => '', 'maxFileSize' => '', 'useGrids' => false)){

    $this->fileName = $upload['fileName'];
    $this->fileUploadDir = $upload['uploadDir'] . DS;
    $this->fileSize = ($upload['maxFileSize'] * 1024) * 1024;
    $this->fileValidExtension = $this->getList($upload['validExtensions']);
    $this->fileValidMime = $this->getList($upload['validMime']);
    $this->useGrids = isset($upload['useGrids']) ? $upload['useGrids'] : false;
    if(is_array($upload['validExtensions'])) {
      $upload['validExtensions'] = implode(',', $upload['validExtensions']);
    }
    if(is_array($upload['validMime'])) {
      $upload['validMime'] = implode(',', $upload['validMime']);
    }
    #----------------------------------------------------------------------

    $error = false;

    if(!$this->isFileUploaded()) {
      $error = 'Файл не загружен на сервер';
    }
    if(!$error and !$this->fileCorrectlySize()) {
      $error = 'Допустимый размер файла в ' . $upload['maxFileSize'] . ' MB, превышен';
    }
    if(!$error and !$this->fileCorrectlyExtension()) {
      $error = 'Не допустимое расщирение файла, поддерживаются: ' . $upload['validExtentions'];
    }
    if(!$error and !$this->fileCorrectlyMime()) {
      $error = 'Не допустимое тип файла, поддерживаются: ' . $upload['validMime'] . ', прислали ' . $this->fileTmpMime;
    }

    if(!$error AND !$this->generateFileName()) {
      $error = 'Ошибка при генерации имени';
    }

    if(!$error AND !$this->generateDir()) {
      $error = 'Ошибка при генерации директории';
    }

    if(!$error AND !$this->fileUploadError) {
      if(!empty($this->path)) {
        $dir = $this->path . DS;
      } else {
        $dir = '';
      }

      $this->fullName = $this->name . '.' . $this->extension;
      $this->pathAndName = $dir . $this->name . '.' . $this->extension;
      $this->fullPath = $this->fileUploadDir . $this->pathAndName;
      $this->relativeFullName = Manager::$Php->getRelativePath($this->fullPath);
      if($this->useGrids) {
        $sl = '/';
      } else {
        $sl = '';
      }
      $this->relativePath = Manager::$Php->getRelativePath($this->fileUploadDir) . $this->path . $sl;
      $this->absPath = $this->fileUploadDir . $this->path . DS;
    }

    if(!$error AND !$this->moveFile()) {
      $error = 'Ошибка при перемещении файла';
    }

    if($error or $this->fileUploadError) {
      $this->error = $error . $this->fileUploadError;
    } else {
      $this->isUploaded = true;
    }
  }

  /**
   * Загружен ли файл на сервер
   *
   * @return bool
   */
  private function isFileUploaded(){

    if(is_uploaded_file($_FILES[$this->fileName]['tmp_name'])) {

      $this->fileTmpName = $_FILES[$this->fileName]['tmp_name'];
      $this->fileTmpSize = $_FILES[$this->fileName]['size'];
      $this->fileTmpMime = $this->fileGetMime();
      $this->fileTmpExtension = $this->fileGetExtension();

      return true;

    }

    if(isset($_FILES[$this->fileName]['error'])) {
      $this->fileUploadError = $this->getFileUploadError($_FILES[$this->fileName]['error']);
    } else {
      $this->fileUploadError = 'Uknown error';
    }

    return false;
  }

  /**
   * Проверка размера файла
   *
   * @return bool
   */
  private function fileCorrectlySize(){

    if(($this->fileTmpSize < $this->fileSize) AND $this->fileTmpSize > 0) {
      $this->size = $this->fileSize;
      return true;
    }

    return false;
  }

  /**
   * Проверка типа файла
   *
   * @return bool
   */
  private function fileCorrectlyMime(){

    $key = array_search($this->fileTmpMime, $this->fileValidMime);
    if($key !== false) {
      $this->mime = $this->fileTmpMime;
      return true;
    }

    return false;
  }

  /**
   * Проверка расширения файла
   *
   * @return bool
   */
  private function fileCorrectlyExtension(){

    $key = array_search($this->fileTmpExtension, $this->fileValidExtension);
    if($key !== false) {
      $this->extension = $this->fileTmpExtension;
      return true;
    }

    return false;
  }

  /**
   * Получить тип файла
   *
   * @return string
   */
  private function fileGetMime(){
    return strtolower($_FILES[$this->fileName]['type']);
  }

  /**
   * Получить расщирение файла
   *
   * @return string
   */
  private function fileGetExtension(){

    $fileName = $_FILES[$this->fileName]['name'];
    $fileArrayByDot = explode('.', $fileName);
    $fileExtension = end($fileArrayByDot);
    $strlowerext = strtolower($fileExtension);

    return $strlowerext;
  }

  /**
   * Получить ошибку загрузки файла
   *
   * @param  int
   * @return string
   */
  private function getFileUploadError($errorID = 0){
    if(isset($this->uploadsErrors[$errorID])) {
      $return = $this->uploadsErrors[$errorID];
    } else {
      $return = 'Uknown error';
    }

    return $return;
  }

  /**
   * Получить список расширений, типов
   *
   * @param  string
   * @return array
   */
  private function getList($string){

    $arrayExtOUT = array();

    if(is_string($string)) {
      $arrayExtIN = explode(',', $string);
    } else {
      $arrayExtIN = $string;
    }

    foreach($arrayExtIN as $value) {
      $arrayExtOUT[] = strtolower(trim($value));
    }

    return $arrayExtOUT;
  }

  /**
   * Сгенерировать имя файла
   *
   * @return bool
   */
  private function generateFileName(){

    $timeStamp = time();
    $randPart = mt_rand(1, 9999);
    $crcPart = crc32($this->fileTmpName);

    if(!empty($timeStamp) and !empty($randPart)) {
      $this->name = $timeStamp . $randPart . $crcPart;
      return true;
    }

    return false;
  }

  /**
   * Сгенерировать директорию
   *
   * @return bool
   */
  private function generateDir(){

    $return = false;
    if($this->useGrids) {
      if(is_dir($this->fileUploadDir . substr($this->name, 0, 3))) {
        $this->path = substr($this->name, 0, 3);
        $return = true;
      } else {
        $return = mkdir($this->fileUploadDir . substr($this->name, 0, 3), 0777);
        $this->path = substr($this->name, 0, 3);
      }
    } else {
      if(!is_dir($this->fileUploadDir)) {
        $return = mkdir($this->fileUploadDir, 0777);
      } else {
        $return = true;
      }
      $this->path = '';
    }

    return $return;
  }

  /**
   * Переместить файл
   *
   * @return bool
   */
  private function moveFile(){
    return move_uploaded_file($this->fileTmpName, $this->fullPath);
  }

}