@extends('manage.layouts.workspace')
@section('title', 'All staff')
@section('heading', 'All staff')
@section('subtitle', 'Accounts and roles')

@section('content')
<div class="swi-wrap max-w-5xl mx-auto" x-data="{ showAddModal: {{ $errors->any() ? 'true' : 'false' }} }" @open-add-staff.window="showAddModal = true">    
    @if(session('status'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">{{ session('status') }}</div>
    @endif

    <!-- Search & Add Button -->
    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center mb-6">
        <form method="GET" action="{{ route('manage.staff.index') }}" class="relative w-full md:w-96">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search staff by name" 
                   class="w-full pl-11 pr-4 py-3 rounded-lg border-0 bg-white shadow-sm focus:ring-2 focus:ring-[#713FCE] outline-none transition">
        </form>
        
        <button @click="showAddModal = true" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-3 rounded-full font-medium flex items-center gap-2 transition shadow-sm w-full md:w-auto justify-center">
            <i class="fa-solid fa-plus text-sm"></i> Add Staff
        </button>
    </div>

    <!-- Staff List -->
    <div class="space-y-3">
        @forelse($staff as $member)
            <a href="{{ route('manage.staff.show', $member->id) }}"
               class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 transition">
                <div class="w-12 h-12 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center font-bold text-lg shrink-0">
                    {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">{{ $member->first_name }} {{ $member->last_name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $member->role }}</p>
                </div>

                @if(!$member->is_active)
                    <i class="fa-solid fa-ban text-red-500 mr-2"></i>
                @endif
                <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
            </a>
        @empty
            <div class="p-8 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
                No staff members found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $staff->links() }}</div>

    <!-- Add Staff Modal -->
    <div x-cloak x-show="showAddModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50" @click.self="showAddModal = false" x-transition.opacity>
        <div class="bg-[#f4effb] rounded-3xl w-full max-w-md p-8 relative shadow-xl">
            
            <form method="POST" action="{{ route('manage.staff.store') }}" class="space-y-6">
                @csrf
                <h3 class="text-2xl font-normal text-gray-900 mb-2">Add staff member</h3>
                
                <!-- Floating/Underline inputs -->
                <div class="relative">
                    <label class="block text-sm text-[#713FCE] mb-1">First name</label>
                    <input type="text" name="first_name" required class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                </div>
                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Last name</label>
                    <input type="text" name="last_name" required class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                </div>
                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Role</label>
                    <select name="role" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none cursor-pointer">
                        <option value="Cashier">Cashier</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Password (8–16 characters)</label>
                    <input type="password" name="password" required minlength="8" maxlength="16" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                    <span class="absolute right-0 bottom-2 text-xs text-gray-400">0/16</span>
                </div>
                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Confirm password</label>
                    <input type="password" name="password_confirmation" required minlength="8" maxlength="16" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                </div>

                @if($errors->any())
                    <p class="text-red-500 text-sm">{{ $errors->first() }}</p>
                @endif

                <div class="flex justify-end items-center gap-4 mt-8">
                    <button type="button" @click="showAddModal = false" class="text-[#713FCE] font-semibold hover:bg-black/5 px-4 py-2 rounded-full transition">Cancel</button>
                    <button type="submit" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection