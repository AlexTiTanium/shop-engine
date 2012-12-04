<?php

namespace lib\Doctrine;

use lib\Core\Manager;

class DoctrineSqlLogger implements \Doctrine\DBAL\Logging\SQLLogger  {

  /**
   * @var DoctrineLogNode[]
   */
  private $logger = array();

  /**
   * @var DoctrineLogNode
   */
  private $currentNode;

  /**
   * @var float
   */
  private $totalDbTime = 0.00;

  const OrmTimerQueryTime  = 'orm_timer_query_time';

  /**
   * Logs a SQL statement somewhere.
   *
   * @param string $sql The SQL to be executed.
   * @param array $params The SQL parameters.
   * @param array $types The SQL parameter types.
   * @return void
   */
  public function startQuery($sql, array $params = null, array $types = null){

    $this->currentNode = new DoctrineLogNode();

    $this->currentNode->query = $sql;
    $this->currentNode->params = $params;
    $this->currentNode->types = $types;
    $this->currentNode->time = false;

    $this->logger[] = $this->currentNode;

    Manager::$Timer->start(self::OrmTimerQueryTime);
  }

  /**
   * Mark the last started query as stopped. This can be used for timing of queries.
   *
   * @return void
   */
  public function stopQuery(){
    $this->totalDbTime += Manager::$Timer->getTimer(self::OrmTimerQueryTime);
    $this->currentNode->time = Manager::$Timer->getTimerFormatted(self::OrmTimerQueryTime);
  }

  /**
   * @return array|DoctrineLogNode[]
   */
  public function getLogger(){
    return $this->logger;
  }

  /**
   * @return string
   */
  public function getTotalDbTime(){
    return number_format($this->totalDbTime / 1000, 5) . ' Сек.';
  }
}

class DoctrineLogNode {
  public $query = '';
  public $params = array();
  public $types = array();
  public $time = '';
}