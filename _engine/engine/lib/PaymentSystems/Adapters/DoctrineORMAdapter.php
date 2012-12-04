<?php

namespace lib\PaymentSystems\Adapters;

use lib\PaymentSystems\IPaymentDbAdapter;
use lib\Log\Log;
use lib\EngineExceptions\SystemException;
use lib\Doctrine\DoctrineOrm;
use lib\PaymentSystems\PaymentSystem;
use DateTime;
use lib\PaymentSystems\IPaymentModel;

class DoctrineORMAdapter implements  IPaymentDbAdapter {

  /**
   * @var string $userId
   */
  private $userId;

  /**
   * @var IPaymentModel
   */
  private $model;

  private $currentPayment;

  private $data = array();

  /**
   * @param \lib\PaymentSystems\IPaymentModel $model
   */
  public function __construct(IPaymentModel $model){
    $this->model = $model;
  }

  /**
   * @param $userId
   */
  public function setUserId($userId){
    $this->userId = $userId;
  }

  /**
   * @param $data
   * @return void
   * @internal param $userId
   */
  public function setData($data){
    $this->data = $data;
  }

  /**
   * @param float $price
   * @param string $merchantPurse
   * @param bool $testMode
   * @return int
   * @throws \lib\EngineExceptions\SystemException
   */
  public function create($price, $merchantPurse, $testMode = false) {

    if(!$this->userId){
      throw new SystemException('You must set user id');
    }

    $payment = clone $this->model;

    $payment->setDate(new DateTime());
    $payment->setDateUpdate(new DateTime());
    $payment->setIsTestMode($testMode);
    $payment->setMerchantPurse($merchantPurse);
    $payment->setPrice($price);
    $payment->setStatus(PaymentSystem::STATUS_PAYMENT_NEW);
    $payment->setUserId($this->userId);

    $this->setCurrentPayment($payment);

    $this->save();

    return $payment->getId();
  }

  /**
   * @param int $id
   * @throws \lib\EngineExceptions\SystemException
   * @return IPaymentModel
   */
  public function getPayment($id) {

    $payment = DoctrineOrm::find(get_class($this->model), $id);

    if(!$payment){
      throw new SystemException('Payment not found, id: '.$id);
    }

    $this->setCurrentPayment($payment);

    return $payment;
  }

  /**
   * @param $payment
   * @return void
   */
  private function setCurrentPayment($payment) {
    $this->currentPayment = $payment;
  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   * @internal param $payment
   * @return IPaymentModel
   */
  public function getCurrentPayment() {

    if(!$this->currentPayment){
      throw new SystemException('Current payment not been set');
    }

    return $this->currentPayment;
  }

  /**
   * @param $status
   * @return mixed
   */
  public function setStatus($status) {

    $payment = $this->getCurrentPayment();
    $payment->setStatus($status);

    $this->save();
  }

  /**
   * @param $msg
   * @internal param $status
   * @return mixed
   */
  public function setError($msg) {

    try {
      $payment = $this->getCurrentPayment();
    }catch (SystemException $e){

      Log::write('Payment not init, error: '.$msg);
      return;
    }

    $payment->setMsg($msg);
    $payment->setStatus(PaymentSystem::STATUS_PAYMENT_ERROR);

    $this->save();
  }

  /**
   * @param $id
   * @return void
   */
  public function setTransactionId($id) {

    $payment = $this->getCurrentPayment();
    $payment->setTransactionId($id);
  }

  /**
   * Save changes in $currentPayment
   */
  private function save(){

    $payment = $this->getCurrentPayment();
    $payment->setDateUpdate(new DateTime());

    if(!empty($this->data)){
      $oldArray = $payment->getData();
      $index = $payment->getStatus();
      $payment->setData(array_merge((array)$oldArray, array($index=>$this->data)));
    }

    DoctrineOrm::persist($payment);
    DoctrineOrm::flush();
  }
}
