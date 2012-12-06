<?php 

namespace lib\EngineExceptions;

use lib\EngineExceptions\IException;
use lib\Core\Manager;
use lib\Templates\TemplatesManager;
use lib\Core\IncluderService;
use lib\Debugger\Debugger;
use lib\View\View;
use lib\Core\Log;


abstract class EngineException extends \Exception implements IException {

  protected $message = 'Unknown exception'; // Exception message
  protected $code = 0; // User-defined exception code
  protected $file; // Source filename of exception
  protected $line; // Source line of exception
  protected $trace; // Unknown

  protected $notLog = false;

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

  public function renderError(){

    View::getCurrent()->error($this);
    echo View::getCurrent()->toString();
  }

}