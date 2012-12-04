<?php

use lib\Core\Events;
use lib\Core\Manager;
use lib\Core\Data;
use Documents\Shop\Catalog;
use models\ODM\Repositories\ShopCatalogRepository;
use lib\Debugger\Debugger;
use lib\ExtJs\TreeHelperOdm;

class ShopCatalogListener extends Events {

  /**
   * @var models\ODM\Repositories\ShopCatalogRepository
   */
  private $treeRepository;

  /**
   * @var lib\ExtJs\TreeHelperOdm
   */
  private $treeHelper;

  protected function setUp(){

    $this->treeRepository = ShopCatalogRepository::getRepository();
    $this->treeHelper = new TreeHelperOdm($this->treeRepository);
    $this->treeHelper->setLeafIconCls('ux-icon-package');

    $this->treeHelper->onUpdate(function(Catalog $model, Data $data){
      $model->setAlias(Manager::$Common->translitEncode($data->get('name')));
    });
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