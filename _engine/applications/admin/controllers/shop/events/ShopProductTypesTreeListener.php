<?php

use lib\Core\Events;
use models\ODM\Repositories\ShopProductsTypesTreeRepository;
use lib\Debugger\Debugger;
use lib\ExtJs\TreeHelperOdm;

class ShopProductTypesTreeListener extends Events {

  /**
   * @var models\ODM\Repositories\ShopProductsTypesTreeRepository
   */
  private $treeRepository;

  /**
   * @var lib\ExtJs\TreeHelperOdm
   */
  private $treeHelper;

  protected function setUp(){

    $this->treeRepository = ShopProductsTypesTreeRepository::getRepository();
    $this->treeHelper = new TreeHelperOdm($this->treeRepository);
    $this->treeHelper->setLeafIconCls('ux-icon-package-link');
  }


  public function update(){

    $data = $this->post->getJsonRequest('data');
    $this->view->set('children', $this->treeHelper->update($data));
  }

  public function create(){

    $data = $this->post->getJsonRequest('data');
    $this->view->set('children', $this->treeHelper->add($data));
  }

  public function destroy(){

    $data = $this->post->getJsonRequest('data');
    $this->treeHelper->destroy($data);
  }

  /**
   *
   */
  public function defaultEvent(){

    $this->view->set('children', $this->treeHelper->getTree());
  }

}