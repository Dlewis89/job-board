<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $error_source;

    public function __construct(string $message = "", int $code = 0, Exception $previous = NULL, $error_source = NULL)
    {
        $this->error_source = $error_source;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorSource()
    {
        return $this->error_source;
    }

    public function render()
    {
        return response()->errorResponse(
            $message = $this->getMessage(),
            $data = $this->getErrorSource(),
            $code = $this->getCode()
        );
    }
}

