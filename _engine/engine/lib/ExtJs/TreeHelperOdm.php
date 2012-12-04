<?php

namespace lib\ExtJs;

use lib\Core\Data;
use lib\Debugger\Debugger;
use lib\EngineExceptions\SystemException;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 17.10.12
 * Time: 20:18
 * To change this template use File | Settings | File Templates.
 */
class TreeHelperOdm {

  /**
   * @var string $leafIconCls
   * @example ux-icon-package-link
   */
  private $leafIconCls;

  /**
   * @var \Doctrine\ODM\MongoDB\DocumentRepository $repository
   */
  private $repository;

  private $onUpdate;
  private $onNodeCreate;

  /**
   * @param \Doctrine\ODM\MongoDB\DocumentRepository $repository
   */
  public function __construct(DocumentRepository $repository){

    $this->repository = $repository;
  }

  public function onUpdate($callable){
    $this->onUpdate = $callable;
  }

  public function onNodeCreate($callable){
    $this->onNodeCreate = $callable;
  }

  /**
   * @param string $leafIconCls
   */
  public function setLeafIconCls($leafIconCls){
    $this->leafIconCls = $leafIconCls;
  }

  /**
   * @param array|null $documentsCollection
   *
   * @return array
   */
  public function getTree($documentsCollection = null){

    $tree = array();

    if(!$documentsCollection){
      $documentsCollection = $this->repository->findBy(array('parentId'=>'root'));
    }

    /**
     * @var IExtJsTreeODMInterface $node
     */
    foreach($documentsCollection as $node){
      array_push($tree, $this->nodeToArray($node));
    }

    $this->nodeSort($tree);

    return $tree;
  }

  /**
   * @param \lib\Core\Data $data
   *
   * @return array
   */
  public function add(Data $data){

    $node = $this->fillModel($this->getDocumentInstance(), $data);

    $this->addToTree($data->get('parentId', false), $node);

    return $this->nodeToArray($node);
  }

  /**
   * @param \lib\Core\Data $data
   * @return array
   */
  public function update(Data $data){

    if(!$data->isList()){
      return $this->updateOneNode($data);
    }

    $result = array();
    $me = $this;

    $data->map(function(Data $data) use(&$result, $me) {
      array_push($result, $me->updateOneNode($data));
    });

    return $result;
  }

  /**
   * @param \lib\Core\Data $data
   *
   * @return array
   */
  public function updateOneNode(Data $data){

    $id = $data->getRequired('id');
    $parentId = $data->get('parentId', 'root');

    /**
     * @var IExtJsTreeODMInterface $node
     */
    $node = $this->fillModel($this->repository->find($id), $data);

    if($node->getParentId() != $parentId){
      $this->move($node, $parentId);
    }

    $this->repository->getDocumentManager()->persist($node);
    $this->repository->getDocumentManager()->flush();

    return $this->nodeToArray($node);
  }

  /**
   * @param \lib\Core\Data $data
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  public function destroy(Data $data){

    $id = $data->getRequired('id');

    /**
      * @var IExtJsTreeODMInterface $node
      */
    $node = $this->repository->find($id);
    if(!$node){ throw new SystemException('Node not found'); }

    if($node->getParentId() != false and $node->getParentId() != 'root'){

      /**
       * @var IExtJsTreeODMInterface $parentNode
       * @var IExtJsTreeODMInterface $child
       */
      $parentNode = $this->repository->find($node->getParentId());
      if(!$parentNode){ throw new SystemException('Parent node not found'); }

      $children = $parentNode->getChildren();

      foreach($children as $key=>$child){
        if($child->getId() == $node->getId()){  unset($children[$key]); break; }
      }

      $this->repository->getDocumentManager()->persist($parentNode);
    }

    $this->repository->getDocumentManager()->remove($node);
    $this->repository->getDocumentManager()->flush();
  }

  /**
   * @param IExtJsTreeODMInterface $node
   * @param $newParentId
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  private function move(IExtJsTreeODMInterface $node, $newParentId){

    $oldParentId = $node->getParentId();

    $this->addToTree($newParentId, $node);

    if($oldParentId == false or $oldParentId == 'root'){ return; }

    /**
     * @var IExtJsTreeODMInterface $oldParentNode
     * @var IExtJsTreeODMInterface $child
     */
    $oldParentNode = $this->repository->find($oldParentId);
    if(!$oldParentNode){ throw new SystemException('Parent node not found'); }

    $children = $oldParentNode->getChildren();

    foreach($children as $key=>$child){
      if($child->getId() == $node->getId()){  unset($children[$key]); break; }
    }

    $this->repository->getDocumentManager()->persist($oldParentNode);
  }

  /**
   * @param IExtJsTreeODMInterface $model
   * @param \lib\Core\Data $data
   *
   * @return IExtJsTreeODMInterface
   */
  private function fillModel(IExtJsTreeODMInterface $model, Data $data){

    $name = $data->getRequired('name');
    $leaf = $data->getBool('leaf');
    $index = $data->get('index', 0);

    $model->setName($name);
    $model->setIndex($index);
    $model->setLeaf($leaf);

    if($this->onUpdate){
      $callable = $this->onUpdate;
      $callable($model, $data);
    }

    return $model;
  }

  /**
   * @return IExtJsTreeODMInterface
   * @return object
   */
  private function getDocumentInstance(){

    return $this->repository->getClassMetadata()->newInstance();
  }

  /**
   * @param IExtJsTreeODMInterface $node
   *
   * @return array
   */
  public function nodeToArray(IExtJsTreeODMInterface $node){

    $tree = array();

    $tree['id'] = $node->getId();
    $tree['name'] = $node->getName();
    $tree['leaf'] = $node->getLeaf();
    $tree['index'] = $node->getIndex();

    if($node->getLeaf() and $this->leafIconCls){
      $tree['iconCls'] = $this->leafIconCls;
    }

    if($this->onNodeCreate){
      $callable = $this->onNodeCreate;
      $callable($node, $tree);
    }

    $tree['children'] = array();

    if($node->getChildren() and $node->getChildren()->count()){

      foreach($node->getChildren() as $child){
        array_push($tree['children'], $this->nodeToArray($child));
      }

      $this->nodeSort($tree['children']);
    }

    return $tree;
  }

  /**
   * @param int|bool $parentId
   * @param IExtJsTreeODMInterface $node
   *
   * @throws \lib\EngineExceptions\SystemException
   */
  private function addToTree($parentId, IExtJsTreeODMInterface $node){

    $toPersistsDocuments = null;

    if($parentId == 'root' or $parentId == false){
      $node->setParentId('root');
      $toPersistsDocuments = $node;
    }else{

      /**
       * @var IExtJsTreeODMInterface $parentDocument
       */
      $parentDocument = $this->repository->find($parentId);
      if(!$parentDocument){ throw new SystemException('Parent node not found'); }

      $this->repository->getDocumentManager()->persist($node);

      $node->setParentId($parentId);
      $parentDocument->addChildren($node);

      $toPersistsDocuments = $parentDocument;
    }

    $this->repository->getDocumentManager()->persist($toPersistsDocuments);
    $this->repository->getDocumentManager()->flush();
  }

  private function nodeSort(&$nodeArray){

    usort($nodeArray, function($a, $b){
      return $a['index'] - $b['index'];
    });

    //Debugger::log($nodeArray);
  }

}