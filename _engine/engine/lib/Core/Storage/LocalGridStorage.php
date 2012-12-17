<?php

namespace lib\Core\Storage;
use lib\Core\DirCommander;
use lib\EngineExceptions\SystemException;
use lib\Core\DirCommander\Adapters\LocalDirCommanderAdapter;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 10.12.12
 * Time: 18:34
 * To change this template use File | Settings | File Templates.
 */
class LocalGridStorage implements IStorage {

  /**
   * Get path to storage
   *
   * @return string
   */
  public function getPath(){
    return PATH_PUBLIC_FILES_STORAGE;
  }

  /**
   * Write file to storageId folder
   *
   * @param $storeId
   * @param IStorageFile $file
   *
   * @throws \lib\EngineExceptions\SystemException
   * @return string - new file name
   */
  public function write($storeId, IStorageFile $file){

    $path = $this->getPath();

    $newFileName = md5($file->getName());
    $newFolderHash = substr($newFileName, 0, 2);

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $path);

    if(!$dc->isExist($storeId)){
      $dc->makeDir($storeId);
    }

    $dc->cd($storeId);

    if(!$dc->isExist($newFolderHash)){
      $dc->makeDir($newFolderHash);
    }

    if(!$dc->isWritable($newFolderHash)){
      throw new SystemException('Folder "'.$newFolderHash.'" not writable');
    }

    $dc->cd($newFolderHash);

    return $file->copyTo($dc->getCurrentPath());
  }
}
