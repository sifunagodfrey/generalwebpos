@extends('manage.layouts.workspace')
@section('title', 'M-Pesa')
@section('heading', 'M-Pesa')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6 space-y-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="mb-4 bg-red-100 text-red-800 p-4 rounded-xl">{{ session('error') }}</div> @endif

    <form action="{{ route('manage.settings.save', 'mpesa') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" x-data="{ enable: {{ isset($settings['mpesa_enabled']) && $settings['mpesa_enabled'] == '1' ? 'true' : 'false' }} }">
            <h2 class="text-lg font-bold text-gray-900 mb-1">M-Pesa payments</h2>
            <p class="text-sm text-gray-500 mb-6">Accept phone prompts and see customers who pay directly to your Till or Paybill.</p>

            <div class="flex items-center justify-between mb-6">
                <span class="text-gray-700">Enable M-Pesa integration</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="mpesa_enabled" value="0">
                    <input type="checkbox" name="mpesa_enabled" value="1" class="sr-only peer" x-model="enable">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#713FCE]"></div>
                </label>
            </div>

            <div class="flex items-center justify-between mb-6 opacity-50">
                <div>
                    <span class="text-gray-700 block">Enable STK Push</span>
                    <span class="text-xs text-gray-500">Turning this off keeps direct-payment confirmations available.</span>
                </div>
                <label class="relative inline-flex items-center cursor-not-allowed">
                    <input type="checkbox" class="sr-only peer" disabled>
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5"></div>
                </label>
            </div>

            <div class="flex items-center gap-3 text-gray-600">
                <i class="fa-solid fa-link-slash text-xl"></i>
                <div>
                    <p class="font-medium text-gray-900">Not connected yet</p>
                    <p class="text-sm">Register direct-payment confirmations after saving.</p>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-1">No payment account configured</h3>
            <p class="text-sm text-gray-500">Environment: sandbox</p>
            <p class="text-sm text-gray-500 mb-4">No payment account linked</p>
            
            <button type="button" onclick="alert('Requesting a shared account from Suweka...')" class="w-full border border-gray-400 text-[#713FCE] font-medium py-2.5 rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-link"></i> Request a shared account
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Business account</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Account type</label>
                    <select name="mpesa_account_type" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition cursor-pointer">
                        <option value="till" @selected(($settings['mpesa_account_type'] ?? '') === 'till')>Till / Buy Goods</option>
                        <option value="paybill" @selected(($settings['mpesa_account_type'] ?? '') === 'paybill')>Paybill</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Business / Store shortcode</label>
                    <input type="text" name="mpesa_shortcode" value="{{ $settings['mpesa_shortcode'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Receiving Till number</label>
                    <input type="text" name="mpesa_till" value="{{ $settings['mpesa_till'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                    <p class="text-xs text-gray-500 mt-1">Leave blank when it is the same as the business shortcode.</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Environment</label>
                    <select name="mpesa_env" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition cursor-pointer">
                        <option value="sandbox" @selected(($settings['mpesa_env'] ?? '') === 'sandbox')>Sandbox - testing</option>
                        <option value="production" @selected(($settings['mpesa_env'] ?? '') === 'production')>Production</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Daraja credentials</h2>
            <p class="text-sm text-gray-500 mb-6">Sent securely to Suweka and encrypted on the server. These secrets are never saved in the POS database.</p>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Consumer Key</label>
                    <input type="password" name="mpesa_consumer_key" value="{{ $settings['mpesa_consumer_key'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Consumer Secret</label>
                    <input type="password" name="mpesa_consumer_secret" value="{{ $settings['mpesa_consumer_secret'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Lipa na M-Pesa Passkey</label>
                    <input type="password" name="mpesa_passkey" value="{{ $settings['mpesa_passkey'] ?? '' }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none transition">
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save settings
            </button>
            
            <!-- Inactive / Disabled Test connection button -->
            <button type="button" disabled title="Enable and save M-Pesa integration first" class="border border-gray-300 text-gray-400 px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 cursor-not-allowed opacity-70 bg-gray-50">
                <i class="fa-solid fa-satellite-dish"></i> Test connection
            </button>
            
            <!-- Inactive / Disabled Register confirmations button -->
            <button type="button" disabled title="Enable and save M-Pesa integration first" class="border border-gray-300 text-gray-400 px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 cursor-not-allowed opacity-70 bg-gray-50">
                <i class="fa-solid fa-bell"></i> Register confirmations
            </button>
            
            <a href="#" class="text-[#713FCE] font-medium hover:underline px-2" onclick="event.preventDefault(); window.location.reload();">Refresh from server</a>
        </div>
        
        <div class="mt-4">
            <button type="submit" name="mpesa_enabled" value="0" class="text-[#713FCE] font-medium hover:underline" onclick="return confirm('Are you sure you want to disable M-Pesa on this POS?')">Disable on this POS</button>
        </div>
        
        <p class="text-sm text-gray-500 mt-4">Payment access or setup permission was denied. Check licence ownership and this device's permission with the Suweka administrator.</p>
    </form>
</div>
@endsection