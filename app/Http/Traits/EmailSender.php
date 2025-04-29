<?php
// app/Traits/EmailSender.php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarEmail;

trait EmailSender
{
    /**
     * Valida los datos y encola el envío.
     * Lanza ValidationException o cualquier excepción de Mail si falla.
     *
     * @param  Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function enviarEmail(Request $request): void
    {
        // 1. Validación: lanza ValidationException si algo falla
        $datos = $request->validate([
            'destino' => 'required|email',
            'titulo'  => 'required|string',
            'mensaje' => 'required|string',
            'archivo' => 'nullable|file|max:5120',
        ]);

        // 2. Incluir UploadedFile en el array de datos, si existe
        if ($file = $request->file('archivo')) {
            $datos['archivo'] = $file;
        }

        // 3. Enviar a la cola; si Mail::queue falla, lanza excepción
        Mail::to($datos['destino'])
            ->queue(new EnviarEmail($datos));
    }
}
