<?php

namespace lib\Core\Storage;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 10.12.12
 * Time: 23:28
 * To change this template use File | Settings | File Templates.
 */
interface IStorageFile {

  /**
   * @return string
   */
  public function getName();

  /**
   * @return string
   */
  public function getStoreId();

  /**
   * @return string
   */
  public function getPrefix();

  /**
   * @return string
   */
  public function getExtension();

  /**
   * @param string $content
   */
  public function write($content);

  /**
   * @return string
   */
  public function read();

  /**
   * @param $prefix
   * @return void
   */
  public function setPrefix($prefix);
}
