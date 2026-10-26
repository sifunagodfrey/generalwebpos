@extends('manage.layouts.workspace')
@section('title', 'Store Network')
@section('heading', 'Store Network')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif
    
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Store Network</h2>
        <p class="text-sm text-gray-500 mt-1">Mode: standalone. Standalone is the default for a shop with one POS.</p>
        <p class="text-sm text-gray-500">Pairing does not merge or erase this device's existing data. Terminal sales are saved on the master. Each installation retains its licence and local printer settings.</p>
    </div>

    <form action="{{ route('manage.settings.save', 'network') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm text-gray-500 mb-1">This device name</label>
            <input type="text" value="DESKTOP-QIUK76I" disabled class="w-full border-0 border-b border-gray-400 bg-transparent text-gray-500 py-2 px-0 outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-500 mb-1">Store name</label>
            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'My store' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
        </div>

        <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-server"></i> Set as master and start hosting
        </button>
    </form>

    <div class="mt-10 border-t border-gray-200 pt-8">
        <h3 class="font-bold text-gray-900 mb-4">Connect to a master</h3>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Paste the master pairing code</label>
            <input type="text" id="pairingCode" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
        </div>
        
        <div class="flex flex-wrap gap-3 mt-6">
            <button type="button" onclick="alert('Camera scanner would open here.')" class="border border-gray-400 text-[#713FCE] px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 hover:bg-gray-50 transition">
                <i class="fa-solid fa-qrcode"></i> Scan pairing QR
            </button>
            <button type="button" onclick="if(document.getElementById('pairingCode').value === '') { alert('Please enter a pairing code first.'); } else { alert('Requesting pairing...'); }" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm">
                Request pairing
            </button>
        </div>
    </div>
</div>
@endsection