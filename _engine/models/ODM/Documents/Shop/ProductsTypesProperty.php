<?php

namespace Documents\Shop;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Documents\Shop\ProductsTypesProperty
 *
 * @ODM\Document(
 *     collection="shop_products_types_properties",
 *     repositoryClass="models\ODM\Repositories\ShopProductsTypesPropertyRepository",
 *     indexes={
 *         @ODM\Index(keys={"nodeId"="1"}, options={"safe"="1"})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class ProductsTypesProperty extends \lib\Doctrine\DoctrineModel
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nodeId
     *
     * @ODM\Field(name="nodeId", type="string")
     */
    protected $nodeId;

    /**
     * @var string $name
     *
     * @ODM\Field(name="name", type="string")
     */
    protected $name;

    /**
     * @var string $type
     *
     * @ODM\Field(name="type", type="string")
     */
    protected $type;

    /**
     * @var int $index
     *
     * @ODM\Field(name="index", type="int")
     */
    protected $index;

    /**
     * @var hash $attribute
     *
     * @ODM\Field(name="attribute", type="hash")
     */
    protected $attribute = array();

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
     * Set nodeId
     *
     * @param string $nodeId
     * @return ProductsTypesProperty
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    /**
     * Get nodeId
     *
     * @return string $nodeId
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ProductsTypesProperty
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
     * Set type
     *
     * @param string $type
     * @return ProductsTypesProperty
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set index
     *
     * @param int $index
     * @return ProductsTypesProperty
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
   * Set attribute
   *
   * @param $key
   * @param hash $attribute
   *
   * @return ProductsTypesProperty
   */
    public function setAttribute($key, $attribute)
    {
        $this->attribute[$key] = $attribute;
        return $this;
    }

  /**
   * Get attribute
   *
   * @param $key
   *
   * @return hash $attribute
   */
    public function getAttribute($key)
    {
        return $this->attribute[$key];
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
     * @return ProductsTypesProperty
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
