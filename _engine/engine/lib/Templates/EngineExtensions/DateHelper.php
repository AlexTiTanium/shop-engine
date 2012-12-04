<?php

namespace lib\Templates\EngineExtensions;

use Twig_Extension;
use Twig_Filter_Method;
use lib\Core\Config;

class DateHelper extends Twig_Extension {

  /**
   * DateHelper::getFilters()
   *
   * @return array
   */
  public function getFilters(){
    return array(
      'rusDate' => new Twig_Filter_Method($this, 'rusDate'),
    );
  }

  /**
   * DateHelper::rusDate()
   *
   * @param $date
   * @internal param mixed $string
   * @return string 22 января 2010
   */
  public function rusDate($date){
    if(!$date) {
      return '-';
    }

    if($date instanceof \MongoTimestamp){
      $timestamp = $date->sec;
    }
    elseif(isset($date['i'])){
      $timestamp = $date['i'];
    } else{
      $timestamp = strtotime($date);
    }

    $mons = Config::get(Config::SYSTEM)->mons[date('n', $timestamp)];

    return date('j ' . $mons . ' Y', $timestamp);
  }

  /**
   * DateHelper::getName()
   *
   * @return string
   */
  public function getName(){
    return 'DateHelper';
  }

}