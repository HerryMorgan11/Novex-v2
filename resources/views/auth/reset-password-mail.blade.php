@php
  $greetingName = $name ?: $email;
@endphp

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restablecer contraseña</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f7fb;padding:24px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="width:600px;max-width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,.06);">
          <tr>
            <td style="padding:24px 28px;background:#0f172a;color:#fff;">
              <div style="font-size:16px;font-weight:700;letter-spacing:.2px;">
                {{ $appName }}
              </div>
              <div style="opacity:.85;font-size:13px;margin-top:6px;">
                Restablecimiento de contraseña
              </div>
            </td>
          </tr>

          <tr>
            <td style="padding:28px;">
              <h1 style="margin:0 0 12px 0;font-size:20px;line-height:1.25;color:#0f172a;">
                Hola, {{ $greetingName }}
              </h1>

              <p style="margin:0 0 14px 0;font-size:14px;line-height:1.6;color:#334155;">
                Hemos recibido una solicitud para restablecer la contraseña de tu cuenta (<strong>{{ $email }}</strong>).
              </p>

              <p style="margin:0 0 18px 0;font-size:14px;line-height:1.6;color:#334155;">
                Haz clic en el botón para crear una nueva contraseña. Este enlace caduca en <strong>{{ $minutes }}</strong> minutos.
              </p>

              <div style="margin:22px 0 18px 0;">
                <a href="{{ $resetUrl }}"
                   style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:700;font-size:14px;">
                  Cambiar contraseña
                </a>
              </div>

              <p style="margin:0 0 10px 0;font-size:13px;line-height:1.6;color:#64748b;">
                Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña no se modificará.
              </p>

              <p style="margin:16px 0 0 0;font-size:12px;line-height:1.6;color:#94a3b8;">
                ¿El botón no funciona? Copia y pega este enlace en tu navegador:
                <br>
                <span style="word-break:break-all;color:#475569;">{{ $resetUrl }}</span>
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:18px 28px;background:#f8fafc;color:#64748b;font-size:12px;line-height:1.5;">
              © {{ date('Y') }} {{ $appName }}. Si necesitas ayuda, responde a este correo.
            </td>
          </tr>
        </table>

        <div style="font-size:12px;color:#94a3b8;margin-top:14px;">
          Este correo fue enviado a {{ $email }}.
        </div>
      </td>
    </tr>
  </table>
</body>
</html>