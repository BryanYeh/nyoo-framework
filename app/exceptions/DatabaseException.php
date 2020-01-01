<?php
namespace App\Exceptions;

use Exception;
use App\Core\Exceptions;

class DatabaseException extends Exceptions 
{
    protected $message = 'Unknown Database Error';

    public function __construct($message='', $code = 0, Exception $previous = null) {
        $this->message = $message;
    }
}
