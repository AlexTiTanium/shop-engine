<?php

namespace lib\View;

class ViewImage implements IView {

  private $image;
  private $type;

  const IMAGE_TYPE_JPG = 'imagejpeg';
  const IMAGE_TYPE_PNG = 'imagepng';
  const IMAGE_TYPE_BMP = 'imagewbmp';

  public function set($type, $image = false){

    if($image=== false){
      $image = self::IMAGE_TYPE_PNG;
    }

    $this->image = $image;
    $this->type = $type;
  }

  public function toString(){
    ${$this->type}($this->image);
    imagedestroy($this->image);
  }

}