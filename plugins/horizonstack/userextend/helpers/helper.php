<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 10/1/2017
 * Time: 8:21 PM
 */

/**
 * @param string $message
 * @param mixed $data
 *
 * @return array
 */
function success($data, $message)
{
    return [
        'flag'    => true,
        'data'    => $data,
        'message' => $message,
    ];
}

function error($code, $message, $status = 400, $errors = [], $data = [], $headers = [])
{
    $error = [];

    $error['flag']    = false;
    $error['message'] = $message;
    $error['code']    = $code;

    if ( ! empty($errors)) {
        $error['errors'] = $errors;
    }
    if ( ! empty($data)) {
        $error['data'] = $data;
    }

    return response()->json($error, $status, $headers);
}

function validation_error($code, $message){
    return [
        'flag'    => false,
        'code'    => $code,
        'message' => $message,
    ];
}
