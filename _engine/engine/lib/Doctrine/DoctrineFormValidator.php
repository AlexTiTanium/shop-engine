<?php

namespace lib\Doctrine;

class DoctrineFormValidator {

  /**
   * @static
   * @param $mapper
   * @param $model
   * @param $field
   * @param $value
   * @return bool
   */
  public static function isUserUnique($mapper, $model, $field, $value){

    if($mapper == 'DoctrineOdm'){
      $result = DoctrineOdm::getRepository($model)->findOneBy(array($field=>$value));
      if($result == null){ return true; }
    }

    return false;
  }

}