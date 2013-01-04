<?php

use lib\Core\Events;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use lib\Core\Manager;
use lib\Core\Data;

class StorageListener extends Events {


  protected function setUp(){


  }

  /**
   * @param $binData
   * @return ImageInterface
   */
  private function loadImage($binData){

    $imagine = new Imagine();
    $imagine = $imagine->load($binData);

    return $imagine;
  }

  /**
   * @param $binData
   * @param $width
   * @param $height
   * @param $way
   * @return ImageInterface
   */
  private function processingImage($binData, $width, $height, $way){

    $image = $this->loadImage($binData);
    $processedImage = null;
    $box = new Box($width, $height);

    switch($way){

      case 'outbound':
      case 'inset':

        $processedImage = $image->thumbnail($box, $way);

        break;

    }

    return $processedImage == null ? $image : $processedImage;
  }

  /**
   * Create thumbnail show and save it
   *
   */
  public function defaultEvent(){

    $file = Manager::$Storage->get(
      $this->url->getParams('storage'),
      $this->url->getParams('imageName').'.'.$this->url->getType()
    );

    $imageBin = $this->processingImage(
      $file->read(),
      $this->url->getParams('width'),
      $this->url->getParams('height'),
      $this->url->getParams('cropWay')
    );

    $prefix = $this->url->getParams('width').'x'.$this->url->getParams('height').'-'.$this->url->getParams('cropWay');

    $file->setPrefix($prefix);

    $file->write($imageBin->get($this->url->getType()));

    $imageBin->show($this->url->getType());
  }

}