<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Access;
use App\Exceptions\TechnicalException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
// ...............   example
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (TechnicalException $exception) {
            Log::channel($exception->getLogChannel())->log(
                $exception->getLogLevel(),
                $exception->getMessage(),
                [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );
        });
// .................. example
    })->create();
