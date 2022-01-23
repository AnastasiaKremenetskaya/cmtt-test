<?php

namespace App\Exceptions;

use Exception;

class InternalServerException extends Exception
{
    public function __construct()
    {
        parent::__construct("Internal server error.");
    }
}
