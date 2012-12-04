<?php

namespace lib\View;

interface IView {

  public function set($name, $value = false);

  public function toString();

}
