@extends('manage.layouts.workspace')
@section('title', 'VAT')
@section('heading', 'VAT')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'vat') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ enabled: {{ isset($settings['vat_enabled']) && $settings['vat_enabled'] == '1' ? 'true' : 'false' }} }">
        @csrf
        <h2 class="text-lg font-bold text-gray-900 mb-1">VAT</h2>
        <p class="text-sm text-gray-500 mb-6">Optional, configured once here (Section 5.6). A product's own tax type still decides whether VAT applies to it at all.</p>

        <div class="flex items-center justify-between mb-8">
            <span class="text-gray-700">VAT enabled</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="vat_enabled" value="0">
                <input type="checkbox" name="vat_enabled" value="1" class="sr-only peer" x-model="enabled">
                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#713FCE]"></div>
            </label>
        </div>

        <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i> Save VAT settings
        </button>
    </form>
</div>
@endsection