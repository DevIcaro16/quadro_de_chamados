<?php

namespace App\Traits;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HttpResponses
{

    public function response(string $message, string|int $statuss, array|Model|JsonResource $data = [])
    {
        return response()->json([
            'message' => $message,
            'statuss' => $statuss,
            'data' => $data
        ], $statuss);
    }

    public function error(string $message, string|int $statuss, array|MessageBag $errors = [], array|Model $data = [])
    {
        return response()->json([
            'message' => $message,
            'statuss' => $statuss,
            'errors' => $errors,
            'data' => $data
        ], $statuss);
    }

}

?>