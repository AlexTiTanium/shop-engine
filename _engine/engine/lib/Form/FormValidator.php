<?php

namespace lib\Form;

use lib\EngineExceptions\SystemException;
use lib\Core\Log;
use lib\Doctrine\DoctrineFormValidator;
use lib\Upload\Upload;
use lib\Core\UrlService;
use lib\Session\Session;

class FormValidator {

  private $element;

  /**
   * @var Form $baseObject
   */
  private $baseObject;
  public $value;
  private $strings = array(
  'req'=>'Поле %s обязательно должно быть заполнено',
  'minCheckbox'=>'В группе %s Выберите хотя бы %s опцию(ии)',
  'email'=>'Email имеет не верный формат',
  'unique'=>'Поле %s должно быть уникальным',
  'url'=>'Поле %s должно быть в URL формате',
  'min'=>'В поле %s должно быть не меньше %s знаков',
  'max'=>'В поле %s должно быть не больше %s знаков',
  'minNum'=>'Значение поля %s должно быть не меньше %s',
  'maxNum'=>'Значение поля %s должно быть не больше %s',
  'int'=>'Поле %s должно быть целым числом',
  'notInt'=>'Поле %s НЕ должно быть целым числом',
  'num'=>'Поле %s должно быть числом',
  'notNum'=>'Поле %s НЕ должно быть числом',
  'onlyLetterNumber'=>'Поле %s может содержать только английские буквы и цифры',
  'alias'=>'Поле %s может содержать только английские буквы и цифры, а также "_"',
  'length'=>'Поле %s может содержать только %s-х значные элементы',
  'countMin'=>'В поле %s должно содержаться не мение %s элемента',
  'countMax'=>'В поле %s должно содержаться не больше %s элементов',
  'preg'=>'Поле %s не соответствует формату',
  'arrayUnique'=>'Элементы в поле %s не должны повторяться',
  'uniqueIn'=>'Некоторые элементы поля %s уже зарегестрированы(%s)',
  'file'=>'Файл поля %s не загружен по причине: %s',
  'captcha'=>'Цифры на капче не соответствуют тем что Вы ввели',
  'enable_cookie'=>'Для правельной работы капчи нужно включить Cookie',
  'equals'=>'Поля %s должны совпадать',
  'youtube'=>'В поле %s должна быть Youtube ссылка на видео',
  'onlyLetNumSpec'=>"В поле %s разрешены только латинские буквы, цифры, спецсимволы('-' '_' ',' '.' ':' '()')"
  );


  /**
   * FormValidator::__construct()
   *
   * @param mixed $element
   * @param $baseObject
   * @throws \lib\EngineExceptions\SystemException
   * @param mixed $baseObject
   * @return \lib\Form\FormValidator
   */
  public function __construct($element,$baseObject){
    $this->element    = $element;
    $this->baseObject = $baseObject;

    if(!property_exists($this->element, 'value')){ return; }

    $value = $this->element->value;
    $this->value = $value;
    $validationArray = $this->element->validation;

    if(!$validationArray){ return; }

    if(!isset($validationArray['escape'])){
      $validationArray['escape'] = true;
    }

    foreach($validationArray as $key=>$value){
      $method = '_'.$key;
      if(!method_exists($this,'_'.$key)){ throw new SystemException('Метод '.$key.' не найден'); }
      $this->{$method}($value);
    }
  }


  /**
   * @param array $map
   * @return array
   */
  private function _map($map){
    if(!$map){ return ''; }
    $result = array();

    foreach($map as $name=>$fieldConfig){
      $element = clone $this->element;
      $element->value = $this->value[$name];
      $element->label = $fieldConfig['label'];
      $element->validation = $fieldConfig['validation'];
      $validator = new FormValidator($element, $this->baseObject);
      $this->element->error[] = $validator->getErrors();
    }

    return  $result;
  }

  /**
   * FormValidator::setError()
   *
   * @param mixed $type
   * @param bool $additional
   * @return void
   */
  private function setError($type,$additional=false){
    $error = sprintf($this->strings[$type],'"'.mb_strtolower($this->element->label).'"',$additional);
    $this->baseObject->errors[] = $error;
    $this->element->error = $error;
  }

  /**
   * @return array
   */
  public function getErrors(){
    return $this->element->error;
  }

  /**
   * FormValidator::_req()
   *
   * @param mixed $value
   * @return void
   */
  private function _req($value){
    if(!$value){ return; }
    if(empty($this->value) ){ $this->setError('req'); }
  }

  /**
   * FormValidator::_phpReq()
   *
   * @param mixed $value
   * @return void
   */
  private function _phpReq($value){
    $this->_req($value);
  }

  /**
   * FormValidator::_groupReq()
   *
   * @param mixed $value
   * @return void
   */
  private function _groupReq($value){
    if(!$value){ return; }
    if(empty($this->value) ){ $this->setError('req'); }
  }

