<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'eJornal' }}</title>
</head>

<body style="margin:0; padding:20px; background-color:#f2f4f6; font-family:Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                    style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#4a5568; padding:18px 22px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="vertical-align:middle; width:48px;">
                                        <img src="{{ url('img/logos/isologo.png') }}" alt="eJornal" width="36"
                                            style="display:block; border:0; outline:none; text-decoration:none;">
                                    </td>
                                    <td style="vertical-align:middle; padding-left:10px;">
                                        <h1 style="margin:0; color:#ffffff; font-size:22px; font-weight:normal;">
                                            {{ $titulo ?? 'eJornal' }}
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px 30px; color:#333333; font-size:16px; line-height:1.55;">
                            <p style="margin:0 0 16px 0;">
                                Estimado <strong>{{ $nomina_nombre ?? 'trabajador' }}</strong>,
                                le enviamos este email para acercarle la receta generada por el profesional
                                <strong>{{ $medico_nombre ?? 'médico' }}</strong>.
                            </p>

                            @if(!empty($pdf_url))
                            <p style="margin:22px 0 10px; text-align:center;">
                                <a href="{{ $pdf_url }}"
                                    style="display:inline-block; padding:12px 22px; background-color:#4a5568; color:#ffffff; text-decoration:none; border-radius:6px; font-weight:bold;">
                                    Ver / Descargar receta (PDF)
                                </a>
                            </p>

                            <p style="margin:10px 0 0; font-size:12px; color:#718096; text-align:center;">
                                Si el botón no funciona, copiá y pegá este link:<br>
                                <span style="word-break:break-all;">{{ $pdf_url }}</span>
                            </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="background-color:#f2f4f6; padding:16px 22px; text-align:center; color:#888888; font-size:12px;">
                            <p style="margin:0;">© {{ date('Y') }} eJornal. Todos los derechos reservados.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>