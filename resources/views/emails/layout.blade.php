<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f4eeea;font-family:Arial,Helvetica,sans-serif;color:#2b2422">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center">
            <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden">
                <tr><td style="background:#7A2B25;color:#ffffff;padding:18px 24px;font-size:18px;font-weight:bold">{{ setting('site_name') }}</td></tr>
                <tr><td style="padding:24px;font-size:14px;line-height:1.6">@yield('body')</td></tr>
                <tr><td style="padding:14px 24px;background:#faf6f3;font-size:12px;color:#7a6d69">{{ setting('contact_address') }} · {{ setting('contact_phone') }}</td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