  /**
   * FormValidator::_minCheckbox()
   *
   * @param int $value
   * @return void
   */
  private function _minCheckbox($value){
    if(!$value){ return; }

    if(empty($this->value) or count($this->value) < $value){ $this->setError('minCheckbox', $value); }
  }

  /**
   * FormValidator::_captcha()
   *
   * @param bool $value
   * @return void
   */
  private function _captcha($value){

    if(!$value){ return; }
    $hash = md5($this->value.SYSTEM_CODE);

    $code = Session::getVar('captcha_code');

    if(empty($code)){
      $this->setError('enable_cookie');
      return;
    }

    if($hash!==$code){ $this->setError('captcha'); }
  }

  /**
   * FormValidator::_email()
   *
   * @param mixed $value
   * @return void
   */
  private function _email($value){
    if(!$value){ return; }
    if(!$this->value){ return; }

    $is_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $this->value);
    if(!$is_ok ){ $this->setError('email'); }
  }

  /**
   * FormValidator::_url()
   *
   * @param bool $value
   * @return void
   */
  private function _url($value){
    if(!$value){ return; }
    if(!$this->value){ return; }

    $is_ok = preg_match('%(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()[\]{};:\'".,<>?«»“”‘’]))%', $this->value);
    if(!$is_ok ){ $this->setError('url'); }
  }

  /**
   * FormValidator::_uniq()
   *
   * @param mixed $value
   * @throws \lib\EngineExceptions\SystemException
   * @return bool
   */
  private function _unique($value){

    if(!$value){ return; }
    if(!$this->value){ return; }

    if(!isset($value['model'])){ throw new SystemException('Опция Unique должна содержать параметр model'); }
    if(!isset($value['field'])){ throw new SystemException('Опция Unique должна содержать параметр field'); }
    if(!isset($value['mapper'])){ throw new SystemException('Опция Unique должна содержать параметр mapper'); }

    $model = $value['model'];
    $field = $value['field'];
    $mapper = $value['mapper'];

    $isUnique = DoctrineFormValidator::isUserUnique($mapper, $model, $field, $this->value);

    if(!$isUnique){
      $this->setError('unique');
    }
  }

  /**
   * FormValidator::_uniqIn()
   *
   * @param mixed $value
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function _uniqueIn($value){

    /*
    if(!$value){ return; }
    if(!$this->value){ return; }

    if(!isset($value['table'])){ throw new SystemException('Опция Unique должна содержать параметр table'); }
    if(!isset($value['column'])){ throw new SystemException('Опция Unique должна содержать параметр colum'); }
    if(!is_array($this->value)){ throw new SystemException('Значение поля должно быть массивом'); }

    $table = $value['table'];
    $column = $value['column'];

    $query = Doctrine_Query::create()
      ->select($column)
      ->from($table)
      ->whereIn($column, $this->value)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);

    if(!empty($query)){
      $prefix = array();
      foreach($query as $value){ $prefix[] = $value['prefix']; }
      $this->setError('uniqueIn', implode(',',$prefix));
    }*/
  }

  /**
   * FormValidator::_minlength()
   *
   * @param mixed $value
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function _min($value){
   if(!$this->value){ return; }
   if(!is_integer($value)){ throw new SystemException('Опция min должна быть числом'); }

   if(mb_strlen($this->value,'utf8')<$value){  $this->setError('min',$value); }
  }

  /**
   * FormValidator::_minNum()
   *
   * @param mixed $value
   * @param $value
   * @return void
   */
  private function _minNum($value){
    if(!$this->value){ return; }
    if($value > $this->value){ $this->setError('minNum',$value); }
  }

  /**
   * FormValidator::_maxNum()
   *
   * @param mixed $value
   * @param $value
   * @return void
   */
  private function _maxNum($value){
    if(!$this->value){ return; }
    if($value < $this->value){ $this->setError('maxNum',$value); }
  }

  /**
   * FormValidator::_maxlength()
   *
   * @param mixed $value
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function _max($value){
    if(!$this->value){ return; }
    if(!is_integer($value)){ throw new SystemException('Опция max должна быть числом'); }

    if(mb_strlen($this->value,'utf8')>$value){  $this->setError('max',$value); }
  }

  /**
   * FormValidator::_integer()
   *
   * @param mixed $value
   * @return void
   */
  private function _integer($value){
   if(!$this->value){ return; }
   if($value){
     if(!ctype_digit((string) $this->value)){  $this->setError('int'); }
   }else{
     if(ctype_digit((string) $this->value)){  $this->setError('notInt'); }
   }
  }

  /**
   * FormValidator::_number()
   *
   * @param mixed $value
   * @return void
   */
  private function _number($value){
   if(!$this->value){ return; }
   if($value){
     if(!is_numeric($this->value)){  $this->setError('num'); }
   }else{
     if(is_numeric($this->value)){  $this->setError('notNum'); }
   }
  }

  /**
   * FormValidator::_intval()
   *
   * @param mixed $value
   * @return void
   */
  private function _intval($value){
   if(!$this->value){ return; }
   if($value){
     $this->value = intval($this->value);
   }
  }

  /**
   * FormValidator::_escape()
   *
   * @param mixed $value
   * @return void
   */
  private function _escape($value){
    if(!$value){return; }

    if(is_string($this->value)){
      $this->value = htmlspecialchars($this->value);
    }

    if(is_array($this->value)){
      foreach($this->value as &$val){
        $val = htmlspecialchars($val);
      }
    }

  }

  /**
   * FormValidator::_onlyLetterNumber()
   *
   * @param mixed $value
   * @return void
   */
  private function _onlyLetterNumber($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

   $is_ok = preg_match('/^[a-z-A-Z-0-9]+$/', $this->value);
   if(!$is_ok){ $this->setError('login'); }

  }

  /**
   * FormValidator::_onlyLetNumSpec()
   *
   * @param mixed $value
   * @return void
   */
  private function _onlyLetNumSpec($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

   $is_ok = preg_match('/^[0-9a-zA-Z_\-.,:()]+$/', $this->value);
   if(!$is_ok){ $this->setError('onlyLetNumSpec'); }

  }

  /**
   * FormValidator::_youtube()
   *
   * @param mixed $value
   * @return void
   */
  private function _youtube($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

   $is_ok = preg_match('%http://(?:www\.)?youtu(?:be\.com/watch\?v=|\.be/)(\w*)(&(amp;)?[\w?=]*)?%im', $this->value);
   if(!$is_ok){ $this->setError('youtube'); }

  }

  /**
   * FormValidator::_preg()
   *
   * @param mixed $value
   * @return void
   */
  private function _preg($value){

   if(!$value){ return; }
   if(!$this->value){ return; }

   if(is_array($this->value)){

     foreach($this->value as $val){
       if(!preg_match($value, $val)){
         $this->setError('preg');
          return;
       }
     }

   }else{
     if(!preg_match($value, $this->value)){ $this->setError('preg'); }
   }
  }

  /**
   * FormValidator::_alias()
   *
   * @param mixed $value
   * @return void
   */
  private function _alias($value){

   if(!$value){ return; }
   if(!$this->value){ return; }

   $is_ok = preg_match('/^[a-z-A-Z-0-9-_]+$/', $this->value);
   if(!$is_ok){ $this->setError('alias'); }
  }

  /**
   * FormValidator::_length()
   *
   * @param mixed $value
   * @return void
   */
  private function _length($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

  //Debugger::log($value);

   if(is_array($this->value)){

     foreach($this->value as $val){

       if(is_array($value)){

         foreach($value as $len){
           if(mb_strlen($val,'utf8')==$len){ return; }
         }

         $this->setError('lenght', implode(',',$value));
         return;
       }

       if(mb_strlen($val,'utf8')!==$value){
         $this->setError('lenght', $value);
         return;
       }

     }

   }else{
     if(mb_strlen($this->value,'utf8')!==$value){ $this->setError('lenght', $value); }
   }
  }

  /**
   * FormValidator::_countMin()
   *
   * @param mixed $value
   * @return void
   */
  private function _countMin($value){

   if(!$value){ return; }
   if(!$this->value){ return; }

   if(count($this->value)<$value){
     $this->setError('countMin', $value);
     return;
   }

  }

  /**
   * FormValidator::_countMax()
   *
   * @param mixed $value
   * @return void
   */
  private function _countMax($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

   if(count($this->value)>$value){
     $this->setError('countMax', $value);
     return;
   }

  }

  /**
   * FormValidator::_array()
   *
   * @param mixed $value
   * @return
   */
  private function _array($value){
   if(!$value){ return; }
   if(!$this->value){ return; }

   if(!is_array($this->value)){ $this->setError('array'); }

  }

  /**
   * FormValidator::_arrayUniq()
   *
   * @param mixed $value
   * @return void
   */
  private function _arrayUnique($value){
   if(!$value){ return ; }
   if(!$this->value){ return ; }

   $elementsBefore = count($this->value);
   $elementsAfter = count(array_unique($this->value));
   if($elementsBefore!==$elementsAfter){ $this->setError('arrayUnique');  }

  }

  /**
   * FormValidator::_file()
   *
   * @param mixed $value
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function _file(array $value){

   if(empty($value)){ throw new SystemException('Опция $value is empty'); }
   if(!$this->element->name){ throw new SystemException('Опция $name is empty'); }

   $value['fileName'] = $this->element->name;
   $value['uploadDir'] = UPLOAD_DIR.DS.$value['uploadDir'];

   $file = new Upload($value);

    if($file->isUploaded){
     $this->value = $file->fullPath;
     return;
   }

   $this->setError('file', $file->error);

  }

  /**
   * @param $value
   */
  private function _equals($value){
    try{

      $formElement = $this->baseObject->getElement($value);

      if($formElement->value != $this->value){
        $this->setError('equals', $value);
      }

    }catch (SystemException $e){
      Log::write('__equals exception:'.$e->getMessage());
    }
  }
}