{{-- -------------------
     Email shell.

     Table based and inline styled, because mail clients ignore stylesheets
     and most of them ignore modern layout entirely. Squared corners keep it
     in step with the site, and nothing here depends on an image loading.
------------------- --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Duramax Mabati' }}</title>
</head>
<body style="margin:0; padding:0; background:#f8f9fa; font-family:Helvetica,Arial,sans-serif; color:#161d45;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fa; padding:28px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                   style="max-width:560px; background:#ffffff; border:1px solid #d5d6de;">

                {{-- Head --}}
                <tr>
                    <td style="background:#f7af39; padding:22px 28px;">
                        <span style="display:block; color:#161d45; font-size:19px; font-weight:bold; letter-spacing:-0.3px;">
                            {{ $company ?? 'Duramax Mabati' }}
                        </span>
                        <span style="display:block; color:rgba(22,29,69,0.7); font-size:11px; letter-spacing:2px; text-transform:uppercase; margin-top:4px;">
                            Maximum Durability!
                        </span>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:32px 28px;">
                        @yield('body')
                    </td>
                </tr>

                {{-- Foot --}}
                <tr>
                    <td style="background:#161d45; padding:20px 28px;">
                        <p style="margin:0 0 6px; color:rgba(255,255,255,0.75); font-size:12px; line-height:19px;">
                            @if (! empty($phone))
                                {{ $phone }}
                            @endif
                            @if (! empty($email))
                                &nbsp;&middot;&nbsp; <a href="mailto:{{ $email }}" style="color:#ffffff; text-decoration:none;">{{ $email }}</a>
                            @endif
                        </p>
                        <p style="margin:0; color:rgba(255,255,255,0.45); font-size:11px;">
                            &copy; {{ date('Y') }} {{ $company ?? 'Duramax Mabati' }}
                        </p>
                    </td>
                </tr>

            </table>

            <p style="max-width:560px; margin:14px auto 0; color:#7f8399; font-size:11px; text-align:center;">
                This message was sent by {{ $company ?? 'Duramax Mabati' }}.
            </p>

        </td>
    </tr>
</table>

</body>
</html>
