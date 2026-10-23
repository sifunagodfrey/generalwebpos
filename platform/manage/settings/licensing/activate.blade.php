@extends('manage.layouts.workspace')
@section('title', 'Activate Suweka POS')
@section('heading', 'Activate Suweka POS')
@section('content')
<div style="max-width:520px;margin:24px auto;background:#fff;border:1px solid #e4d8fa;border-radius:14px;padding:26px">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:12px;background:#f2ebfd;color:#713FCE;font-size:24px"><i class="fa-solid fa-key" aria-hidden="true"></i></div>
    <h2 style="font-size:22px;font-weight:700;margin:14px 0 18px;color:#263249">Activate your license</h2>
    @if(session('status'))
        <div role="status" style="padding:12px;margin-bottom:16px;background:#e5f7ee;color:#076f45;border-radius:8px">{{ session('status') }}</div>
    @endif
    @if($active)
        <div role="status" style="padding:14px;background:#e5f7ee;color:#076f45;border-radius:8px">License active</div>
    @else
        @if($errors->any())
            <div role="alert" style="padding:12px;margin-bottom:16px;background:#fff0ed;color:#a52a18;border-radius:8px">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('manage.license.activate') }}" style="display:grid;gap:14px" x-data="{sending:false}" @submit="sending=true">
            @csrf
            <label for="su-license-key" style="font-size:14px;font-weight:600;color:#263249">Product key</label>
            <input id="su-license-key" type="text" name="license_key" value="{{ old('license_key') }}" placeholder="Enter product key" autocomplete="off" autocapitalize="characters" spellcheck="false" required maxlength="150" style="width:100%;padding:13px 14px;border:1px solid #d9c8f5;border-radius:9px;outline-color:#713FCE;font-size:15px;box-sizing:border-box">
            <button type="submit" :disabled="sending" style="background:#713FCE;color:#fff;border:0;border-radius:9px;padding:13px;font-weight:700;cursor:pointer" x-text="sending ? 'Activating…' : 'Activate'"></button>
        </form>
    @endif
    <a href="{{ route('manage.workspace.sale',['page'=>'new']) }}" style="display:inline-block;margin-top:18px;color:#713FCE;text-decoration:none;font-weight:600">← Back to Sale</a>
</div>
@endsection
