<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace lib\Pagination;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\ViewInterface;
use lib\Core\UrlObject;

/**
 * DefaultInterface.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
class DefaultHtmlView implements ViewInterface {

  private $cssClassCurrent = 'red-35px-height red-button';
  private $cssClassDefault = 'grey-button';
  private $cssClassToAll = 'button ui-button ui-button-text-only ui-widget ui-corner-all';
  private $cssClassDots= 'dots';

  /**
   * @var string $pages
   */
  private $pages;

  /**
   * @var \Pagerfanta\PagerfantaInterface
   */
  private $paginator;

  /**
   * {@inheritdoc}
   */
  public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = array()) {
    $options = array_merge(array(
      'proximity' => 2,
    ), $options);

    $currentPage = $pagerfanta->getCurrentPage();

    $startPage = $currentPage - $options['proximity'];
    $endPage = $currentPage + $options['proximity'];

    if ($startPage < 1) {
      $endPage = min($endPage + (1 - $startPage), $pagerfanta->getNbPages());
      $startPage = 1;
    }
    if ($endPage > $pagerfanta->getNbPages()) {
      $startPage = max($startPage - ($endPage - $pagerfanta->getNbPages()), 1);
      $endPage = $pagerfanta->getNbPages();
    }

    $pages = array();

    // pages
    for ($page = $startPage; $page <= $endPage; $page++) {
      if ($page == $currentPage) {
        $pages[] = sprintf('<span class="%s %s"><span class="ui-button-text">%s</span></span>', $this->cssClassCurrent, $this->cssClassToAll, $page);
      } else {
        $pages[] = array($page, $page);
      }
    }

    // last
    if ($pagerfanta->getNbPages() > $endPage) {
      if ($pagerfanta->getNbPages() > ($endPage + 1)) {
        if ($pagerfanta->getNbPages() > ($endPage + 2)) {
          $pages[] = sprintf('<span class="%s">...</span>', $this->cssClassDots);
        } else {
          $pages[] = array($endPage + 1, $endPage + 1);
        }
      }

      $pages[] = array($pagerfanta->getNbPages(), $pagerfanta->getNbPages());
    }

    /**
     * @var UrlObject $routeGenerator
     */
    $htmlPages = '';
    foreach ($pages as $page) {
      if (!is_string($page)) {
        $onePage = '<a class="%s %s" href="' . $routeGenerator->getString(array('page'=>$page[0])) . '"><span class="ui-button-text">' . $page[1] . '</span></a>';
        $htmlPages .= sprintf($onePage, $this->cssClassDefault, $this->cssClassToAll);
      }else{
        $htmlPages .= $page;
      }
    }

    $this->paginator = $pagerfanta;
    $this->pages = $htmlPages;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'default';
  }

  /**
   * @return array
   */
  public function getPages() {
    return $this->pages;
  }

  /**
   * @return \Pagerfanta\PagerfantaInterface
   */
  public function getPaginator() {
    return $this->paginator;
  }

  /**
   * @return int
   */
  public function getMaxPerPage(){
    return $this->paginator->getMaxPerPage();
  }

  /**
   * @return int
   */
  public function getCurrentPage(){
    return$this->paginator->getCurrentPage();
  }

  /**
   * @return array|\Traversable The results.
   */
  public function getResult(){
    return $this->paginator->getCurrentPageResults();
  }

  /**
   * @return int
   */
  public function getNbResults(){
    return $this->paginator->getNbResults();
  }

  /**
   * @return int
   */
  public function getNbPages(){
    return $this->paginator->getNbPages();
  }

  /**
   * @return bool
   */
  public function haveToPaginate(){
    return $this->paginator->haveToPaginate();
  }

  /**
   * @return bool
   */
  public function hasPreviousPage(){
    return $this->paginator->hasPreviousPage();
  }

  /**
   * @return int
   */
  public function getPreviousPage(){
    return $this->paginator->getPreviousPage();
  }

  /**
   * @return bool
   */
  public function hasNextPage(){
    return $this->paginator->hasNextPage();
  }

  /**
   * @return int
   */
  public function getNextPage(){
    return $this->paginator->getNextPage();
  }
}