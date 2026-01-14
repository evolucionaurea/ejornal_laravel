<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class EnviarEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array */
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = $this->data['titulo'] ?? 'Notificación';
        $view    = $this->data['view'] ?? 'emails.recetas';

        $mail = $this->subject($subject)
            ->view($view)
            ->with($this->data);

        /**
         * Adjuntos (OPCIONAL) - 2 formas:
         *
         * A) Path (ideal / queue-safe):
         * 'archivo' => ['path' => '/abs/path/receta.pdf', 'as' => 'receta.pdf', 'mime' => 'application/pdf']
         *
         * B) UploadedFile (si viene en request):
         * 'archivo' => $req->file('archivo')
         */
        if (!empty($this->data['archivo'])) {

            // B) UploadedFile
            if ($this->data['archivo'] instanceof UploadedFile) {
                $file = $this->data['archivo'];

                $mail->attach($file->getRealPath(), [
                    'as'   => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                ]);

                return $mail;
            }

            // A) Path (array)
            if (is_array($this->data['archivo'])) {
                $path = $this->data['archivo']['path'] ?? null;
                $name = $this->data['archivo']['as'] ?? 'archivo.pdf';
                $mime = $this->data['archivo']['mime'] ?? 'application/octet-stream';

                if (!empty($path) && file_exists($path)) {
                    $mail->attach($path, [
                        'as'   => $name,
                        'mime' => $mime,
                    ]);
                }

                return $mail;
            }
        }

        return $mail;
    }
}
