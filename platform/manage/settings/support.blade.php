@extends('manage.layouts.workspace')
@section('title', 'Support')
@section('heading', 'Support')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Need help?</h2>
        <p class="text-sm text-gray-500 mb-8">Reach the Suweka support team directly.</p>

        <div class="space-y-6 mb-8">
            <div class="flex items-start gap-4">
                <i class="fa-solid fa-phone text-[#713FCE] mt-1"></i>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-gray-900 font-medium">+254 700 577 467</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <i class="fa-solid fa-envelope text-[#713FCE] mt-1"></i>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900 font-medium">support@suweka.com</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <i class="fa-solid fa-globe text-[#713FCE] mt-1"></i>
                <div>
                    <p class="text-sm text-gray-500">Website</p>
                    <p class="text-gray-900 font-medium">suweka.com</p>
                </div>
            </div>
        </div>

        <form action="{{ route('manage.settings.support.logs') }}" method="POST">
            @csrf
            <button type="submit" class="border border-gray-400 text-[#713FCE] px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 hover:bg-gray-50 transition">
                <i class="fa-solid fa-bug"></i> Share diagnostic logs
            </button>
        </form>
    </div>
</div>
@endsection