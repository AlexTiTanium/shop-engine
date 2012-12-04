<?php

namespace lib\ImageResizer;

use lib\EngineExceptions\SystemException;

class ImageResizer {

  /**
   * Maximal scaling
   */
  const THUMBNAIL_METHOD_SCALE_MAX = 0;

  /**
   * Minimal scaling
   */
  const THUMBNAIL_METHOD_SCALE_MIN = 1;

  /**
   * Cropping of fragment
   */
  const THUMBNAIL_METHOD_CROP = 2;

  /**
   * Align constants
   */
  const THUMBNAIL_ALIGN_CENTER = 0;
  const THUMBNAIL_ALIGN_LEFT = -1;
  const THUMBNAIL_ALIGN_RIGHT = +1;
  const THUMBNAIL_ALIGN_TOP = -1;
  const THUMBNAIL_ALIGN_BOTTOM = +1;

  private $thumbnail;

  /**
   *
   *
   * @param  $thumbnail instanceof Thumbnail
   * @return boolean TRUE on success or FALSE on failure.
   * @access public
   */
  public function __construct($thumbnail){
    if(!$thumbnail instanceof Thumbnail) {
      throw new SystemException('Аргумент должен быть потомком Thumbnail');
    }
    $this->thumbnail = $thumbnail;
  }

  /**
   * Draw thumbnail result to resource.
   *
   * @param  array   $options Thumbnail options
   *
   * Options = array(
   *   'width'   => 150,
   *   'height'  => 150,
   *   'method'  => THUMBNAIL_METHOD_SCALE_MAX,
   *   'percent' => 0,
   *   'halign'  => THUMBNAIL_ALIGN_CENTER,
   *   'valign'  => THUMBNAIL_ALIGN_CENTER,
   * );
   *
   * @return boolean TRUE on success or FALSE on failure.
   * @access public
   */
  public function resize($options = array()){

    $sourceWidth = $this->thumbnail->getCurrentWidth();
    $sourceHeight = $this->thumbnail->getCurrentHeight();

    // Set default options
    $defOptions = array('width' => 150, 'height' => 150, 'method' => self::THUMBNAIL_METHOD_SCALE_MAX, 'percent' => 0, 'halign' => self::THUMBNAIL_ALIGN_CENTER, 'valign' => self::THUMBNAIL_ALIGN_CENTER,);

    foreach($defOptions as $k => $v) {
      if(!isset($options[$k])) {
        $options[$k] = $v;
      }
    }

    // Estimate a rectangular portion of the source image and a size of the target image
    if($options['method'] == self::THUMBNAIL_METHOD_CROP) {
      if($options['percent']) {
        $W = floor($options['percent'] * $sourceWidth);
        $H = floor($options['percent'] * $sourceHeight);
      } else {
        $W = $options['width'];
        $H = $options['height'];
      }

      $width = $W;
      $height = $H;

      $Y = $this->_coord($options['valign'], $sourceHeight, $H);
      $X = $this->_coord($options['halign'], $sourceWidth, $W);

    } else {

      $X = 0;
      $Y = 0;

      $W = $sourceWidth;
      $H = $sourceHeight;

      if($options['percent']) {
        $width = floor($options['percent'] * $W);
        $height = floor($options['percent'] * $H);
      } else {
        $width = $options['width'];
        $height = $options['height'];

        if($options['method'] == self::THUMBNAIL_METHOD_SCALE_MIN) {

          $Ww = $W / $width;
          $Hh = $H / $height;

          if($Ww > $Hh) {
            $W = floor($width * $Hh);
            $X = $this->_coord($options['halign'], $sourceWidth, $W);
          } else {
            $H = floor($height * $Ww);
            $Y = $this->_coord($options['valign'], $sourceHeight, $H);
          }
        } else {
          if($H > $W) {
            $width = floor($height / $H * $W);
          } else {
            $height = floor($width / $W * $H);
          }
        }
      }
    }
    // Copy the source image to the target image
    if($options['method'] == self::THUMBNAIL_METHOD_CROP) {
      $this->thumbnail->crop($X, $Y, $W, $H);
    } else {
      $this->thumbnail->cropRisized($X, $Y, $width, $height, $W, $H);
    }

    return $this->thumbnail;
  }

  private function _coord($align, $param, $src){

    if($align < self::THUMBNAIL_ALIGN_CENTER) {
      $result = 0;
    } elseif($align > self::THUMBNAIL_ALIGN_CENTER) {
      $result = $param - $src;
    } else {
      $result = ($param - $src) >> 1;
    }

    return $result;
  }

}