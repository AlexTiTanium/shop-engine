<?php

namespace lib\ExtJs;

use lib\Core\Data;
use lib\Doctrine\DoctrineModel;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 17.10.12
 * Time: 20:18
 * To change this template use File | Settings | File Templates.
 */
class GridHelperOdm {

  /**
   * @var \Doctrine\ODM\MongoDb\Query\Builder
   */
  private $qb;

  /**
   * @var \lib\Core\Data
   */
  private $params;

  /**
   * @param \Doctrine\ODM\MongoDb\Query\Builder $qb
   * @param \lib\Core\Data $params
   */
  public function __construct(\Doctrine\ODM\MongoDb\Query\Builder $qb, Data $params = null){

    $this->qb = $qb;
    $this->params = $params;
  }

  /**
   * @param bool $removeFromResult
   * @return array
   */
  public function getList($removeFromResult = false){

    $qb = $this->qb;
    $countQb = clone $this->qb;

    // Page limiting filter
    $this->page();

    // Sorting
    $this->sort();

    $collection = iterator_to_array($qb->getQuery()->execute(), false);

    // Page count
    $count = $countQb->count()->getQuery()->execute();

    $data = array_map(
      function(DoctrineModel $model) use($removeFromResult) {
        return $model->toFlatArray($removeFromResult);
      },
      $collection
    );

    return array('total'=>$count, 'data'=>$data);
  }

  /**
   * Setup paginator
   */
  private function page(){

    $qb = $this->qb;
    $data = $this->params;

    $qb->limit($data->getRequired('limit'));
    $qb->skip($data->getRequired('start'));
  }

  /**
   * Sorting filter
   */
  private function sort(){

    $qb = $this->qb;
    $data = $this->params;

    if(!$data->isExist('sort')){ return; }

    $json = $data->getJson('sort');

    $json->map(function(Data $data, $key) use($qb) {
      $qb->sort($data->getRequired('property'), $data->getRequiredOpt('direction', array('ASC', 'DESC')));
    });

  }

}
