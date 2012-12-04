<?php

namespace models\Interfaces;

interface IUserInterface {

  /**
   * @abstract
   * @param string $login
   */
  public function setLogin($login);

  /**
   * @abstract
   * @param string $cryptedPassword
   */
  public function setPassword($cryptedPassword);

  /**
   * @abstract
   * @param string $email
   */
  public function setEmail($email);

  /**
   * @abstract
   * @param string $hash
   */
  public function setActivationHash($hash);

  /**
   * @abstract
   * @param string $dateTime
   */
  public function setDate($dateTime);

  /**
   * @abstract
   * @param bool $bool
   */
  public function setIsActivated($bool);

  /**
   * @abstract
   * @return string
   */
  public function getActivationHash();

  /**
   * @abstract
   * @return string
   */
  public function getLogin();

  /**
   * @abstract
   * @return string
   */
  public function getPassword();

  /**
   * @abstract
   * @return string
   */
  public function getEmail();

  /**
   * @abstract
   * @return int
   */
  public function getDate();

  /**
   * @abstract
   * @return bool
   */
  public function getEnable();

  /**
   * @abstract
   * @param bool$bool
   * @return
   */
  public function setEnable($bool);

  /**
   * @abstract
   * @return bool
   */
  public function getIsActivated();

  public function getId();

  public function getVkToken();
  public function setVkToken($token);
  public function getVkUid();
  public function setVkUid($token);
}