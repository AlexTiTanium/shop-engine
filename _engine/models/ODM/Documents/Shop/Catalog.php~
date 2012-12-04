<?php

namespace Documents\Shop;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use lib\ExtJs\IExtJsTreeODMInterface;

/**
 * Documents\Shop\Catalog
 *
 * @ODM\Document(
 *     collection="shop_catalog",
 *     repositoryClass="models\ODM\Repositories\ShopCatalogRepository",
 *     indexes={
 *         @ODM\Index(keys={"nodeId"="1"}, options={"safe"="1"})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class Catalog extends \lib\Doctrine\DoctrineModel implements IExtJsTreeODMInterface
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ODM\Field(name="name", type="string")
     */
    protected $name;

    /**
     * @var int $index
     *
     * @ODM\Field(name="index", type="int")
     */
    protected $index;

    /**
     * @var boolean $leaf
     *
     * @ODM\Field(name="leaf", type="boolean")
     */
    protected $leaf;

    /**
     * @var string $parentId
     *
     * @ODM\Field(name="parentId", type="string")
     */
    protected $parentId;

    /**
     * @var \Documents\Shop\Catalog
     *
     * @ODM\ReferenceMany(targetDocument="Documents\Shop\Catalog")
     */
    protected $children = array();

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Catalog
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set index
     *
     * @param int $index
     * @return Catalog
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * Get index
     *
     * @return int $index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set leaf
     *
     * @param boolean $leaf
     * @return Catalog
     */
    public function setLeaf($leaf)
    {
        $this->leaf = $leaf;
        return $this;
    }

    /**
     * Get leaf
     *
     * @return boolean $leaf
     */
    public function getLeaf()
    {
        return $this->leaf;
    }

    /**
     * Set parentId
     *
     * @param string $parentId
     * @return Catalog
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Get parentId
     *
     * @return string $parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

  /**
   * Add children
   *
   * @param \Documents\Shop\Catalog|\lib\ExtJs\IExtJsTreeODMInterface $children
   *
   * @return void
   */
    public function addChildren(IExtJsTreeODMInterface $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection $children
     */
    public function getChildren()
    {
        return $this->children;
    }
    /**
     * @var string $alias
     *
     * @ODM\Field(name="alias", type="string")
     */
    protected $alias;


    /**
     * Set alias
     *
     * @param string $alias
     * @return Catalog
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Get alias
     *
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
