<?php 

namespace lib\EngineExceptions;

use lib\EngineExceptions\IException;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService;
use lib\Debugger\Debugger;
use lib\View\View;
use lib\Core\Log;

use lib\EngineExceptions\SystemException;

abstract class EngineException extends \Exception implements IException {

  protected $message = 'Unknown exception'; // Exception message
  protected $code = 0; // User-defined exception code
  protected $file; // Source filename of exception
  protected $line; // Source line of exception
  protected $trace; // Unknown
  protected $format = 'html';

  public static $template = 'error';
  protected $notLog = false;

  /**
   * EngineException::setFormat()
   *
   * @param mixed $format
   * @return void
   */
  protected function setFormat($format){ $this->format = $format; }

  /**
   * EngineException::__construct()
   *
   * @param mixed $message
   * @param integer $code
   * @param bool $notLog
   * @throws
   * @return EngineException
   */
  public function __construct($message = null, $code = 0 , $notLog = false){

    if(!$message) {
      throw new $this('Unknown ' . get_class($this));
    }
    
    parent::__construct($message, $code);

    $this->notLog = $notLog;

    //TODO: hard mode
    if(!$this->notLog){
      Log::write($this->getMessage(), $this->file, $this->line);
    }

  }

  /**
   * EngineException::xml()
   * @return void
   */
  public function xml(){

    $view = View::getXmlView();

    $view->set(array('error' => $this->message));

    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();

    echo $view->toString();
  }

  /**
   * EngineException::json()
   *
   * @return void
   */
  public function json(){

    $view = View::getJsonView();
    $view->error($this->message);

    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();

    echo $view->toString();
  }

  /**
   * EngineException::cron()
   *
   * @return void
   */
  public function cron(){
    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();
  }

  /**
   * EngineException::cron()
   *
   * @return void
   */
  public function text(){
    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();
  }

  /**
   * EngineException::html()
   *
   * @return void
   */
  public function html(){

    if(!IncluderService::$skin) {
      echo $this->message;
      Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
      Debugger::information();
      return;
    }

    if(empty(TemplatesManager::$pathToTpl)) {
      TemplatesManager::addPath(IncluderService::$skin->setPath('templates')->getPath());
      TemplatesManager::addPath(IncluderService::$skin->getPath());
    }

    View::setConstant('images','/skins/'.SKIN_NAME.'/images');

    $view = View::getHtmlView();

    $view->setTemplate(self::$template);

    $view->set('message', $this->message);
    $trace = str_replace('#', '<br />#', $this->getTraceAsString());

    $view->set('trace', $trace);
    $view->set('line', $this->line);
    $view->set('file', $this->file);

    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();

    echo $view->toString();
  }

  /**
   * EngineException::html()
   *
   * @return void
   */
  public function php(){
    $this->html();
  }

  /**
   * EngineException::html()
   *
   * @return void
   */
  public function block(){
    echo '<p><center>'.$this->message.'</center></p>';
    Debugger::exception(array('message' => $this->message, 'file' => $this->file, 'line' => $this->line));
    Debugger::information();
  }
}