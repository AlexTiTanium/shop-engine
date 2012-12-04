<?php

namespace lib\PaymentSystems;

use lib\Core\Config;
use lib\EngineExceptions\SystemException;

class PaymentSystem {

  const STATUS_PAYMENT_NEW = 'new';
  const STATUS_PAYMENT_DONE = 'done';
  const STATUS_PAYMENT_PRE_REQUEST = 'pre_request';
  const STATUS_PAYMENT_REQUEST = 'request';
  const STATUS_PAYMENT_ERROR = 'error';

  /**
   * @var IPaymentDbAdapter
   */
  private $adapter;

  /**
   * @var IPaymentClient
   */
  private $client;

  /**
   * @var IPaymentServer
   */
  private $server;

  /**
   * @param IPaymentDbAdapter $adapter
   */
  public function __construct(IPaymentDbAdapter $adapter){
    $this->adapter = $adapter;
  }

  /**
   * @param IPaymentClient $client
   */
  public function setClient(IPaymentClient $client) {
    $this->client = $client;
  }

  /**
   * @throws \lib\EngineExceptions\SystemException
   * @return IPaymentClient
   */
  public function getClient() {

    if(!$this->client){
      throw new SystemException('You must setClient');
    }

    return $this->client;
  }

  /**
   * @param IPaymentServer $server
   * @throws \lib\EngineExceptions\SystemException
   */
  public function setServer(IPaymentServer $server) {
    $this->server = $server;
    $this->server->getPayment($this->adapter);
  }

  /**
   * @return IPaymentServer
   */
  public function getServer() {
    return $this->server;
  }

  /**
   * @return IPaymentModel
   */
  public function getCurrentPayment() {
    return $this->adapter->getCurrentPayment();
  }

  /**
   *  Begin transaction
   */
  public function createTransaction(){
    $this->getClient()->createTransaction($this->adapter);
  }

  /**
   * @return string - response
   */
  public function result(){
    return $this->getServer()->result($this->adapter);
  }

  /**
   * @param $idTransaction
   * @return string - response
   */
  public function success($idTransaction){
    return $this->getServer()->success($this->adapter, $idTransaction);
  }

  /**
   * @return string - response
   */
  public function fail(){
    return $this->getServer()->fail($this->adapter);
  }
}