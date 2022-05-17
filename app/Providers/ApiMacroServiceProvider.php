<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ApiMacroServiceProvider extends ServiceProvider
{
    public function boot() {
        Response::macro('api', function(bool $is_success, string $description, int $code = 400, $data = null, string $message = null) {
            return response()->json([
                'success' => $is_success,
                'message' => ($is_success ? "Success" : $message),
                'description' => $description,
                'data' => $data,
            ], $code);
        });
    }
}
