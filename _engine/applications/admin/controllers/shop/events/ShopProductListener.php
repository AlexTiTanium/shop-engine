<?php

use lib\Core\Events;
use lib\Core\Storage\UploadedFile;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductListener extends Events {


  protected function setUp(){


  }

  public function imageUpload(){

    $storeUpload = new UploadedFile('image');
    Manager::$Storage->save('product_images', $storeUpload);
  }

  public function update(){

    $data = $this->post->getJsonRequest('data');

  }

  public function create(){

    $data = $this->post->getJsonRequest('data');

  }

  public function destroy(){

    $data = $this->post->getJsonRequest('data');

  }

  /**
   *
   */
  public function defaultEvent(){

    $this->view->set('data',

    array('images'=>array(
      array('id'=>'fd45gdfgdffdfds.jpg'),
      array('id'=>'fd46gdfgdffdfds.jpg')
    )));

  }

}