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
   * @param IStorageFile $file
   *
   * @return string - new file name
   */
  public function save(IStorageFile $file);

  /**
   * @param string $storeId
   * @param string $fileIdWithExtension
   *
   * @return IStorageFile $file
   */
  public function get($storeId, $fileIdWithExtension);

  /**
   * @param IStorageFile $file
   * @param mixed $content
   * @return void
   */
  public function write(IStorageFile $file, $content);

  /**
   * @param IStorageFile $file
   * @return mixed
   */
  public function read(IStorageFile $file);

}
