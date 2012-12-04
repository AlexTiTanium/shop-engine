<?php

namespace lib\Templates\EngineExtensions;

use Twig_Extension;
use lib\Core\UrlService\Url;
use lib\Debugger\Debugger;
use lib\Core\Manager;
use Twig_Function_Method;

class UrlHelper extends Twig_Extension {

  public function getFunctions(){
    return array(
      'url' => new Twig_Function_Method($this, 'url'),
      'url_catalog'=> new Twig_Function_Method($this, 'catalog')
    );
  }

  public function url(array $array){

    return Manager::$UrlService->toString($array);
  }

  public function catalog(){

    $catalog = new Url(array('params'=>func_get_args()));
    return $catalog->toString();
  }

  /**
   * UrlHelper::getName()
   *
   * @return string
   */
  public function getName(){
    return 'UrlHelper';
  }

}