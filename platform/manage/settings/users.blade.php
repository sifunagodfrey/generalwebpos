@extends('manage.layouts.workspace')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
<div class="max-w-3xl mx-auto pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Users</h2>
        <p class="text-sm text-gray-500 mb-6">Add, edit or deactivate staff accounts (Section 5.3).</p>

        <a href="{{ route('manage.workspace.staff', ['page' => 'index']) }}" class="w-full border border-gray-400 text-[#713FCE] font-medium py-3 rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-users-gear"></i> Manage staff
        </a>
    </div>
</div>
@endsection