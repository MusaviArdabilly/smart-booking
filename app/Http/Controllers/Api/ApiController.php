<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Success response method
     *
     * @param mixed $result
     * @param mixed $message
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $result)
    {
        $response = [
            'success'   => true,
            'message'   => $message,
            'data'      => $result,
        ];

        return response()->json($response, 200);
    }

    /**
     * Error response method
     *
     * @param mixed $error
     * @param array $errorMessages
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success'   => false,
            'message'   => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Invalid response method
     *
     * @param mixed $error
     * @param array $errorMessages
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendInvalid($invalid, $invalidMessages = [], $code = 422)
    {
        $response = [
            'success'   => false,
            'message'   => $invalid,
        ];

        if (!empty($invalidMessages)) {
            $response['data'] = $invalidMessages;
        }

        return response()->json($response, $code);
    }
}
