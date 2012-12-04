<?php

namespace lib\View;

use lib\Core\Core;

class View {

  private static $viewHtml;
  private static $viewJson;
  private static $viewBlock;
  private static $viewCaptcha;
  private static $viewCron;
  private static $viewCsv;
  private static $viewImage;
  private static $viewPhp;
  private static $viewText;
  private static $viewXml;

  /**
   * @static
   * @return ViewBlock
   */
  static private $constView = array();

  /**
   * @var IView
   */
  private static $current;

  public static function setCurrent(IView $view){
    self::$current = $view;
  }

  /**
   * View::setConstant()
   *
   * @param string $key
   * @param string $value
   * @return void
   */
  public static function setConstant($key, $value){
    self::$constView[$key] = $value;
  }

  /**
   * @static
   * @return array
   */
  public static function getConstants(){
    return self::$constView;
  }

  /**
   * @static
   * @return IView
   */
  public static function getCurrent(){
    if(!self::$current){
      self::setCurrent(self::getHtmlView());
    }

    return self::$current;
  }

  public static function setBlockView(ViewBlock $viewBlock)
  {
    self::$viewBlock = $viewBlock;
  }

  /**
   * @static
   * @return ViewCaptcha
   */
  public static function getBlockView()
  {
    if(!self::$viewCaptcha){
      self::setBlockView(new ViewBlock());
    }
    return self::$viewCaptcha;
  }

  public static function setCaptchaView(ViewCaptcha $viewCaptcha)
  {
    self::$viewCaptcha = $viewCaptcha;
  }

  /**
   * @static
   * @return ViewCaptcha
   */
  public static function getCaptchaView()
  {
    if(!self::$viewCaptcha){
      self::setCaptchaView(new ViewCaptcha());
    }
    return self::$viewCaptcha;
  }

  public static function setCronView(ViewCron $viewCron)
  {
    self::$viewCron = $viewCron;
  }

  /**
   * @static
   * @return ViewCron
   */
  public static function getCronView()
  {
    if(!self::$viewCron){
      self::setCronView(new ViewCron());
    }
    return self::$viewCron;
  }

  public static function setCsvView(ViewCsv $viewCsv)
  {
    self::$viewCsv = $viewCsv;
  }

  /**
   * @static
   * @return ViewCsv
   */
  public static function getCsvView()
  {
    if(!self::$viewCsv){
      self::setCsvView(new ViewCsv());
    }
    return self::$viewCsv;
  }

  public static function setHtmlView(ViewHtml $viewHtml)
  {
    self::$viewHtml = $viewHtml;
  }

  /**
   * @static
   * @return ViewHtml
   */
  public static function getHtmlView()
  {
    if(!self::$viewHtml){
      self::setHtmlView(new ViewHtml());
    }
    return self::$viewHtml;
  }

  public static function setImageView(ViewImage $viewImage)
  {
    self::$viewImage = $viewImage;
  }

  /**
   * @static
   * @return ViewImage
   */
  public static function getImageView()
  {
    if(!self::$viewImage){
      self::setImageView(new ViewImage());
    }
    return self::$viewImage;
  }

  public static function setJsonView(ViewJson $viewJson)
  {
    self::$viewJson = $viewJson;
  }

  /**
   * @static
   * @return ViewJson
   */
  public static function getJsonView()
  {
    if(!self::$viewJson){
      self::setJsonView(new ViewJson());
    }

    return self::$viewJson;
  }

  public static function setPhpView(ViewPhp $viewPhp)
  {
    self::$viewPhp = $viewPhp;
  }

  /**
   * @static
   * @return ViewPhp
   */
  public static function getPhpView()
  {
    if(!self::$viewPhp){
      self::setPhpView(new ViewPhp());
    }

    return self::$viewPhp;
  }

  public static function setTextView(ViewText $viewText)
  {
    self::$viewText = $viewText;
  }

  /**
   * @static
   * @return ViewText
   */
  public static function getTextView()
  {
    if(!self::$viewText){
      self::setTextView(new ViewText());
    }

    return self::$viewText;
  }

  public static function setXmlView(ViewXml $viewXml)
  {
    self::$viewXml = $viewXml;
  }

  /**
   * @static
   * @return ViewXml
   */
  public static function getXmlView()
  {
    if(!self::$viewXml){
      self::setXmlView(new ViewXml());
    }

    return self::$viewXml;
  }
}