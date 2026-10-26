@extends('manage.layouts.workspace')
@section('title', 'Sample Data')
@section('heading', 'Sample Data')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <div class="w-16 h-16 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fa-solid fa-table"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-2">Sample Data</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Load demo products, customers, and sales to test the system before going live. This action will overwrite any existing data.</p>
        
        <button class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-8 py-3 rounded-xl font-medium transition shadow-sm">
            Load Sample Data
        </button>
    </div>
</div>
@endsection