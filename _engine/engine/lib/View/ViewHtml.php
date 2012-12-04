<?php

namespace lib\View;

use lib\EngineExceptions\SystemException;
use lib\Core\Manager;
use lib\Core\IncluderService;
use lib\Templates\TemplatesManager;

class ViewHtml implements IView {

  private $title = false;
  private $template = 'index';

  private $result = null;

  private $js = array();
  private $css = array();

  private $description = false;
  private $keywords = false;

  private $vars = array();

  /**
   * ViewHtml::clean()
   *
   * @return \lib\View\ViewHtml
   */
  public function clean()
  {
    $this->title = 'title';
    $this->js = array();
    $this->css = array();
    $this->vars = array();

    return $this;
  }

  /**
   * ContentHtml::setTitle()
   *
   * @param mixed $title
   * @return \lib\View\ViewHtml
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * ContentHtml::addTitle()
   *
   * @param string $title
   * @return \lib\View\ViewHtml
   */
  public function addTitle($title)
  {
    $this->title = $title . ' &mdash; ' . $this->title;
    return $this;
  }

  /**
   * ContentHtml::setKeywords()
   *
   * @param string $keywords
   * @return \lib\View\ViewHtml
   */
  public function setKeywords($keywords)
  {
    $this->keywords = $keywords;
    return $this;
  }

  /**
   * ViewHtml::addKeywords()
   *
   * @param string $keywords
   * @return \lib\View\ViewHtml
   */
  public function addKeywords($keywords)
  {
    $this->keywords .= $keywords;
    return $this;
  }

  /**
   * ViewHtml::setDescription()
   *
   * @param string $Description
   * @return \lib\View\ViewHtml
   */
  public function setDescription($Description)
  {
    $this->description = $Description;
    return $this;
  }

  /**
   * ViewHtml::addDescription()
   *
   * @param string $Description
   * @return \lib\View\ViewHtml
   */
  public function addDescription($Description)
  {
    $this->description .= $Description;
    return $this;
  }

  /**
   * ViewHtml::setTemplate()
   *
   * @param mixed $name
   * @return \lib\View\ViewHtml
   */
  public function setTemplate($name)
  {
    $this->template = $name;
    return $this;
  }

  /**
   * ViewHtml::extend()
   *
   * @param string $name
   * @return \lib\View\ViewHtml
   */
  public function extendBy($name)
  {
    $this->set('_extend_'.$name, $this->template.'.'.TemplatesManager::getTplExtension());
    $this->template = $name;

    return $this;
  }

  /**
   * ViewHtml::addJs()
   *
   * @param mixed $name
   * @return \lib\View\ViewHtml
   */
  public function addJs($name)
  {
    if(array_search($name, $this->js) === false) {
      $this->js[] = $name;
    }
    return $this;
  }

  /**
   * ViewHtml::addCss()
   *
   * @param mixed $name
   * @return \lib\View\ViewHtml
   */
  public function addCss($name)
  {
    if(array_search($name, $this->css) === false) {
      $this->css[] = $name;
    }
    return $this;
  }

  /**
   * ViewHtml::unsetCss()
   *
   * @param mixed $name
   * @return \lib\View\ViewHtml
   */
  public function unsetCss($name)
  {

    $key = array_search($name, $this->css);

    if($key !== false) {
      unset($this->css[$key]);
    }

    return $this;
  }

  /**
   * ViewHtml::set()
   *
   * @param mixed $name
   * @param mixed $value
   * @return ViewHtml
   */
  public function set($name, $value = false)
  {
    if(is_string($name)) {
      $this->vars[$name] = $value;
    }

    if(is_array($name) or is_object($name)) {
      foreach($name as $key => $value) {
        $this->set($key, $value);
      }
    }

    return $this;
  }

  /**
   * ViewHtml::parse()
   *
   * @throws SystemException
   * @return void
   */
  private function parse()
  {

    if(!$this->template) {
      throw new SystemException('Шаблон не задан');
    }

    $template = TemplatesManager::load($this->template);
    $template->setConstants(View::getConstants());

    if(!empty($this->title)) {
      $template->set('title', $this->title);
    }

    if(!empty($this->js)) {
      $js = '';
      foreach($this->js as $value) {
        $js .= IncluderService::$skin->js($value) . "\n";
      }
      $template->set('js', $js);
    }

    if(!empty($this->css)) {
      $css = '';
      foreach($this->css as $value) {
        $css .= IncluderService::$skin->css($value) . "\n";
      }

      $template->set('css', $css);
    }

    if(!empty($this->vars)) {
      foreach($this->vars as $key => $value) {
        $template->set($key, $value);
      }
    }

    $this->result = $template->toString();
  }

  /**
   * ViewHtml::toString()
   *
   * @return string
   */
  public function toString()
  {

    Manager::$Headers->ContentType('text/html');

    if($this->result){ return $this->result; }
    $this->parse();
    return $this->result;
  }

}