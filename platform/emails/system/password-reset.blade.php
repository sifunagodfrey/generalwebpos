{{-- ------------------- Admin password reset code ------------------- --}}
@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px; font-size:16px; font-weight:bold;">Hello {{ $name ?? 'there' }},</p>

    <p style="margin:0; font-size:14px; line-height:22px; color:#7f8399;">
        Use this code to set a new password for the admin panel.
    </p>

    @include('emails.partials.code', ['code' => $code, 'codeLabel' => 'Reset code'])

    <p style="margin:0; font-size:13px; line-height:21px; color:#7f8399;">
        If you did not ask for this, ignore this message. Your password stays as it is.
    </p>
@endsection
