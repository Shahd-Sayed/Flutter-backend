<?php

namespace App\Helpers\Classes;

class ResponseHelpers{

    public static function endRequest(\Illuminate\Http\JsonResponse $response){
        $response->send();
        die;
    }

    public static function dump(...$data){
        response()->json(count($data) > 1 ? $data : $data[0])->send();
    }

    public static function dd(...$data){
        self::endRequest(response()->json(count($data) > 1 ? $data : $data[0], 500));
    }

    public static function send($success, $message, $data, $code = 200){
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function success($message, $data = null, $code = 200){
        return self::send(true, $message, $data, $code);
    }

    public static function error($message, $data = null, $code = 400){
        return self::send(false, $message, $data, $code);
    }

    public static function unauthenticated($message = null, $data = null, $code = 401){
        return self::error($message ?? __('messages.unauthenticated'), $data, $code);
    }

    public static function unauthorized($message = null, $data = null, $code = 401){
        return self::error($message ?? __('messages.unauthorized'), $data, $code);
    }

    public static function notFound($message = null, $data = null, $code = 404){
        return self::error($message ?? __('messages.not_found'), $data, $code);
    }

    public static function badRequest(\Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null $__)
    {
        return self::error($__ ?? __('messages.bad_request'), null, 400);
    }
}
