<?php

/**
 * Success response method
 *
 * @param $result
 * @param $message
 * @return \Illuminate\Http\JsonResponse
 */
function sendResponse($data)
{
    $response = [
        'success' => true,
        'data'    => $data,
    ];

    return response()->json($response, 200);
}

/**
 * Return error response
 *
 * @param       $error
 * @param int   $code
 * @return \Illuminate\Http\JsonResponse
 */
function sendError($message, $code = 404)
{
    $response = [
        'success' => false,
        'message' => $message,
    ];

    return response()->json($response, $code);
}
