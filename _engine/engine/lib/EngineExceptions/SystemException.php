<?php

namespace lib\EngineExceptions;

class SystemException extends EngineException implements IException {

  public function __construct($message = null, $notLog = false){
    parent::__construct($message, 0, $notLog);

  }

  public function copy(\Exception $e){
    $this->line = $e->line;
    $this->file = $e->file;
    $this->code = $e->code;
    $this->trace = $e->getTrace();
  }

  public function xml(){ parent::xml();}
  
  public function json(){ parent::json();}
  
  public function html(){ parent::html();  }

  public function php(){ parent::html();  }
  
}