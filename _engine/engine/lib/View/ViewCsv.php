<?php

namespace lib\View;

use lib\Core\Manager;

class ViewCsv implements IView {

  private $headers = array();
  private $rows = array();

  public function headers(array $headers){

    foreach($headers as &$value) {
      $value = iconv('utf-8', 'windows-1251', $value);
    }

    $this->headers = $headers;
  }

  /**
   * @param $rows
   * @param bool $val - not used!!
   */
  public function set($rows, $val = false){

    foreach($rows as &$array) {
      foreach($array as &$value) {
        $value = iconv('utf-8', 'windows-1251', $value);
      }
    }
    $this->rows = $rows;
  }

  public function toString(){

    $tmp = array();

    $tmp[] = $this->headers;
    $tmp[] = '';

    foreach($this->rows as $value) {
      $tmp[] = str_replace('.', ',', $value);
    }

    return Manager::$Common->arrayToCsv($tmp);
  }

}