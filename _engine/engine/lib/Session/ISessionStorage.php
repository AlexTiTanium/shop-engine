<?php

namespace lib\Session;

interface ISessionStorage {

  /**
   * @abstract
   * @param string $id
   * @return \models\Interfaces\ISessionInterface
   */
  public function findSessionById($id);

  public function delete($sessionObject);

  /**
   * @abstract
   * @param string $login
   * @return \models\Interfaces\IUserInterface
   */
  public function findUserByLogin($login);

  public function deleteSessionsBySid($sid);

  public function deleteSessionsByUserId($userId);

  /**
   * @abstract
   * @param \models\Interfaces\IUserInterface $user
   * @return \models\Interfaces\ISessionInterface
   */
  public function createSession($user);

  /**
   * @abstract
   * @param int $timestamp
   * @return mixed
   */
  public function deleteOlder($timestamp);

  /**
   * @abstract
   * @param int $timestamp
   * @return mixed
   */
  public function offlineOlder($timestamp);

  /**
   * @abstract
   * @param \models\Interfaces\ISessionInterface $session
   * @return mixed
   */
  public function insert($session);

  /**
   * @abstract
   * @param \models\Interfaces\ISessionInterface $session
   * @return mixed
   */
  public function update($session);

  /**
   * @abstract
   * @param \models\Interfaces\ISessionInterface $sessionObject
   * @return \models\Interfaces\IUserInterface
   */
  public function getUser($sessionObject);

}
