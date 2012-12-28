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
   * Return grid folder name
   *
   * @param $fileIdWithExtension
   * @return string
   */
  private function getStorageFolderName($fileIdWithExtension){

    return substr(md5($fileIdWithExtension), 0, 2);
  }

  /**
   * Write file to storageId folder
   *
   * @param IStorageFile $file
   *
   * @throws SystemException
   * @return string - new file name
   */
  public function save(IStorageFile $file){

    $newFolderHash = $this->getStorageFolderName($file->getName().'.'.$file->getExtension());

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $this->getPath());

    if(!$dc->isExist($file->getStoreId())){
      $dc->makeDir($file->getStoreId());
    }

    $dc->cd($file->getStoreId());

    if(!$dc->isExist($newFolderHash)){
      $dc->makeDir($newFolderHash);
    }

    if(!$dc->isWritable($newFolderHash)){
      throw new SystemException('Folder "'.$newFolderHash.'" not writable');
    }

    $dc->cd($newFolderHash);

    if($file instanceof UploadedFile){
      return $file->copyTo($dc->getCurrentPath());
    }

    $fileName = $file->getPrefix().$file->getName().'.'.$file->getExtension();

    $dc->makeFile($fileName);

    return $fileName;
  }

  /**
   * @param string $storeId
   * @param string $fileWithExtensionId
   * @return IStorageFile|void
   * @throws SystemException
   */
  public function get($storeId, $fileWithExtensionId){

    $folder = $this->getStorageFolderName($fileWithExtensionId);

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $this->getPath());

    if(!$dc->isExist($storeId)){
      throw new SystemException('Bad storage id');
    }

    $dc->cd($storeId);

    if(!$dc->isExist($folder)){
      throw new SystemException('Bad file id or folder hash');
    }

    $dc->cd($folder);

    if(!$dc->isExist($fileWithExtensionId)){
      throw new SystemException('Not found');
    }

    return new StorageFile($storeId, $fileWithExtensionId, $this);
  }

  /**
   * @param IStorageFile $file
   * @param mixed $content
   * @throws SystemException
   * @return void
   */
  public function write(IStorageFile $file, $content) {

    $folder = $this->getStorageFolderName($file->getName().'.'.$file->getExtension());

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $this->getPath());

    if(!$dc->isExist($file->getStoreId())){
      throw new SystemException('Bad storage id');
    }

    $dc->cd($file->getStoreId());

    if(!$dc->isExist($folder)){
      throw new SystemException('Bad file id or folder hash');
    }

    $dc->cd($folder);

    $fileName = $file->getPrefix().$file->getName().'.'.$file->getExtension();

    /*
    if(!$dc->isExist($fileName)){
      throw new SystemException('File '.$fileName.' not found in: '.$dc->getCurrentPath());
    }*/

    $dc->makeFile($fileName, $content);
  }

  /**
   * @param IStorageFile $file
   * @throws SystemException
   * @return mixed
   */
  public function read(IStorageFile $file) {

    $folder = $this->getStorageFolderName($file->getName().'.'.$file->getExtension());

    $dc = new DirCommander(new LocalDirCommanderAdapter(), $this->getPath());

    if(!$dc->isExist($file->getStoreId())){
      throw new SystemException('Bad storage id');
    }

    $dc->cd($file->getStoreId());

    if(!$dc->isExist($folder)){
      throw new SystemException('Bad file id or folder hash');
    }

    $dc->cd($folder);

    $fileName = $file->getPrefix().$file->getName().'.'.$file->getExtension();

    if(!$dc->isExist($fileName)){
      throw new SystemException('File '.$fileName.' not found in: '.$dc->getCurrentPath());
    }

    return $dc->getContent($fileName);
  }

}
