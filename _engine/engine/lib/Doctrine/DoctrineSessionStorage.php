<?php

namespace lib\Doctrine;

use lib\Session\ISessionStorage;

class DoctrineSessionStorage implements ISessionStorage {

  /**
   * @example Documents\User
   * @var string
   */
  private $userDocument = '';

  /**
   * @example Documents\Users\SessionStorage
   * @var string
   */
  private $storageDocument = '';

  private $cacheStorageNamespace = '_DoctrineSession_storage_';


  /**
   * @param $userDocument
   * @param $storageDocument
   */
  public function __construct($userDocument, $storageDocument){
    $this->userDocument = $userDocument;
    $this->storageDocument = $storageDocument;
    $this->cacheStorageNamespace = $storageDocument . $this->cacheStorageNamespace;
  }

  /**
   * @param $id
   * @internal param $id
   * @return \models\Interfaces\ISessionInterface
   */
  public function findSessionById($id){

    return DoctrineOdm::getRepository($this->storageDocument)->find($id);
  }

  /**
   * @param \models\Interfaces\ISessionInterface $sessionObject
   */
  public function delete($sessionObject){

    DoctrineOdm::createQueryBuilder($this->storageDocument)
      ->findAndRemove()
      ->field('_id')->equals($sessionObject->getId())
      ->getQuery()->execute();

    $cache = Doctrine::getCache();
    $idCache = $this->cacheStorageNamespace.$sessionObject->getId();

    $cache->delete($idCache);
  }

  /**
   * @param string $login
   * @return \models\Interfaces\IUserInterface
   */
  public function findUserByLogin($login){
    return DoctrineOdm::getRepository($this->userDocument)
      ->findOneBy(array('login'=>$login));
  }

  /**
   * @param string $sid
   */
  public function deleteSessionsBySid($sid){

    DoctrineOdm::createQueryBuilder($this->storageDocument)
      ->remove()
      ->field('sid')->equals($sid)
      ->getQuery()->execute();

  }

  /**
   * @param string $userId
   */
  public function deleteSessionsByUserId($userId){

    DoctrineOdm::createQueryBuilder($this->storageDocument)
      ->remove()
      ->field('user_id')->equals($userId)
      ->getQuery()->execute();

  }

  /**
   * @param \models\Interfaces\IUserInterface $user
   * @return \models\Interfaces\ISessionInterface
   */
  public function createSession($user){

    /**
     * @var \models\Interfaces\ISessionInterface $session
     */
    $session =  new $this->storageDocument();

    // TODO: not abstract
    $session->setUser($user);

    return $session;
  }

  /**
   * @param \models\Interfaces\ISessionInterface $session
   * @return mixed
   */
  public function insert($session){
    DoctrineOdm::persist($session);
    DoctrineOdm::flush();
  }

  /**
   * @param \models\Interfaces\ISessionInterface $session
   * @return mixed
   */
  public function update($session){

    $cache = Doctrine::getCache();
    $idCache = $this->cacheStorageNamespace.$session->getId();

    $cache->delete($idCache);

    DoctrineOdm::persist($session);
    DoctrineOdm::flush();
  }

  /**
   * @param \models\Interfaces\ISessionInterface $sessionObject
   * @return \models\Interfaces\IUserInterface
   */
  public function getUser($sessionObject){
    $data = DoctrineOdm::getRepository($this->userDocument)->find($sessionObject->getUserId());
    return $data;
  }

  /**
   * @param int $timestamp
   * @return mixed
   */
  public function deleteOlder($timestamp){

    DoctrineOdm::createQueryBuilder($this->storageDocument)
      ->remove()
      ->field('exp_session')->lt($timestamp)
      ->getQuery()->execute();
  }

  /**
   * @param int $timestamp
   * @return mixed
   */
  public function offlineOlder($timestamp){

    DoctrineOdm::createQueryBuilder($this->storageDocument)
      ->field('now')->set('Offline')
      ->field('exp_online')->lt($timestamp)
      ->getQuery()->execute();
  }

}
