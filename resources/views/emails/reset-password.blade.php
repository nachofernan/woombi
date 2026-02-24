<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña — Woombi</title>
</head>
<body style="margin:0;padding:0;background-color:#001529;font-family:Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#001529;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="padding-bottom:32px;">
                            <p style="margin:0;font-size:36px;font-weight:900;color:#facc15;letter-spacing:2px;">WOOMBI</p>
                            <p style="margin:6px 0 0;font-size:9px;font-weight:900;color:rgba(255,255,255,0.5);letter-spacing:6px;text-transform:uppercase;">Prode Mundial 2026</p>
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td style="background-color:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:32px;padding:40px;">

                            <p style="margin:0 0 8px;font-size:9px;font-weight:900;color:rgba(255,255,255,0.4);letter-spacing:5px;text-transform:uppercase;text-align:center;">
                                Recuperación de contraseña
                            </p>

                            <p style="margin:24px 0;font-size:13px;color:rgba(255,255,255,0.7);line-height:1.8;text-align:center;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta.
                                Si no fuiste vos, podés ignorar este mail.
                            </p>

                            {{-- Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}"
                                            style="display:inline-block;background-color:#002868;color:#ffffff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:3px;text-decoration:none;padding:16px 40px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:10px;color:rgba(255,255,255,0.3);text-align:center;line-height:1.8;">
                                Este link expira en {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutos.<br>
                                Si el botón no funciona, copiá este link en tu navegador:
                            </p>

                            <p style="margin:12px 0 0;font-size:10px;color:rgba(250,204,21,0.6);text-align:center;word-break:break-all;">
                                {{ $actionUrl }}
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="margin:0;font-size:9px;color:rgba(255,255,255,0.2);letter-spacing:2px;text-transform:uppercase;">
                                © 2026 Woombi — Prode Mundial
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>