{{-- ------------------- Admin sign in code ------------------- --}}
@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px; font-size:16px; font-weight:bold;">Hello {{ $name ?? 'there' }},</p>

    <p style="margin:0; font-size:14px; line-height:22px; color:#7f8399;">
        Use this code to finish signing in to the admin panel.
    </p>

    @include('emails.partials.code', ['code' => $code, 'codeLabel' => 'Sign in code'])

    <p style="margin:0; font-size:13px; line-height:21px; color:#7f8399;">
        If this was not you, someone has your password. Change it as soon as you can.
    </p>
@endsection
