<?php

namespace lib\PaymentSystems;

interface IPaymentDbAdapter {

  /**
   * @abstract
   * @param $userId
   * @return void
   */
  public function setUserId($userId);

  /**
   * @abstract
   * @param $price
   * @param $merchantPurse
   * @param bool $testMode
   * @return int
   */
  public function create($price, $merchantPurse, $testMode = false);

  /**
   * @abstract
   * @param $id
   * @return IPaymentModel
   */
  public function getPayment($id);

  /**
   * @abstract
   * @param $status
   * @return mixed
   */
  public function setStatus($status);

  /**
   * @abstract
   * @param $data
   * @internal param $status
   * @return mixed
   */
  public function setData($data);

  /**
   * @abstract
   * @param $msg
   * @return mixed
   */
  public function setError($msg);

  /**
   * @abstract
   * @param $id
   * @return void
   */
  public function setTransactionId($id);

  /**
   * @abstract
   * @return IPaymentModel
   */
  public function getCurrentPayment();
}
