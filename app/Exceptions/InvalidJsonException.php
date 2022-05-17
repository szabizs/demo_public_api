<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InvalidJsonException extends Exception
{
    public $is_success;
    public $message;
    public $code;
    public $data;

    public function __construct(bool $is_success, $message, $code = 400, $data = null, Exception $previous = NULL)
    {
        $this->is_success = $is_success;
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public function render()
    {
        return response()->json([
            'success' => $this->is_success,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->code);
    }
}
