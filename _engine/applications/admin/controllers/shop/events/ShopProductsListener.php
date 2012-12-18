<?php

use lib\Core\Events;
use lib\ExtJs\GridHelperOdm;
use models\ODM\Repositories\ShopProductsRepository;
use lib\Core\Manager;
use lib\Core\Data;
use lib\Debugger\Debugger;

class ShopProductsListener extends Events {

  /**
   * @var ShopProductsRepository
   */
  private $shopProduct;

  /**
   * Fire on action call
   */
  protected function setUp(){

    $this->shopProduct = ShopProductsRepository::getRepository();
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
   * Products list
   */
  public function defaultEvent(){

    \lib\Debugger\Debugger::log($this->get);

    $propertiesQb = $this->shopProduct->createQueryBuilder();

    //$propertiesQb->field('nodeId')->equals($this->get->getRequired('nodeId'));

    $grid = new GridHelperOdm($propertiesQb, $this->get);
    $this->view->set($grid->getList());
  }

}