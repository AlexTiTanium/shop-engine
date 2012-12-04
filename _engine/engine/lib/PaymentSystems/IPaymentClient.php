<?php

namespace lib\PaymentSystems;

interface IPaymentClient {

  public function createTransaction(IPaymentDbAdapter $adapter);

}