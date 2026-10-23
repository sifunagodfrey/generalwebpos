{{-- ------------------- A staff account was created ------------------- --}}
@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px; font-size:16px; font-weight:bold;">Welcome, {{ $name ?? 'there' }}</p>

    <p style="margin:0 0 4px; font-size:14px; line-height:22px; color:#7f8399;">
        An account has been created for you on the Duramax Mabati admin panel.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
           style="margin:22px 0; border:1px solid #d5d6de; font-size:13px;">
        <tr>
            <td style="padding:10px 14px; background:#f8f9fa; width:38%;">Email</td>
            <td style="padding:10px 14px;">{{ $email }}</td>
        </tr>
        <tr>
            <td style="padding:10px 14px; background:#f8f9fa;">Password</td>
            <td style="padding:10px 14px; font-family:monospace; font-size:14px;">{{ $password }}</td>
        </tr>
    </table>

    @include('emails.partials.button', ['url' => $loginUrl, 'label' => 'Sign in'])

    <p style="margin:0; font-size:13px; line-height:21px; color:#7f8399;">
        Change that password once you are in. It was sent by email, so treat it as temporary.
    </p>
@endsection
