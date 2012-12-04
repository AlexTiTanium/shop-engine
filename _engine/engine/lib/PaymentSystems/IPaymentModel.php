<?php

namespace lib\PaymentSystems;

interface IPaymentModel {

  public function getId();

  public function getDate();
  public function setDate($date);

  public function getDateUpdate();
  public function setDateUpdate($date);

  public function getUserId();
  public function setUserId($id);

  public function getPrice();
  public function setPrice($price);

  public function getMerchantPurse();
  public function setMerchantPurse($purse);

  public function getIsTestMode();
  public function setIsTestMode($bool);

  public function getTransactionId();
  public function setTransactionId($id);

  public function getMsg();
  public function setMsg($text);

  public function getStatus();
  public function setStatus($status);

  public function getData();
  public function setData($data);

}