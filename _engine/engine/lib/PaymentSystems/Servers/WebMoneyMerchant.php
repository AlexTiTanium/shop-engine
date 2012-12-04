<?php

namespace lib\PaymentSystems\Servers;

use lib\Form\Form;
use lib\PaymentSystems\PaymentSystem;
use lib\Doctrine\DoctrineOrm;
use lib\PaymentSystems\IPaymentModel;
use lib\EngineExceptions\SystemException;
use lib\Core\Manager;
use lib\Data\Data;
use lib\PaymentSystems\IPaymentServer;
use lib\PaymentSystems\IPaymentDbAdapter;
use lib\Core\Config;

class WebMoneyMerchant implements IPaymentServer {

  const LMI_PREREQUEST = 'LMI_PREREQUEST';
  const LMI_PAYEE_PURSE = 'LMI_PAYEE_PURSE';
  const LMI_PAYMENT_AMOUNT = 'LMI_PAYMENT_AMOUNT';
  const LMI_PAYMENT_NO = 'LMI_PAYMENT_NO';
  const LMI_MODE = 'LMI_MODE';
  const LMI_HASH = 'LMI_HASH';

  const LMI_SYS_INVS_NO = 'LMI_SYS_INVS_NO';
  const LMI_SYS_TRANS_NO = 'LMI_SYS_TRANS_NO';
  const LMI_SYS_TRANS_DATE = 'LMI_SYS_TRANS_DATE';
  const LMI_SECRET_KEY = 'LMI_SECRET_KEY';
  const LMI_PAYER_PURSE = 'LMI_PAYER_PURSE';
  const LMI_PAYER_WM = 'LMI_PAYER_WM';

  const LMI_PAYMENT_DESC = 'LMI_PAYMENT_DESC';

  const SUCCESS_RESPONSE = 'YES';

  private static $IP_RANGE = array(
    '212.118.48.*',
    '212.158.173.*',
    '91.200.28.*',
    '91.227.52.*'
    //'194.79.22.*'
  );

  /**
   * @var \config\PaymentsWebMoneyConfig
   */
  private $config;

  /**
   * @var int $paymentId
   */
  private $paymentId;

  /**
   * @var bool $isPreRequest
   */
  private $isPreRequest;

  /**
   * @var string $hash
   */
  private $hash;

  /**
   * @var string $purse
   */
  private $purse;

  /**
   * @var float $amount
   */
  private $amount;

  /**
   * @var string $testMode
   */
  private $testMode;

  /**
   * @var \lib\Data\Data $data
   */
  private $data;

  /**
   * @param \lib\Data\Data $data
   * @throws \lib\EngineExceptions\SystemException
   */
  public function __construct(Data $data){

    $this->paymentId = $data->getRequired(self::LMI_PAYMENT_NO);
    $this->isPreRequest = $data->getBool(self::LMI_PREREQUEST);

    $this->data = $data;

    $this->config = Config::load('paymentsWebMoney', new \config\PaymentsWebMoneyConfig());
  }

  /**
   * @param \lib\PaymentSystems\IPaymentDbAdapter $adapter
   * @return \lib\PaymentSystems\IPaymentModel
   * @throws \lib\EngineExceptions\SystemException
   */
  public function getPayment(IPaymentDbAdapter $adapter){

    $payment = $adapter->getPayment($this->paymentId);

    if(!$payment){
      throw new SystemException('Payment not found, id: '.$this->paymentId);
    }

    return $payment;
  }

  /**
   * @param \lib\PaymentSystems\IPaymentModel $payment
   * @throws \lib\EngineExceptions\SystemException
   */
  private function requestChecks(IPaymentModel $payment){

    if($payment->getMerchantPurse() != $this->purse){
      throw new SystemException('Invalid merchant purse');
    }

    if($payment->getPrice() != $this->amount){
      throw new SystemException('Invalid amount');
    }

    if($payment->getIsTestMode() != $this->testMode){
      throw new SystemException('Invalid transaction mode');
    }

  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function checkDataHash(){

    $data = $this->data;

    $stringBuilder = array(
      $data->getRequired(self::LMI_PAYEE_PURSE),
      $data->getRequired(self::LMI_PAYMENT_AMOUNT),
      $data->getRequired(self::LMI_PAYMENT_NO),
      (int)$data->getBool(self::LMI_MODE),
      $data->getRequired(self::LMI_SYS_INVS_NO),
      $data->getRequired(self::LMI_SYS_TRANS_NO),
      $data->getRequired(self::LMI_SYS_TRANS_DATE),
      $this->config->webMoneySecretKey,
      $data->getRequired(self::LMI_PAYER_PURSE),
      $data->getRequired(self::LMI_PAYER_WM),
    );

    $sig = strtoupper(md5(implode('', $stringBuilder)));

    if($this->hash != $sig){
      throw new SystemException('Bad hash');
    }
  }

  /**
   * @return array
   */
  private function getData(){
    $data = $this->data->toArray();

    if(isset($data[self::LMI_PAYMENT_DESC])){
      $data[self::LMI_PAYMENT_DESC] = 'none';
    }

    return $data;
  }

  /**
   * @param \lib\PaymentSystems\IPaymentDbAdapter $adapter
   * @throws \lib\EngineExceptions\SystemException
   * @return string
   */
  public function result(IPaymentDbAdapter $adapter) {

    if(!Manager::$Common->ipInRanges(Manager::$Php->getIp(), self::$IP_RANGE)){
      throw new SystemException('Bad source ip, request come from: '.Manager::$Php->getIp());
    }

    $adapter->setData($this->getData());

    $this->purse = $this->data->getRequired(self::LMI_PAYEE_PURSE);
    $this->amount = $this->data->getRequired(self::LMI_PAYMENT_AMOUNT);
    $this->testMode = $this->data->getBool(self::LMI_MODE);

    if(!$this->isPreRequest){
      $this->hash = $this->data->getRequired(self::LMI_HASH);
    }

    $payment = $adapter->getCurrentPayment();
    $this->requestChecks($payment);

    if(!$this->isPreRequest){
      $this->checkDataHash();
      $adapter->setStatus(PaymentSystem::STATUS_PAYMENT_REQUEST);
    }else{
      $adapter->setStatus(PaymentSystem::STATUS_PAYMENT_PRE_REQUEST);
    }

    return self::SUCCESS_RESPONSE;
  }

  /**
   * @param \lib\PaymentSystems\IPaymentDbAdapter $adapter
   * @param $idTransaction
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  public function success(IPaymentDbAdapter $adapter, $idTransaction) {
    $payment = $adapter->getCurrentPayment();

    if($payment->getStatus() != PaymentSystem::STATUS_PAYMENT_REQUEST){
      throw new SystemException($payment->getMsg());
    }

    $adapter->setData($this->getData());
    $adapter->setTransactionId($idTransaction);
    $adapter->setStatus(PaymentSystem::STATUS_PAYMENT_DONE);
  }

  /**
   * @param \lib\PaymentSystems\IPaymentDbAdapter $adapter
   */
  public function fail(IPaymentDbAdapter $adapter) {
    $adapter->setData($this->getData());
    $adapter->setStatus(PaymentSystem::STATUS_PAYMENT_ERROR);
  }
}