<?php

use lib\Core\Events;
use lib\Core\Manager;
use lib\EngineExceptions\SystemException;
use lib\ExtJs\GridHelperOdm;
use Documents\Shop\ProductsTypesProperty;
use models\ODM\Repositories\ShopProductsTypesPropertyRepository;
use lib\Debugger\Debugger;

class ShopProductPropertiesListener extends Events {

  /**
   * @var models\ODM\Repositories\ShopProductsTypesPropertyRepository
   */
  private $propertiesRepository;

  protected function setUp(){

    $this->propertiesRepository = ShopProductsTypesPropertyRepository::getRepository();
  }

  public function update(){

    $data = $this->post->getJsonRequest('data');

    /**
     * @var ProductsTypesProperty $property
     */
    $property = $this->propertiesRepository->find($data->getRequired('id'));

    $property->setName($data->getRequired('name'));
    $property->setType($data->getRequired('type'));

    $property->setAlias(Manager::$Common->translitEncode($data->get('name')));

    if($data->isExist('attribute')){
      foreach($data->get('attribute', array()) as $key=>$value){
        $property->setAttribute($key, $value);
      }
    }

    $this->propertiesRepository->update($property);

    $this->view->set('data', $property->toFlatArray());
  }

  public function create(){

    $data = $this->post->getJsonRequest('data', $this->getConfig('propertyData'));

    $property = new ProductsTypesProperty();

    $property->setName($data->get('name'));
    $property->setNodeId($data->get('nodeId'));
    $property->setType($data->get('type'));
    $property->setAlias(Manager::$Common->translitEncode($data->get('name')));

    $this->propertiesRepository->add($property);

    $this->view->set('data', $property->toFlatArray());
  }

  public function destroy(){

    $data = $this->post->getJsonRequest('data');

    /**
     * @var ProductsTypesProperty $property
     */
    $property = $this->propertiesRepository->find($data->getRequired('id'));
    if(!$property){ throw new SystemException('Record not found'); }

    $this->propertiesRepository->delete($property);
  }

  /**
   *
   */
  public function defaultEvent(){

    $propertiesQb = $this->propertiesRepository->createQueryBuilder();

    $propertiesQb->field('nodeId')->equals($this->get->getRequired('nodeId'));

    $grid = new GridHelperOdm($propertiesQb, $this->get);
    $this->view->set($grid->getList());
  }
}