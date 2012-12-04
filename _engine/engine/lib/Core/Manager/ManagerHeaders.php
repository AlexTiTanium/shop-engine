<?php

namespace lib\Core\Manager;

class ManagerHeaders {

  /**
   * Отправить заголовки для правельной работы юникода с русскими символами
   *
   * @access Public
   **/
  public function setUnicode(){
    mb_language('uni');
    mb_internal_encoding('UTF-8');
  }

  /**
   * Отправить заголовки для запрета кеширования
   *
   * @access Public
   **/
  public function noCache(){
    Header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    Header('Cache-Control: no-cache, must-revalidate');
    Header('Pragma: no-cache');
    Header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
  }

  /**
   * Отправить заголовок с типом контента
   *
   * @param string $type - Тип контента
   * @param string $encoding
   * @example application/xml, text/html, application/x-bittorrent, text/plain
   * @access Public
   **/
  public function contentType($type, $encoding = 'utf-8'){
    Header('Content-type: ' . $type . '; charset=' . $encoding);
  }

  /**
   * Код ответа скрипта
   *
   * @param string $text - Обработан успешно = OK или код ошибки
   * @access Public
   **/
  public function ort($text){
    Header('Ort: ' . $text);
  }

  /**
   * Ошибка сервера
   *
   * @access Public
   **/
  public function error500(){
    Header('HTTP/1.1 500 Internal Server Error');
    exit();
  }

  /**
   * Not found
   */
  public function error404(){
    Header('HTTP/1.0 404 Not Found');
  }

}