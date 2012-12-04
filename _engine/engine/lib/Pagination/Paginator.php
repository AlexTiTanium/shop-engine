<?php

namespace lib\Pagination;

use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use lib\Core\UrlObject;
use Pagerfanta\Pagerfanta;

class Paginator {

  /**
   * @var \Pagerfanta\Adapter\DoctrineODMMongoDBAdapter
   */
  private $adapter;

  /**
   * @var \Pagerfanta\Pagerfanta $pagerfanta
   */
  private $pagerfanta;

  /**
   * @var \lib\Core\UrlObject $urlObject
   */
  private $urlObject;

  public function __construct($qb, UrlObject $url, $maxOnPage = 10){

    if($qb instanceof \Doctrine\ODM\MongoDB\Query\Builder){
      $this->adapter = new DoctrineODMMongoDBAdapter($qb);
    }

    if($qb instanceof \Doctrine\ORM\QueryBuilder){
      $this->adapter = new DoctrineORMAdapter($qb);
    }

    $this->pagerfanta = new Pagerfanta($this->adapter);

    $this->pagerfanta->setMaxPerPage($maxOnPage);
    $this->pagerfanta->setCurrentPage($url->page);
    $this->urlObject = $url;
  }

  public function getView(){
    $view = new DefaultHtmlView();

    return $view->render($this->pagerfanta, $this->urlObject);
  }

}
