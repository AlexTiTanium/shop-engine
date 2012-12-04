<?php

namespace models\Interfaces;

interface ISessionInterface {


  public function setSid($sid);

  public function setUserId($userId);

  public function setIp($ip);

  public function setNow($string);

  public function setExpOnline($timestamp);

  public function setRememberMe($bool);

  public function setExpSession($timestamp);

  public function getId();

  public function getSid();

  public function getUserId();

  public function getIp();

  public function getNow();

  public function getExpOnline();

  public function getRememberMe();

  public function getExpSession();

}