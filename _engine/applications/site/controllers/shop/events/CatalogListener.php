<?php

use lib\Core\Events;
use lib\EngineExceptions\SystemException;
use Documents\Shop\Catalog;
use lib\Debugger\Debugger;
use lib\Core\Manager;
use models\ODM\Repositories\ShopCatalogRepository;
use lib\ExtJs\TreeHelperOdm;

class CatalogListener extends Events {

  /**
   * @var ShopCatalogRepository
   */
  private $catalogRepo;

  /**
   * @var TreeHelperOdm
   */
  private $treeHelper;

  public function setUp(){

    $this->catalogRepo = ShopCatalogRepository::getRepository();
    $this->treeHelper = new TreeHelperOdm($this->catalogRepo);

    $this->treeHelper->onNodeCreate(function(Catalog $model, &$node){
      $node['alias'] = $model->getAlias();
    });
  }

  private function setBreadcrumb($chunks){

    $data = array();
    $path = array();

    foreach($chunks as $alias){

      $path[] = $alias;
      $name = $this->catalogRepo->findOneBy(array('alias'=>$alias));

      if(!$name){
        Manager::$Headers->error404();
        throw new SystemException('Каталог "'.$alias.'" не найден');
      }

      $data[implode('/', $path)] = $name->getName();
    }

    $this->view->set('breadcrumb', $data);
  }

  private function setLeftMenu($chunks){

    $menu = null;

    $current = array_pop($chunks); // get first element
    $parent = array_shift($chunks);

    if(!$parent){
      $parent = $current;
    }

    /**
     * @var Catalog $currentDoc
     */
    $parentDoc = $this->catalogRepo->findOneBy(array('alias'=>$parent));

    if(!$parentDoc){
      Manager::$Headers->error404();
      throw new SystemException('Каталог "'.$parent.'" не найден');
    }

    $menu = $parentDoc;

    $this->view->set('leftMenuCurrent', $current);
    $this->view->set('leftMenu', $this->treeHelper->nodeToArray($menu));
  }

  public function renderForHome(){

    $this->view->extendBy('shop_home_catalog');
  }

  public function sendCatalogToView(){

    $debug = $this->treeHelper->getTree();
    $this->view->set('catalog', $debug);
  }

  /**
   *
   */
  public function defaultEvent(){

    $this->view->extendBy('shop');

    $catalogPath = explode('/',$this->url->getParams('path'));

    $this->setBreadcrumb($catalogPath);
    $this->setLeftMenu($catalogPath);

    $this->view->set('currentSortBy', $this->get->get('sortBy'));

    //$categoryForSearch = Manager::$Common->translitDecode(array_pop($catalogPath));

    //
  }

}