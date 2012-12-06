<?php

namespace lib\EngineExceptions;

use lib\Core\Manager;

class NotFound404Exception extends EngineException implements IException {

  public function __construct($message = null, $notLog = false){
    parent::__construct($message, 0, $notLog);
  }

  public function copy(\Exception $e){
    $this->line = $e->line;
    $this->file = $e->file;
    $this->code = $e->code;
    $this->trace = $e->getTrace();
  }

  public function renderError(){

    Manager::$Headers->error404();
    parent::renderError();
  }

}