<?php

namespace lib\Form\elements;

use lib\Templates\TemplatesManager;
use lib\Core\UrlService;
use lib\Core\IncluderService;
use lib\View\View;
use lib\Form\IFormElement;

class UploadImagesElement implements IFormElement {

  #is connect plugin
  private static $isJsIncluded = false;

  # Properties
  public $id;
  public $name;
  public $class;
  public $style;
  public $template = 'uploadImagesElement';
  public $label = 'label';
  public $value;
  public $inform;
  public $uploadDispatcher;
  public $deleteDispatcher;
  public $uploadAnimation;
  public $maxFiles;

  public $error = false;

  # Validation
  public $validation = array();

  public function __construct($name, array $config){

    $this->name = $name;

    foreach($config as $key => $value) {
      $this->$key = $value;
    }
  }

  public function toString(){

    if(!self::$isJsIncluded) {
      View::get('html')->setJs(IncluderService::$skin->jq->getPath('ajaxupload', 'jsLink'));
      View::get('html')->setJs(IncluderService::$skin->jq->getPath('imagesUploader', 'jsLink'));
      self::$isJsIncluded = true;
    }

    if($this->value) {
      //$this->value = json_encode(UploadClass::getFiles($this->value));
    }

    $this->deleteDispatcher = UrlService::get('Delete')->set(array('module' => 'upload', 'action' => $this->deleteDispatcher, 'page' => false, 'type' => 'json'));
    $this->uploadDispatcher = UrlService::get('Upload')->set(array('module' => 'upload', 'action' => $this->uploadDispatcher, 'page' => false, 'type' => 'json'));
    $this->uploadAnimation = IncluderService::$skin->images->imageLink('loadingAnimation.gif');

    $element = TemplatesManager::load($this->template);
    $element->set($this);

    return $element->toString();
  }

}