{{--
    Renders submitted form data as a clean key/value table.
    Props: $rows — associative array ['Label' => $value, ...]; rows with a
    null/empty value are skipped.

    SECURITY: every value is rendered via {{ }} (escaped), never {!! !!} —
    these values come directly from public, unauthenticated form
    submissions. Never change this to raw/unescaped output.
--}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 16px 0; border-collapse: collapse;">
    @foreach ($rows as $label => $rowValue)
        @if ($rowValue !== null && $rowValue !== '')
            <tr>
                <td style="padding: 8px 12px; background-color:#f8f9fa; font-weight:600; width: 140px; border:1px solid #eaeaea; vertical-align:top; font-size:13px; color:#555;">
                    {{ $label }}
                </td>
                <td style="padding: 8px 12px; border:1px solid #eaeaea; font-size:14px; color:#333; word-break:break-word; white-space:pre-line;">
                    {{ $rowValue }}
                </td>
            </tr>
        @endif
    @endforeach
</table>
