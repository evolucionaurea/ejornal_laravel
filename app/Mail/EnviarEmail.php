<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class EnviarEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array Datos (título, mensaje, destino, archivo, etc.) */
    public $datos;

    /**
     * @param array $datos Debe incluir al menos:
     *                     - 'destino'  => email destinatario
     *                     - 'titulo'   => asunto del correo
     *                     - 'mensaje'  => cuerpo del correo
     *                     - opcional 'archivo' => UploadedFile
     */
    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    /**
     * Construye y retorna el correo.
     *
     * @return $this
     * @throws \Exception
     */
    public function build()
    {
        try {
            $email = $this->subject($this->datos['titulo'] ?? 'Notificacion')
                ->view('emails.generico')
                ->with($this->datos);

            // si llega UploadedFile en $datos['archivo'], lo adjuntamos
            if (!empty($this->datos['archivo']) && $this->datos['archivo'] instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $this->datos['archivo'];
                $path = $file->getRealPath();

                if (File::exists($path)) {
                    Log::info("Adjuntando archivo: {$path}");
                    $email->attach($path, [
                        'as'   => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                } else {
                    Log::error("Archivo no encontrado: {$path}");
                }
            }

            return $email;
        } catch (\Exception $e) {
            Log::error('Error al construir el correo: ' . $e->getMessage());
            throw $e;
        }
    }
}
