<?php

namespace lib\PaymentSystems;

interface IPaymentServer {

  public function getPayment(IPaymentDbAdapter $adapter);
  public function result(IPaymentDbAdapter $adapter);
  public function success(IPaymentDbAdapter $adapter, $idTransaction);
  public function fail(IPaymentDbAdapter $adapter);

}