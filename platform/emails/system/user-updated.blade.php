{{-- ------------------- A staff account was changed ------------------- --}}
@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px; font-size:16px; font-weight:bold;">Hello {{ $name ?? 'there' }},</p>

    <p style="margin:0; font-size:14px; line-height:22px; color:#7f8399;">
        Your admin account has been updated.
    </p>

    @if (! empty($changes))
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
               style="margin:20px 0; border:1px solid #d5d6de; font-size:13px;">
            @foreach ($changes as $field => $value)
                <tr>
                    <td style="padding:9px 14px; background:#f8f9fa; width:38%;">
                        {{ \Illuminate\Support\Str::headline((string) $field) }}
                    </td>
                    <td style="padding:9px 14px;">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <p style="margin:20px 0 0; font-size:13px; line-height:21px; color:#7f8399;">
        If this was not you or you were not expecting it, tell an administrator straight away.
    </p>
@endsection
