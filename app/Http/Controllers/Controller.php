<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    static public function success($data = [], $msg = ''): JsonResponse
    {
        return response()->json([
            'bool' => true,
            'result' => $data,
            'message' => $msg,
        ]);
    }

    static public function error($msg, $data = []): JsonResponse
    {
        return response()->json([
            'bool' => false,
            'message' => $msg,
            'errors' => $data
        ], 422);
    }
}
