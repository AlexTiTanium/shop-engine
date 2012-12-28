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
   *
   */
  public function defaultEvent(){

    $file = Manager::$Storage->get(
      $this->url->getParams('storage'),
      $this->url->getParams('imageName').'.'.$this->url->getType()
    );

    $imagine = new Imagine();

    $imagine = $imagine->load($file->read());

    $box = new Box($this->url->getParams('width'), $this->url->getParams('height'));

    $thumbnail = $imagine->thumbnail($box, $this->url->getParams('cropWay'));

    $thumbnail->show($this->url->getType());


    //$file->setPrefix('10x10-box');

    //$file->write('dsdsdsdsd');



    //\lib\Debugger\Debugger::log($file);

    //$this->storage->createModification($stotage, $fileId, $modificationId, $content);
    //echo "Works fine";
  }

}