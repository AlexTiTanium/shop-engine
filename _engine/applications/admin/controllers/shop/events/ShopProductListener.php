<?php

use lib\Core\Events;
use lib\EngineExceptions\SystemException;
use Documents\Shop\Products;
use models\ODM\Repositories\ShopProductsRepository;
use lib\Core\Storage\UploadedFile;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductListener extends Events {

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
  public function imageUpload(){

    $this->post->checkToken();

    $file = new UploadedFile('image');

    $file->addValidation('size',      '5M');
    $file->addValidation('type',      array('image/png', 'image/jpg', 'image/gif'));
    $file->addValidation('extension', array('jpg', 'png', 'gif'));

    $fileName = Manager::$Storage->save('product_images', $file);

    /**
     * @var Products $product
     */
    $product = $this->productRepo->find($this->post->getRequired('id'));

    if(!$product){
      throw new SystemException('Product not found');
    }

    $product->addImage($fileName);

    $this->productRepo->update($product);
  }

  /**
   * Update product data
   */
  public function update(){

    $data = $this->post->getJsonRequest('data');

    $product = $this->productRepo->find($data->getRequired('id'));

    $this->setData($product, $data);

    $this->productRepo->update($product);
    $this->view->set('data', $product->toFlatArray());
  }

  /**
   * Fill model fields
   *
   * @param Products $product
   * @param Data $data
   */
  private function setData(Products $product, Data $data){

    $product->setName($data->get('name', 'Unknown'));
    $product->setDescription($data->get('description', ''));
    $product->setPrice($data->get('price', '0.00'));
    $product->setStatus($data->get('status', Products::STATUS_DISABLE));
  }

  /**
   * Create product
   */
  public function create(){

    $data = $this->post->getJsonRequest('data');

    $product = new Products();

    $product->setDateAdd(new MongoDate());
    $product->setCatalog($data->getRequired('catalog'));

    $this->setData($product, $data);

    $this->productRepo->update($product);
    $this->view->set('data', $product->toFlatArray());
  }

  /**
   * Delete product
   */
  public function destroy(){

    $data = $this->post->getJsonRequest('data');

  }

  /**
   * Product data by id
   */
  public function defaultEvent(){

    $product = $this->productRepo->find($this->get->getRequired('id'));
    $this->view->set('data', $product->toFlatArray());
  }

}