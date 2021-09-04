<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($result, $message = 'success', $code = 200)
    {
        $response = [
            'status' => true,
            'data'    => $result,
            'message'    => $message,
        ];

        return response()->json($response, $code);
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($message = 'error', $errors = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message'    => $message,
            'errors'    => $errors,
        ];

        return response()->json($response, $code);
    }
}
