<?php
namespace App\Core;
use Exception;

abstract class Exceptions extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($this->message, $code, $previous);
    }
}