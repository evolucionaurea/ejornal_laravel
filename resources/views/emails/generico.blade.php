<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'Notificación' }}</title>
</head>

<body style="margin:0; padding:20px; background-color:#f2f4f6; font-family:Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                    style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); overflow:hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#4a5568; padding:20px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:normal;">{{ $titulo ??
                                '¡Hola!' }}</h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px; color:#333333; font-size:16px; line-height:1.5;">
                            <p style="margin-top:0;">{{ $mensaje ?? 'Este es el contenido de tu mensaje.' }}</p>

                            @if(!empty($extra))
                            <p
                                style="margin:20px 0; padding:15px; background-color:#f7fafc; border-left:4px solid #4a5568;">
                                <strong>Información extra:</strong><br>
                                {{ $extra }}
                            </p>
                            @endif

                            {{-- Ejemplo de botón CTA --}}
                            @if(!empty($actionUrl) && !empty($actionText))
                            <p style="text-align:center; margin:30px 0;">
                                <a href="{{ $actionUrl }}"
                                    style="display:inline-block; padding:12px 24px; background-color:#4a5568; color:#ffffff; text-decoration:none; border-radius:4px; font-weight:bold;">
                                    {{ $actionText }}
                                </a>
                            </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="background-color:#f2f4f6; padding:20px; text-align:center; color:#888888; font-size:12px;">
                            <p style="margin:0;">© {{ date('Y') }} Tu Empresa. Todos los derechos reservados.</p>
                            <p style="margin:5px 0 0;">
                                <a href="{{ $unsubscribeUrl ?? '#' }}"
                                    style="color:#888888; text-decoration:underline;">Darme de baja</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>