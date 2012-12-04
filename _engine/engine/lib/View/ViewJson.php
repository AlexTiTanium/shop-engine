<?php

namespace lib\View;

class ViewJson implements IView {

  private $json = array();
  private $success = true;

  /**
   * @param array|string $json
   * @param bool|string $value
   */
  public function set($json, $value = null){

    if($value !== null){
      $this->json[$json] = $value;
      return;
    }

    $this->json = $json;
  }

  public function error($message){
    $this->success = false;
    $this->json['msg'] = $message;
  }

  public function add($json){
    $this->json += $json;
  }

  public function toString(){
    $this->json['success'] = $this->success;
    return json_encode($this->json);
  }

}