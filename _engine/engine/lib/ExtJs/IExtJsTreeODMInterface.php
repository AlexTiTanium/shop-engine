<?php

namespace lib\ExtJs;

interface IExtJsTreeODMInterface {

  /**
   * Get id
   *
   * @return int $id
   */
  public function getId();

  /**
   * Set name
   *
   * @param string $name
   * @return IExtJsTreeODMInterface
   */
  public function setName($name);

  /**
   * Get name
   *
   * @return string $name
   */
  public function getName();

  /**
   * Set index
   *
   * @param int $index
   * @return IExtJsTreeODMInterface
   */
  public function setIndex($index);

  /**
   * Get index
   *
   * @return int $index
   */
  public function getIndex();

  /**
   * Add children
   *
   * @param IExtJsTreeODMInterface $children
   */
  public function addChildren(IExtJsTreeODMInterface $children);
  /**
   * Get children
   *
   * @return IExtJsTreeODMInterface[] $children
   */
  public function getChildren();

  /**
   * Set leaf
   *
   * @param boolean $leaf
   * @return IExtJsTreeODMInterface
   */
  public function setLeaf($leaf);

  /**
   * Get leaf
   *
   * @return boolean $leaf
   */
  public function getLeaf();

  /**
   * Set parentId
   *
   * @param string $parentId
   * @return IExtJsTreeODMInterface
   */
  public function setParentId($parentId);

  /**
   * Get parentId
   *
   * @return string $parentId
   */
  public function getParentId();

}