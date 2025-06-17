<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {


        $exceptions->render(function (Throwable $e, Request $request) {
            Log::error($e);
            // 2. Siapkan informasi debug HANYA JIKA APP_DEBUG=true
            $debugInfo = null;
            if (config('app.debug')) {
                $debugInfo = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ];
            }
            $statusCode = $e instanceof HttpException
                ? $e->getStatusCode()
                : 500;
            return response()->view('errors.default', [
                'exception' => $e,
                'debugInfo' => $debugInfo,
            ], $statusCode);
        });

    })
    ->withSchedule(function (Schedule $schedule) {
        // tentukan pemenang setiap jam
        $schedule->command('lelang-set-winner')->hourly();
    })
    ->create();
