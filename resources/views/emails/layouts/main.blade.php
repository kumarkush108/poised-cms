<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine ?? $siteName }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #f4f4f7; }
        a { color: {{ $primaryColor }}; }
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .email-padding { padding-left: 20px !important; padding-right: 20px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f7;">
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <table role="presentation" class="email-container" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="background-color: {{ $primaryColor }}; padding: 28px 24px;">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 44px; display:block;">
                            @else
                                <span style="font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">
                                    {{ $siteName }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td class="email-padding" style="padding: 36px 40px; color: #333333; font-size: 15px; line-height: 1.6;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="email-padding" style="padding: 24px 40px; background-color: #f8f9fa; border-top: 1px solid #eaeaea; font-size: 12px; color: #888888; line-height: 1.6;">
                            <p style="margin: 0 0 6px;">
                                <strong style="color:#555;">{{ $siteName }}</strong>
                                @if ($address)
                                    &middot; {{ $address }}
                                @endif
                            </p>
                            @if ($contactEmail || $contactPhone)
                                <p style="margin: 0 0 6px;">
                                    @if ($contactEmail)
                                        <a href="mailto:{{ $contactEmail }}" style="color:#888888; text-decoration:underline;">{{ $contactEmail }}</a>
                                    @endif
                                    @if ($contactEmail && $contactPhone) &middot; @endif
                                    @if ($contactPhone)
                                        {{ $contactPhone }}
                                    @endif
                                </p>
                            @endif
                            <p style="margin: 12px 0 0;">
                                &copy; {{ now()->year }} {{ $copyrightText }}
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
