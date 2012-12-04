<?php

namespace lib\PaymentSystems\Clients;

use lib\Form\Form;
use lib\PaymentSystems\IPaymentDbAdapter;
use lib\PaymentSystems\IPaymentClient;
use lib\Core\Config;

class WebMoneyMerchant implements IPaymentClient {

  const URL_WEBMONEY_MERCHANT = 'https://merchant.webmoney.ru/lmi/payment.asp';

  const LMI_PAYEE_PURSE = 'LMI_PAYEE_PURSE';
  const LMI_PAYMENT_AMOUNT = 'LMI_PAYMENT_AMOUNT';
  const LMI_PAYMENT_NO = 'LMI_PAYMENT_NO';
  const LMI_PAYMENT_DESC = 'LMI_PAYMENT_DESC';
  const LMI_PAYMENT_DESC_BASE64 = 'LMI_PAYMENT_DESC_BASE64';
  const LMI_SIM_MODE = 'LMI_SIM_MODE';
  const LMI_RESULT_URL = 'LMI_RESULT_URL';
  const LMI_SUCCESS_URL = 'LMI_SUCCESS_URL';
  const LMI_SUCCESS_METHOD  = 'LMI_SUCCESS_METHOD';
  const LMI_FAIL_URL  = 'LMI_FAIL_URL';
  const LMI_FAIL_METHOD  = 'LMI_FAIL_METHOD';
  const LMI_PAYMER_EMAIL = 'LMI_PAYMER_EMAIL';

  private $form;
  private $price;
  private $description;
  private $userId;

  /**
   * @var \config\PaymentsWebMoneyConfig
   */
  private $config;

  /**
   * @param $userId
   * @param \lib\Form\Form $form
   */
  public function __construct($userId, Form $form){
    $this->form = $form;
    $this->userId = $userId;
    $this->config = Config::load('paymentsWebMoney', new \config\PaymentsWebMoneyConfig());
  }

  /**
   * @param $key
   * @param $value
   */
  public function addParam($key, $value){
    $this->form->addElement('input', $key, array('type'=>'hidden', 'value'=>$value));
  }

  /**
   * @param $text
   */
  public function setDescription($text) {
    $this->description = $text;
  }

  /**
   * @param $price
   */
  public function setPrice($price) {
    $this->price = $price;
  }

  /**
   * @param \lib\PaymentSystems\IPaymentDbAdapter $adapter
   */
  public function createTransaction(IPaymentDbAdapter $adapter) {

    $config = $this->config;
    $adapter->setUserId($this->userId);
    $transactionNumber = $adapter->create($this->price, $config->webMoneyPurse, $config->webMoneyTestMode);
    $this->prepareForm($transactionNumber);
  }

  /**
   * @param $transactionNumber
   */
  private function prepareForm($transactionNumber){

    $config = $this->config;
    $url = Config::get('system')->siteUrl;

    $this->form->sendTo(self::URL_WEBMONEY_MERCHANT);
    $this->form->addBtn('Пополнить');

    $this->form->setTemplateVar('amount', $this->price);

    $this->addParam(self::LMI_PAYEE_PURSE, $config->webMoneyPurse);

    //$this->addParam(self::LMI_SIM_MODE,   (int)$config->webMoneyTestMode);
    $this->addParam(self::LMI_PAYMENT_NO, $transactionNumber);

    $this->addParam(self::LMI_PAYMENT_DESC_BASE64, base64_encode($this->description));
    $this->addParam(self::LMI_PAYMENT_AMOUNT, $this->price);

    $this->addParam(self::LMI_FAIL_URL,    $url.$config->webMoneyFailUrl);
    $this->addParam(self::LMI_RESULT_URL,  $url.$config->webMoneyResultUrl);
    $this->addParam(self::LMI_SUCCESS_URL, $url.$config->webMoneySuccessUrl);

  }
}