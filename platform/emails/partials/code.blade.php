{{-- ------------------- The code, set large and spaced ------------------- --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
    <tr>
        <td align="center" style="background:#f8f9fa; border:1px solid #d5d6de; padding:20px;">
            <span style="display:block; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:#7f8399;">
                {{ $codeLabel ?? 'Your code' }}
            </span>
            <span style="display:block; font-size:34px; font-weight:bold; letter-spacing:8px; color:#a17225; margin-top:8px;">
                {{ $code }}
            </span>
            <span style="display:block; font-size:12px; color:#7f8399; margin-top:8px;">
                Expires in ten minutes
            </span>
        </td>
    </tr>
</table>
