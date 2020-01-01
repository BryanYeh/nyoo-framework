<?php
namespace App\Exceptions;

use Exception;
use App\Core\Exceptions;

class RouterException extends Exceptions 
{
    protected $message = 'Unknown exception';

    public function __construct($message='', $code = 0, Exception $previous = null) {
        $this->message = $message;
    }
}
