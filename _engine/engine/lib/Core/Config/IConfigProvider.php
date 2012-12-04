<?php

namespace lib\Core\Config;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 24.11.12
 * Time: 8:36
 * To change this template use File | Settings | File Templates.
 */
interface IConfigProvider {

  /**
   * @param array $data
   */
  public function __construct($data);

  /**
   * @param null $key
   *
   * @return IConfigProvider|string|boolean
   */
  public function get($key = null);

  /**
   * @param $key
   *
   * @return mixed
   */
  public function value($key);
}
