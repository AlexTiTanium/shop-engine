<?php

namespace lib\View;

use lib\EngineExceptions\SystemException;
use lib\Tpl\Template;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService\Includer;
use lib\Core\IncluderService;

class ViewBlock implements IView {

  private $template = 'blank';

  private $vars = array();

  private $addedTemplates = array();


  /**
   * ViewBlock::clean()
   *
   * @return \lib\View\ViewBlock
   */
  public function clean(){
    $this->vars = array();
    $this->addedTemplates = array();
    return $this;
  }

  /**
   * ViewBlock::setTemplate()
   *
   * @param mixed $name
   * @param bool $withClean
   * @return \lib\View\ViewBlock
   */
  public function setTemplate($name, $withClean = false){
    if($withClean) {
      $this->clean();
    }
    $this->template = $name;
    return $this;
  }

  /**
   * ViewBlock::addTemplate()
   *
   * @param mixed $key
   * @param mixed $nameTemplate
   * @return ViewHtml
   */
  public function addTemplate($key, $nameTemplate){
    $this->addedTemplates[$key] = $nameTemplate;
    return $this;
  }

  /**
   * ViewBlock::getTemplate()
   *
   * @param mixed $key
   * @return Template
   */
  public function getTemplate($key){
    return $this->addedTemplates[$key];
  }

  /**
   * ViewHtml::set()
   *
   * @param mixed $name
   * @param mixed $value
   * @return ViewHtml
   */
  public function set($name, $value = false){
    if(is_string($name)){
      $this->vars[$name] = $value;
    }

    if(is_array($name) or is_object($name)){
      foreach($name as $key=>$value){
        $this->set($key,$value);
      }
    }

    return $this;
  }

  /**
   * ViewBlock::parse()
   * @throws \lib\EngineExceptions\SystemException
   * @return
   */
  public function parse(){

    if(View::$disable) {
      return;
    }

    if(!$this->template) {
      throw new SystemException('Шаблон не задан');
    }

    $template = TemplatesManager::load($this->template);

    if(!empty($this->addedTemplates)) {
      foreach($this->addedTemplates as $key => $value) {
        if($value instanceof Template) {
          /**
           * @var Template $value
           */
          $value = $value->toString();
        } else {
          if($value instanceof Includer) {
            /**
             * @var Includer $value
             */
            $value = $value->get();
          } else {
            /**
             * templates - it is folder in skins
             */
            IncluderService::$skin->templates->html($value);
          }
        }
        $template->set($key, $value);
      }
    }

    if(!empty($this->vars)) {
      foreach($this->vars as $key => $value) {
        $template->set($key, $value);
      }
    }

    $this->template = $template->toString();
  }

  /**
   * ViewBlock::toString()
   *
   * @return string
   */
  public function toString(){
    if(View::$disable) {
      return null;
    }
    $this->parse();
    return $this->template;
  }

}