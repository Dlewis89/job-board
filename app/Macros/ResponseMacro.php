<?php

namespace App\Macros;

use Illuminate\Support\Facades\Response;

class ResponseMacro
{

    public function success()
    {
        return function ($message, $data = [], $status = 200) {
            $response_format = (object) [
                "status" => true,
                "message" => $message,
                "data" => (object) $data
            ];
            return Response::json($response_format, $status);
        };
    }

    public function errorResponse()
    {
        return function ($message, $data = [], $status = 500) {
            $response_format = (object) [
                "status" => false,
                "message" => $message,
                "errors" => (object) $data
            ];
            return Response::json($response_format, $status);
        };
    }
}
