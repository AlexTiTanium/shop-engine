<?php

namespace lib\Xml;

use \SimpleXMLElement;
use \DOMDocument;

class Xml extends SimpleXMLElement {

  /**
   * Добавить объект
   *
   * @param SimpleXMLElement $xmlTree
   * @param boolean $root
   * @return Xml
   **/
  public function addXmlObject(SimpleXMLElement $xmlTree, $root = false){
    if($root) {
      /**
       * @var Xml $child
       */
      $child = $this->addChild($xmlTree->getName());
      foreach($xmlTree->attributes() as $k => $v) {
        $child->addAttribute($k, $v);
      }
      $child->addXmlObject($xmlTree);
    } else {
      /**
       * @var Xml $childTree
       */
      foreach($xmlTree as $childName => $childTree) {

        $child = $this->addCData($childName, (string)$childTree);
        foreach($childTree->attributes() as $k => $v) {
          $child->addAttribute($k, $v);
        }
        $child->addXmlObject($childTree->children());
      }
    }

    return $this;
  }

  /**
   * Добавить СDATA объект
   *
   * @param string $nodeName
   * @param string $cdata_text
   * @return Xml
   **/
  public function addCData($nodeName, $cdata_text){

    $node = $this->addChild($nodeName);

    if(!empty($cdata_text)) {
      $node = dom_import_simplexml($node);
      /**
       * @var DOMDocument $ownerDoc
       */
      $ownerDoc = $node->ownerDocument;
      $node->appendChild($ownerDoc->createCDATASection($cdata_text));
    }

    return $this;
  }

  /**
   * @param $name
   * @param null $value
   * @param null $namespace
   * @return SimpleXMLElement
   */
  public function addChild($name, $value = null, $namespace = null){
    return parent::addChild(htmlspecialchars($name), htmlspecialchars($value), $namespace);
  }

  /**
   * Вернуть Xml
   *
   * @return string
   */
  public function toString(){
    return (string)$this->asXML();
  }

  /**
   * Добавить массив
   *
   * @param $array
   * @param Xml SimpleXMLElement
   * @internal param string $rootNodeName
   * @return void
   */
  function addArray($array, &$node = null){
    if($node == null) {
      $node = $this;
    }
    foreach($array as $key => $value) {
      if(is_array($value)) {
        if(!is_numeric($key)) {
          $subNode = $node->addChild("$key");
          $this->addArray($value, $subNode);
        } else {
          $subNode = $node->addChild("item");
          $this->addArray($value, $subNode);
        }
      } else {
        if(!is_numeric($key)) {
          $node->addChild("$key", "$value");
        } else {
          $node->addChild("item", "$value");
        }
      }
    }
  }

  /**
   * Добавить массив
   *
   * @param array $options
   * @param array  $data
   * @param null $currentNode
   * @internal param string $rootNodeName 'rootName'=>'root','arrayItem'=>'item','array'=>'items'
   * @return void
   */
  public function addTree($options = array('rootName', 'arrayItem', 'array'), $data, $currentNode = null){

    if(!$options or !is_array($options)) {
      $options = array('rootName' => 'root', 'arrayItem' => 'item', 'array' => 'items');
    }
    if($currentNode == null) {
      $currentNode = $this->addChild($options['rootName']);
    } else {
      if(isset($data[0])) {
        $keyName = $options['array'];
      } else {
        $keyName = $options['arrayItem'];
      }

      $currentNode = $currentNode->addChild($keyName);
    }

    if(empty($data) or !is_array($data)) {
      return;
    }
    foreach($data as $keyNode => $valNode) {

      if(is_array($valNode) and !empty($valNode)) {
        $this->AddTree($options, $valNode, $currentNode);
      } else {
        if($keyNode !== 'level'
          and $keyNode !== 'id'
            and $keyNode !== 'root_id'
              and $keyNode !== 'lft'
                and $keyNode !== 'rgt'
        ) {
          if(!empty($valNode)) {
            $currentNode->addChild($keyNode, $valNode);
          }
        }
      }
    }
  }

  /**
   * Добавить элемент c атрибутами
   *
   * @param string $name
   * @param array  $attributes
   * @param string|boolean $value
   * @return Xml
   */
  public function addChildWithAttribute($name, $attributes, $value = false){

    $element = $this->addChild($name, $value);
    foreach($attributes as $key => $val) {
      $element->addAttribute($key, $val);
    }

    return $this;
  }

}