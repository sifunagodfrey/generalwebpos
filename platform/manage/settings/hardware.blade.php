@extends('manage.layouts.workspace')
@section('title', 'Hardware')
@section('heading', 'Hardware')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6 space-y-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'hardware') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf
        <h2 class="text-lg font-bold text-gray-900 mb-1">Receipt printer</h2>
        <p class="text-sm text-gray-500 mb-6">Choose a default receipt printer (Section 9 Phase 6).</p>

        <div class="mb-6">
            <label class="block text-sm text-gray-600 mb-1">Receipt printer</label>
            <select name="printer" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition cursor-pointer">
                <option value="ask" @selected(($settings['printer'] ?? '') === 'ask')>Ask every time</option>
                <option value="default" @selected(($settings['printer'] ?? '') === 'default')>Default system printer</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i> Save printer settings
        </button>
    </form>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Barcode scanner</h2>
        <p class="text-sm text-gray-500 mb-6">A USB or Bluetooth scanner needs no setup here, it types like a keyboard. Scan something below to confirm it is working.</p>

        <div class="relative">
            <i class="fa-solid fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input type="text" id="barcodeTest" placeholder="Scan a barcode to test" onkeydown="if(event.key === 'Enter') { event.preventDefault(); alert('Barcode scanned successfully: ' + this.value); this.value=''; }" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 bg-transparent focus:ring-1 focus:ring-[#713FCE] outline-none transition text-gray-900">
        </div>
    </div>
</div>
@endsection