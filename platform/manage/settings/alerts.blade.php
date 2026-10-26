@extends('manage.layouts.workspace')
@section('title', 'Alerts')
@section('heading', 'Alerts')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'alerts') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf
        <h2 class="text-lg font-bold text-gray-900 mb-1">Alerts</h2>
        <p class="text-sm text-gray-500 mb-6">Thresholds for the notification center (Section 5.5).</p>

        <div class="space-y-6 mb-8">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Low-stock alert at or below</label>
                <input type="number" name="low_stock_alert" value="{{ $settings['low_stock_alert'] ?? 5 }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Expiry alert, days before</label>
                <input type="number" name="expiry_alert_days" value="{{ $settings['expiry_alert_days'] ?? 7 }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
            </div>
        </div>

        <button type="submit" class="w-full bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold py-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i> Save alert settings
        </button>
    </form>
</div>
@endsection