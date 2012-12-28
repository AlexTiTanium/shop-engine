<?php

use lib\Core\Events;
use lib\EngineExceptions\SystemException;
use Documents\Shop\Products;
use models\ODM\Repositories\ShopProductsRepository;
use lib\Core\Storage\UploadedFile;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductImagesListener extends Events {

  /**
   * @var ShopProductsRepository
   */
  private $productRepo;

  /**
   * Run on action call
   */
  protected function setUp(){

    $this->productRepo = ShopProductsRepository::getRepository();
  }


  /**
   * Upload product image
   *
   * @throws SystemException
   */
  public function upload(){

    $this->post->checkToken();

    $file = new UploadedFile('product_images','image');

    $file->addValidation('size',      '5M');
    $file->addValidation('type',      array('image/png', 'image/jpg', 'image/gif'));
    $file->addValidation('extension', array('jpg', 'png', 'gif'));

    $fileName = Manager::$Storage->save($file);

    /**
     * @var Products $product
     */
    $product = $this->productRepo->find($this->post->getRequired('id'));

    if(!$product){
      throw new SystemException('Product not found');
    }

    $product->addImage($fileName, array(
      'storage'=>$file->getStoreId(),
      'folder'=>substr(md5($fileName), 0, 2)
    ));

    $this->productRepo->update($product);
  }

  /**
   * Update product data
   */
  public function update(){
/*
    $data = $this->post->getJsonRequest('data');

    $product = $this->productRepo->find($data->getRequired('id'));

    $this->setData($product, $data);

    $this->productRepo->update($product);
    $this->view->set('data', $product->toFlatArray());*/
  }

  /**
   * Create product
   */
  public function create(){


  }

  /**
   * Delete product
   */
  public function destroy(){

  }

  /**
   * Product images by id
   */
  public function defaultEvent(){

    $product = $this->productRepo->find($this->get->getRequired('productId'));
    $this->view->set('data', $product->getImages());
  }

}