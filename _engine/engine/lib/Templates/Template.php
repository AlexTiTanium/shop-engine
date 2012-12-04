<?php

namespace lib\Templates;

use lib\View\View;
use Twig_Template;

class Template {

  /**
   * Переменные шаблона
   * @var array
   */
  private $setVars = array();

  /**
   * Имя шаблона который зашёл в шаблонизатор
   * @var string
   */
  private $template;

  /**
   * Помечаем если мы его уже парсили
   * @var bool
   */
  private $parsed = false;

  /**
   * Конструктор
   *
   * @param Twig_Template $template
   **/
  public function __construct(Twig_Template $template){
    $this->template = $template;
  }

  /**
   * @param array $global
   */
  public function setConstants($global){
    foreach($global as $key=>$value){
      $this->template->getEnvironment()->addGlobal($key, $value);
    }
  }

  /**
   * Установка переменных
   *
   * @param string || array || object $nameVar
   * @param mixed $newValue
   * @example $object->set('str','str');
   * @example $object->set(array('dsd','dsds'));
   * @example $object->set('str',$object);
   **/
  public function set($nameVar, $newValue = ''){

    if(is_string($nameVar)) {
      $this->setVars[$nameVar] = $newValue;
    }

    if(is_array($nameVar) or is_object($nameVar)) {
      foreach($nameVar as $key => $value) {
        $this->set($key, $value);
      }
    }
  }

  /**
   * Вывести из объекта содержимое шаблона
   *
   * @return string
   */
  public function toString(){

    if($this->parsed) {
      return $this->template;
    }

    $this->parsed = true;

    $this->template = $this->template->render($this->setVars);

    return $this->template;
  }

}