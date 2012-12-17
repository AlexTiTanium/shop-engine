<?php

namespace lib\Core\Storage;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 10.12.12
 * Time: 18:33
 * To change this template use File | Settings | File Templates.
 */
interface IStorage {

  /**
   * @param string $storeId
   * @param IStorageFile $file
   *
   * @return string - new file name
   */
  public function write($storeId, IStorageFile $file);
}
