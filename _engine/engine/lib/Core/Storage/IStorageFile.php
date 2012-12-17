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
  public function getExtension();

  /**
   * @param $path
   *
   * @return string - new file name
   */
  public function copyTo($path);
}
