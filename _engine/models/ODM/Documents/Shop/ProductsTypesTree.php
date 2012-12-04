<?php

namespace Documents\Shop;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use lib\ExtJs\IExtJsTreeODMInterface;

/**
 * Documents\Shop\ProductsTypesTree
 *
 * @ODM\Document(
 *     collection="shop_products_types_tree",
 *     repositoryClass="models\ODM\Repositories\ShopProductsTypesTreeRepository",
 *     indexes={
 *         @ODM\Index(keys={"parentId"="1"}, options={"safe"="1"}),
 *         @ODM\Index(keys={"children"="1"}, options={"safe"="1"})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class ProductsTypesTree extends \lib\Doctrine\DoctrineModel implements IExtJsTreeODMInterface
{
    /**
     * @var \MongoId $id
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
     * @var \Documents\Shop\ProductsTypesTree
     *
     * @ODM\ReferenceMany(targetDocument="Documents\Shop\ProductsTypesTree")
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
     * @return ProductsTypesTree
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
     * @return ProductsTypesTree
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
     * @return ProductsTypesTree
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
     * @return ProductsTypesTree
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
   * @param \Documents\Shop\ProductsTypesTree|\lib\ExtJs\IExtJsTreeODMInterface $children
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
}
