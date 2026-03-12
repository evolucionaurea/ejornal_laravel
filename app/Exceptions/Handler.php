<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\PostTooLargeException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            Error::create([
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'user_id' => Auth::check() ? Auth::id() : null,
            ]);
        }

        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof PostTooLargeException) {
            return redirect()
                ->back()
                ->with('error', 'La carga supera el límite permitido. Máximo 2MB por archivo.')
                ->withInput();
        }

        return parent::render($request, $exception);
    }
}