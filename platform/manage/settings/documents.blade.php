@extends('manage.layouts.workspace')
@section('title', 'Documents')
@section('heading', 'Documents')

@section('content')
<div class="max-w-4xl mx-auto pb-20 md:pb-6 space-y-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'documents') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Company header and logo</h2>
            <p class="text-sm text-gray-500 mb-6">Logo on the left; shop contact details on the right.</p>

            <button type="button" onclick="document.getElementById('logoInput').click()" class="border border-gray-400 text-[#713FCE] px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-50 transition mb-8">
                <i class="fa-regular fa-image"></i> Choose logo
            </button>
            <input type="file" id="logoInput" name="logo" class="hidden" accept="image/*" onchange="alert('Logo selected: ' + this.files[0].name)">

            <div class="space-y-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Company contact details</label>
                    <input type="text" name="contact_details" value="{{ $settings['contact_details'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Currency label</label>
                    <input type="text" name="currency_label" value="{{ $settings['currency_label'] ?? 'KSh' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Document footer</label>
                    <input type="text" name="footer" value="{{ $settings['footer'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ color: '{{ $settings['accent_color'] ?? '#713FCE' }}' }">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Document accent color</h2>
            <p class="text-sm text-gray-500 mb-6">Document titles, table headings and total highlights use this color.</p>

            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg shadow-inner" :style="`background-color: ${color}`"></div>
                <div class="flex-1">
                    <label class="block text-sm text-gray-600 mb-1">Color (hex)</label>
                    <input type="text" name="accent_color" x-model="color" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition uppercase font-mono">
                </div>
            </div>
            
            <div class="flex gap-2">
                @foreach(['#713FCE', '#10b981', '#f59e0b', '#ef4444', '#1f2937', '#0f766e'] as $swatch)
                    <button type="button" @click="color = '{{ $swatch }}'" class="w-8 h-8 rounded-lg shadow-sm border border-gray-200" style="background-color: {{ $swatch }}"></button>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Payment details</h2>
            <p class="text-sm text-gray-500 mb-6">Shown on every generated Invoice and Quotation, between the items and the closing thank-you line - a bank account, till number, paybill or phone number for mobile money, whatever this shop takes.</p>

            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Payment details</label>
                <textarea name="payment_details" rows="3" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">{{ $settings['payment_details'] ?? '' }}</textarea>
            </div>

            <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i> Save document settings
            </button>
        </div>
    </form>
</div>
@endsection