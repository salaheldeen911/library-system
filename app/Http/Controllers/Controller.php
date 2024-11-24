<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    public function success(string $message = '', $data = [], int $statusCode = 200)
    {
        $response = ['status' => true, 'message' => $message, 'data' => $data];

        return response()->json($response, $statusCode);
    }

    public function failed(string $message = '', $data = [], int $statusCode = 404)
    {
        $response = ['status' => false, 'message' => $message, 'data' => $data];

        return response()->json($response, $statusCode);
    }

    public function errorLog(Exception $e, string $message = 'Some error occurred'): void
    {
        Log::error($message, ['error_msg' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'time' => now()->toDateTimeString()]);
    }
}
