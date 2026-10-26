@extends('manage.layouts.workspace')
@section('title', 'Edit staff member')
@section('heading', 'Edit staff member')

@section('topbar_actions')
    <!-- Desktop Save Button -->
    <button type="submit" form="edit-staff-form" class="hidden md:flex bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-2.5 rounded-full font-medium items-center gap-2 transition shadow-sm">
        <i class="fa-solid fa-check"></i> Save
    </button>
    <!-- Mobile Save Button (Icon only) -->
    <button type="submit" form="edit-staff-form" class="md:hidden bg-[#713FCE] hover:bg-[#5a2ea6] text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-sm">
        <i class="fa-solid fa-check"></i>
    </button>
@endsection

@section('content')
<div class="swi-wrap max-w-2xl mx-auto mt-4 md:mt-10">
    @if(session('status'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">{{ session('status') }}</div>
    @endif

    <form id="edit-staff-form" method="POST" action="{{ route('manage.staff.update', $staff->id) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10">
        @csrf
        @method('PUT')

        <h3 class="text-xl font-semibold text-gray-900 mb-8">Account details</h3>
        
        <div class="space-y-6 mb-10">
            <div class="relative">
                <label class="block text-sm text-gray-500 mb-1">First name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required 
                       class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-lg text-gray-900 outline-none transition-colors">
                @error('first_name') <span class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</span> @enderror
            </div>
            
            <div class="relative">
                <label class="block text-sm text-gray-500 mb-1">Last name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required 
                       class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-lg text-gray-900 outline-none transition-colors">
                @error('last_name') <span class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</span> @enderror
            </div>

            <div class="relative">
                <label class="block text-sm text-gray-500 mb-1">Role</label>
                <select name="role" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-lg text-gray-900 outline-none cursor-pointer transition-colors appearance-none">
                    <option value="Admin" {{ $staff->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Cashier" {{ $staff->role === 'Cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="Manager" {{ $staff->role === 'Manager' ? 'selected' : '' }}>Manager</option>
                </select>
                <i class="fa-solid fa-caret-down absolute right-2 top-1/2 text-gray-500 pointer-events-none mt-2"></i>
                @error('role') <span class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 class="text-xl font-semibold text-gray-900 mb-2 mt-4">Reset password</h3>
        <p class="text-sm text-gray-500 mb-8">Leave blank to keep the current password.</p>

        <div class="space-y-8">
            <div class="relative">
                <label class="block text-sm text-gray-500 mb-1">New password</label>
                <input type="password" name="password" 
                       class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-lg text-gray-900 outline-none transition-colors">
                <span class="absolute right-0 bottom-2 text-xs text-gray-400">0/16</span>
                @error('password') <span class="text-red-500 text-xs mt-1 absolute -bottom-5">{{ $message }}</span> @enderror
            </div>
            
            <div class="relative">
                <label class="block text-sm text-gray-500 mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" 
                       class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-lg text-gray-900 outline-none transition-colors">
                <span class="absolute right-0 bottom-2 text-xs text-gray-400">0/16</span>
            </div>
        </div>
    </form>
</div>
@endsection