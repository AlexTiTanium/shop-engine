<?php

namespace Documents\Shop;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Documents\Shop\Products
 *
 * @ODM\Document(
 *     collection="shop_products",
 *     repositoryClass="models\ODM\Repositories\ShopProductsRepository",
 *     indexes={
 * @ODM\Index(keys={"dateAdd"="1"}, options={}),
 * @ODM\Index(keys={"catalog"="1"}, options={}),
 * @ODM\Index(keys={"price"="1"}, options={}),
 * @ODM\Index(keys={"searchIndex"="1"}, options={}),
 * @ODM\Index(keys={"filters"="1"}, options={}),
 * @ODM\Index(keys={"status"="1"}, options={})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class Products extends \lib\Doctrine\DoctrineModel {


  const STATUS_ACTIVE = 'active';
  const STATUS_ENDS = 'ends';
  const STATUS_NEW = 'new';
  const STATUS_PROMOTION = 'promotion';
  const STATUS_ENDED = 'ended';
  const STATUS_DISABLE = 'disable';
  const STATUS_COMING_SOON = 'coming_soon';

  /**
   * @var MongoId $id
   *
   * @ODM\Id(strategy="AUTO")
   */
  protected $id;

  /**
   * @var date $dateAdd
   *
   * @ODM\Field(name="dateAdd", type="date")
   */
  protected $dateAdd;

  /**
   * @var string $catalog
   *
   * @ODM\Field(name="catalog", type="string")
   */
  protected $catalog;

  /**
   * @var string $name
   *
   * @ODM\Field(name="name", type="string")
   */
  protected $name;

  /**
   * @var float $price
   *
   * @ODM\Field(name="price", type="float")
   */
  protected $price;

  /**
   * @var string $description
   *
   * @ODM\Field(name="description", type="string")
   */
  protected $description;

  /**
   * @var hash $searchIndex
   *
   * @ODM\Field(name="searchIndex", type="hash")
   */
  protected $searchIndex;

  /**
   * @var hash $attributes
   *
   * @ODM\Field(name="attributes", type="hash")
   */
  protected $attributes;

  /**
   * @var hash $filters
   *
   * @ODM\Field(name="filters", type="hash")
   */
  protected $filters;

  /**
   * @var string $status
   *
   * @ODM\Field(name="status", type="string")
   */
  protected $status;


  /**
   * Get id
   *
   * @return id $id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set dateAdd
   *
   * @param date $dateAdd
   * @return Products
   */
  public function setDateAdd($dateAdd) {
    $this->dateAdd = $dateAdd;
    return $this;
  }

  /**
   * Get dateAdd
   *
   * @return date $dateAdd
   */
  public function getDateAdd() {
    return $this->dateAdd;
  }

  /**
   * Set catalog
   *
   * @param string $catalog
   * @return Products
   */
  public function setCatalog($catalog) {
    $this->catalog = $catalog;
    return $this;
  }

  /**
   * Get catalog
   *
   * @return string $catalog
   */
  public function getCatalog() {
    return $this->catalog;
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Products
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get name
   *
   * @return string $name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set price
   *
   * @param float $price
   * @return Products
   */
  public function setPrice($price) {
    $this->price = $price;
    return $this;
  }

  /**
   * Get price
   *
   * @return float $price
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * Set description
   *
   * @param string $description
   * @return Products
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * Get description
   *
   * @return string $description
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set searchIndex
   *
   * @param hash $searchIndex
   * @return Products
   */
  public function setSearchIndex($searchIndex) {
    $this->searchIndex = $searchIndex;
    return $this;
  }

  /**
   * Get searchIndex
   *
   * @return hash $searchIndex
   */
  public function getSearchIndex() {
    return $this->searchIndex;
  }

  /**
   * Set attributes
   *
   * @param hash $attributes
   * @return Products
   */
  public function setAttributes($attributes) {
    $this->attributes = $attributes;
    return $this;
  }

  /**
   * Get attributes
   *
   * @return hash $attributes
   */
  public function getAttributes() {
    return $this->attributes;
  }

  /**
   * Set filters
   *
   * @param hash $filters
   * @return Products
   */
  public function setFilters($filters) {
    $this->filters = $filters;
    return $this;
  }

  /**
   * Get filters
   *
   * @return hash $filters
   */
  public function getFilters() {
    return $this->filters;
  }

  /**
   * Set status
   *
   * @param string $status
   * @return Products
   */
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  /**
   * Get status
   *
   * @return string $status
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @var boolean $new
   *
   * @ODM\Field(name="new", type="boolean")
   */
  protected $new;

  /**
   * @var boolean $promotion
   *
   * @ODM\Field(name="promotion", type="boolean")
   */
  protected $promotion;


  /**
   * Set new
   *
   * @param boolean $new
   * @return Products
   */
  public function setNew($new) {
    $this->new = $new;
    return $this;
  }

  /**
   * Get new
   *
   * @return boolean $new
   */
  public function getNew() {
    return $this->new;
  }

  /**
   * Set promotion
   *
   * @param boolean $promotion
   * @return Products
   */
  public function setPromotion($promotion) {
    $this->promotion = $promotion;
    return $this;
  }

  /**
   * Get promotion
   *
   * @return boolean $promotion
   */
  public function getPromotion() {
    return $this->promotion;
  }

  /**
   * @var hash $images
   *
   * @ODM\Field(name="images", type="hash")
   */
  protected $images;


  /**
   * Set images
   *
   * @param hash $images
   * @return Products
   */
  public function setImages($images) {
    $this->images = $images;
    return $this;
  }

  /**
   * Set images
   *
   * @param $imageName
   *
   * @return Products
   */
  public function addImage($imageName) {
    $this->images[] = $imageName;
    return $this;
  }

  /**
   * Get images
   *
   * @return hash $images
   */
  public function getImages() {
    return $this->images;
  }
    /**
     * @var int $count
     *
     * @ODM\Field(name="count", type="int")
     */
    protected $count;

    /**
     * @var string $marking
     *
     * @ODM\Field(name="marking", type="string")
     */
    protected $marking;


    /**
     * Set count
     *
     * @param int $count
     * @return Products
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Get count
     *
     * @return int $count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set marking
     *
     * @param string $marking
     * @return Products
     */
    public function setMarking($marking)
    {
        $this->marking = $marking;
        return $this;
    }

    /**
     * Get marking
     *
     * @return string $marking
     */
    public function getMarking()
    {
        return $this->marking;
    }
}
