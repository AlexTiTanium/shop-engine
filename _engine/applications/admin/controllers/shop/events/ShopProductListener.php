<?php

use lib\Core\Events;
use Documents\Shop\Products;
use models\ODM\Repositories\ShopProductsRepository;
use lib\Core\Storage\UploadedFile;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductListener extends Events {


  protected function setUp(){


  }

  public function imageUpload(){

    $storeUpload = new UploadedFile('image');
    $fileName = Manager::$Storage->save('product_images', $storeUpload);

  }

  public function update(){

    $data = $this->post->getJsonRequest('data');

  }

  public function create(){

    $data = $this->post->getJsonRequest('data');

    $productRepository = ShopProductsRepository::getRepository();

    $product = new Products();

    $product->setDateAdd(new MongoDate());

    $productRepository->update($product);

    $this->view->set('data', array('id'=>$product->getId()));
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