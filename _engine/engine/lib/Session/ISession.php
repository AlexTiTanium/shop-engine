<?php

namespace lib\Session;

interface ISession {
 public function setVar($key,$value);
 public function deleteVar($key);
 public function setFlash($key,$value);
 public function pushFlash($key,$value);
 public function getVar($key);
 public function start($login,$password,$rememberMe = false);
 public function getUser();
 public function getSid();
 public function isLogged();
 public function close();
}