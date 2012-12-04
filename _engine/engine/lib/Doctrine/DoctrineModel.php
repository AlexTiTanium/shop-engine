<?php

namespace lib\Doctrine;

use DateTime;
use MongoId;
use MongoDate;

class DoctrineModel {

  /**
   * @return array
   */
  public function toArray() {

    return get_object_vars($this);
  }

  /**
   * @param bool $removeFromResult
   * @return array
   */
  public function toFlatArray($removeFromResult = false){

    $array = $this->toArray();

    if (is_string($removeFromResult)) {
      $removeFromResult = array($removeFromResult);
    }

    foreach ($array as $key => $value) {

      if ($value instanceof DateTime) {
        $array[$key] = $value->format('Y-m-d h:i:s');
      }

      if ($value instanceof MongoDate) {
        $array[$key] = date('Y-m-d h:i:s', $value->sec);
      }

      if (is_array($removeFromResult) and in_array($key, $removeFromResult)) {
        unset($array[$key]);
      }

      if ($value instanceof MongoId) {
        $array['id'] = (string)$value;
        unset($array['_id']);
      }

      if($value instanceof DoctrineModel){
        $array[$key] = $value->toFlatArray();
      }
    }

    return $array;
  }

}