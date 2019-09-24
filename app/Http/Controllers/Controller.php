<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message = "", $success_code = "", $result = []) {

    	$response = ['success' => true, 'data' => []];

        if(!empty($message)) {
            $response['message'] = $message;
        }

        if(!empty($success_code)) {
            $response['code'] = $success_code;
        }

        if(!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function sendError($error, $error_code = 101, $error_messages = [], $response_code = 200) {
    	//
        $response = ['success' => false, 'error' => $error , 'error_code' => $error_code];

        if(!empty($error_messages)) {
            $response['error_messages'] = $error_messages;
        }

        return response()->json($response, $response_code);
    }
}
