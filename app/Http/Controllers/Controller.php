<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a successful JSON response.
     *
     * Mirrors the error envelope used across the resource controllers,
     * e.g. response(['error' => 1, 'message' => '...'], 409).
     *
     * @param  mixed  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithSuccess($data = null, $status = 200)
    {
        return response()->json(['error' => 0, 'data' => $data], $status);
    }
}
