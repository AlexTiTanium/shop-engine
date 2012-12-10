<?php

namespace lib\Core\Storage;
use lib\Core\DirCommander;
use lib\Core\DirCommander\Adapters\LocalDirCommanderAdapter;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 10.12.12
 * Time: 18:34
 * To change this template use File | Settings | File Templates.
 */
class LocalGridStorage implements IStorage {

  public function getPath(){
    return PATH_PUBLIC_FILES_STORE;
  }

  public function write($storeId, IStorageFile $file){

    $path = $this->getPath();

    $newFileName = $file->getName();

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $path);
    if(!$dc->isExist($storeId)){
      $dc->makeDir($storeId);
    }

    $dc->cd($storeId);

  }
}
