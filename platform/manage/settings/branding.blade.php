@extends('manage.layouts.workspace')
@section('title', 'Branding')
@section('heading', 'Branding')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'branding') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf
        <h2 class="text-lg font-bold text-gray-900 mb-1">Shop name</h2>
        <p class="text-sm text-gray-500 mb-6">Printed at the top of every receipt and PDF invoice, in place of "Suweka POS".</p>

        <div class="mb-8">
            <label class="block text-sm text-gray-600 mb-1">Shop name</label>
            <input type="text" name="shop_name" value="{{ $settings['shop_name'] ?? 'Suweka POS' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
        </div>

        <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i> Save shop name
        </button>
    </form>
</div>
@endsection