<?php

namespace lib\Core\Manager;

class ManagerTimers {

  /**
   * @var array
   */
  protected $startTime;

  /**
   * @var array
   */
  protected $results;

  public function __construct(){
    $this->startTime = array();
    $this->start();
  }

  /**
   * Возврящает текушее время в милисекундах
   *
   * @return int
   **/
  protected function getTime(){
    return microtime(1);
  }

  /**
   * Установить начала отсчёта
   *
   * @param string $timer имя таймера
   **/
  public function start($timer = 'default'){
    $this->startTime[$timer] = $this->getTime();
  }

  /**
   * Сколько прошло миллисекунд после начала отсчёта
   *
   * @param string $timer имя таймера
   *
   * @return float|int
   */
  public function getTimer($timer = 'default'){
    $time = $this->getTime();

    if(!isset($this->startTime[$timer])) {
      return 0.00;
    }

    return ($time - (float)$this->startTime[$timer]) * 1000;
  }

  /**
   * Сколько прошло секунд после начала отсчёта
   *
   * @param string $timer имя таймера
   *
   * @return string
   */
  public function getTimerFormatted($timer = 'default'){
    return number_format($this->getTimer($timer) / 1000, 5) . ' Сек.';
  }

  /**
   * Прибавляет прошедшее время к результату таймера $timer
   *
   * @param string $timer имя таймера
   * @param string $result имя результата, если не указывать, будет равно имени таймера
   **/
  public function appendResult($timer = 'default', $result = ""){
    if(!$result) {
      $result = $timer;
    }
    $this->results[$result] = (float)$this->results[$result] + $this->getTimer($timer);
  }

  /**
   * Устанавливает результат
   *
   * @param string $timer имя таймера
   * @param string $result имя результата, если не указывать, будет равно имени таймера
   **/
  public function setResult($timer = "default", $result = ""){
    if(!$result) {
      $result = $timer;
    }
    $this->results[$result] = $this->getTimer($timer);
  }

  /**
   * Возвращает рузельтат
   *
   * @param $result
   * @return array
   */
  public function getResult($result){
    return $this->results[$result];
  }

  /**
   * Возвращает массив с результатами
   *
   * @return array
   */
  public function getAllResults(){
    return $this->results;
  }
}