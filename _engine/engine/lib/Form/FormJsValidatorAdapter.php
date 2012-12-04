<?php

namespace lib\Form;

class FormJsValidatorAdapter {

  /**
   * @var array
   */
  private static $rules = array(
    'req'=>'_req',
    'groupReq'=>'_groupReq',
    'minCheckbox'=>'_minCheckbox',
    'js'=>'_js',
    'min'=>'_min',
    'max'=>'_max',
    'minNum'=>'_minNum',
    'maxNum'=>'_maxNum',
    'equals'=>'_equals',
    'email'=>'_email',
    'onlyLetterNumber'=>'_onlyLetterNumber',
    'integer'=>'_integer',
    'number'=>'_number',
    'map'=>'_map',
    'youtube'=>'_youtube',
    'onlyLetNumSpec'=>'_onlyLetNumSpec',
    'url'=>'_url'
  );

  /**
   * @static
   * @param array $config
   * @return array
   */
  public static function addValidator(array $config){

    if(!isset($config['validation']) or empty($config['validation'])){
      return $config;
    }

    $rulesResult = array();

    foreach($config['validation'] as $rule=>$value){

      if(!isset(self::$rules[$rule])){ continue; }

      /**
       * @var string $function
       */
      $function  = self::$rules[$rule];
      $result = self::$function($value);

      if($result and is_string($result)){ $rulesResult[] = $result; }
      if($result and is_array($result)){
        foreach($result as $key=>$validator){
          $config['class'][$key] = $validator;
        }
      }
    }

    if(!empty($rulesResult)){
      $config['class'] = 'validate['.implode(', ', $rulesResult).']';
    }

    return $config;
  }

  /**
   * @param array $map
   * @return array
   */
  private static function _map($map){
    if(!$map){ return ''; }
    $result = array();

    foreach($map as $name=>$fieldConfig){
      $result[$name] = FormJsValidatorAdapter::addValidator($fieldConfig);
      $result[$name] = $result[$name]['class'];
    }

    return  $result;
  }

  /**
   * @param bool $value
   * @return string
   */
  private static function _req($value){
    if($value != true){ return ''; }

    return 'required';
  }



  /**
   * @param string $value
   * @return string
   */
  private static function _groupReq($value){
    if(!$value){ return ''; }

    return 'groupRequired['.$value.']';
  }

  /**
   * @param int $value
   * @return string
   */
  private static function _minCheckbox($value){
    if(!$value){ return ''; }

    return 'minCheckbox['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _js($value){
    if(empty($value)){ return ''; }

    return $value;
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _min($value){
    if(empty($value)){ return ''; }

    return 'minSize['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _max($value){
    if(empty($value)){ return ''; }

    return 'maxSize['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _minNum($value){
    if(empty($value)){ return ''; }

    return 'min['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _maxNum($value){
    if(empty($value)){ return ''; }

    return 'max['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _equals($value){
    if(empty($value)){ return ''; }

    return 'equals['.$value.']';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _email($value){
    if(!$value){ return ''; }

    return 'custom[email]';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _youtube($value){
    if(!$value){ return ''; }

    return 'custom[youtube]';
  }

  /**
   * @param string $value
   * @return string
   */
  private static function _url($value){
    if(!$value){ return ''; }

    return 'custom[url]';
  }

  /**
   * @static
   * @param $value
   * @return string
   */
  private static function _onlyLetterNumber($value){
    if(!$value){ return ''; }

    return 'custom[onlyLetterNumber]';
  }

  /**
   * @static
   * @param $value
   * @return string
   */
  private static function _integer($value){
    if(!$value){ return ''; }

    return 'custom[integer]';
  }

  /**
   * @static
   * @param $value
   * @return string
   */
  private static function _number($value){
    if(!$value){ return ''; }

    return 'custom[number]';
  }

  /**
   * @static
   * @param $value
   * @return string
   */
  private static function _onlyLetNumSpec($value){
    if(!$value){ return ''; }

    return 'custom[onlyLetNumSpec]';
  }

}
