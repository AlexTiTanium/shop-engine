<?php

namespace lib\Templates\EngineExtensions;

use Twig_Extension;
use lib\Core\IncluderService;
use Twig_Function_Method;

class ViewHelper extends Twig_Extension {

  /**
   * @return array
   */
  public function getFunctions(){
    return array(
      'addCss' => new Twig_Function_Method($this, 'addCss',array('is_safe' => array('html'))),
      'addJs' => new Twig_Function_Method($this,  'addJs',array('is_safe' => array('html')))
    );
  }

  /**
   * @param $path
   *
   * @return string
   */
  public function addCss($path){

    if(is_array($path)){

      $pathArray = array();

      foreach($path as $itemPath){
        $pathArray[] = $this->addCss($itemPath);
      }

      return implode("\n", $pathArray);
    }

    return IncluderService::$skin->css($path);
  }

  /**
   * @param $path
   *
   * @return string
   */
  public function addJs($path){

    if(is_array($path)){

        $pathArray = array();

        foreach($path as $itemPath){
          $pathArray[] = $this->addJs($itemPath);
        }

        return implode("\n", $pathArray);
    }

    return IncluderService::$skin->js($path);
  }

  /**
   * ViewHelper::getName()
   *
   * @return string
   */
  public function getName(){
    return 'ViewHelper';
  }

}