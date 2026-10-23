{{-- ------------------- A staff account was removed ------------------- --}}
@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px; font-size:16px; font-weight:bold;">Hello {{ $name ?? 'there' }},</p>

    <p style="margin:0; font-size:14px; line-height:22px; color:#7f8399;">
        Your access to the Duramax Mabati admin panel has been removed. You will no longer be
        able to sign in.
    </p>

    <p style="margin:18px 0 0; font-size:13px; line-height:21px; color:#7f8399;">
        If you believe this is a mistake, speak to whoever manages the panel.
    </p>
@endsection
