<?php

namespace lib\EngineExceptions;

use \Exception;
use lib\Debugger\Debugger;
use lib\Core\Log;

class ExceptionHandler {

  /**
   * ExceptionHandler::handleException()
   *
   * @param Exception $exception
   * @return void
   */
  public static function handleException($exception){


    $type = 'html';

    Debugger::setTraceException($exception);

    try{

      Log::write($exception->getMessage() . ' :: File:' . $exception->getFile() . ' Line:' . $exception->getLine());

      if(method_exists($exception, $type)) {
        $exception->$type();
        return;
      }

      $sysException = new SystemException($exception->getMessage());
      $sysException->copy($exception);
      $sysException->$type();
      return;

    }catch (\Exception $e){
      echo $exception->getMessage() . ' <br><b>' . $exception->getFile() . '</b> LINE: ' . $exception->getLine();
      echo $e->getMessage() . ' <br><b>' . $e->getFile() . '</b> LINE: ' . $e->getLine();
      Log::write($e->getMessage() . ' :: File:' . $e->getFile() . ' Line:' . $e->getLine());
    }

  }

}