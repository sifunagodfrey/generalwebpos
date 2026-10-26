@extends('manage.layouts.workspace')
@section('title', 'Leave')
@section('heading', 'Leave')
@section('mobile_heading', 'Leave')

@section('topbar_actions')
    <button type="button" @click="$dispatch('open-leave-modal')" class="hidden md:flex bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-5 py-2.5 rounded-full font-medium items-center gap-2 transition shadow-sm">
        <i class="fa-regular fa-calendar-plus"></i> Log leave
    </button>
    <button type="button" @click="$dispatch('open-leave-modal')" class="md:hidden bg-[#713FCE] hover:bg-[#5a2ea6] text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-sm">
        <i class="fa-regular fa-calendar-plus"></i>
    </button>
@endsection

@section('content')
<div class="swi-wrap max-w-5xl mx-auto space-y-4 pb-20 md:pb-6" x-data="{ leaveModal: false }" @open-leave-modal.window="leaveModal = true">

    @if(session('status'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-4">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse($leaves as $leave)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center font-bold text-lg shrink-0">
                        {{ substr($leave->staff->first_name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-lg">{{ $leave->staff->first_name ?? 'Unknown' }} {{ $leave->staff->last_name ?? '' }}</p>
                        <p class="text-sm text-gray-500 mt-0.5"><i class="fa-solid fa-circle-info text-xs mr-1"></i> {{ $leave->reason ?? 'No reason provided' }}</p>
                    </div>
                </div>
                <div class="md:text-right border-t md:border-t-0 border-gray-100 pt-3 md:pt-0">
                    <p class="text-sm font-semibold text-[#713FCE]">
                        <i class="fa-regular fa-calendar text-xs mr-1"></i>
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }} &rarr; {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Logged by {{ $leave->loggedByStaff->first_name ?? 'System' }}</p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
                No leave logged yet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $leaves->links() }}</div>

    <!-- Log Leave Modal -->
    <div x-cloak x-show="leaveModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50" @click.self="leaveModal = false" x-transition.opacity>
        <div class="bg-[#f4effb] rounded-3xl w-full max-w-md p-8 relative shadow-xl">
            <form method="POST" action="{{ route('manage.staff.store-leave') }}" class="space-y-6" @submit="leaveModal = false">
                @csrf
                <h3 class="text-2xl font-normal text-gray-900 mb-2">Log leave</h3>
                
                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Staff member</label>
                    <select name="staff_id" required class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none cursor-pointer">
                        <option value="" disabled selected>Select staff</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="relative flex-1">
                        <label class="block text-sm text-gray-600 mb-1">Start date</label>
                        <input type="date" name="start_date" required value="{{ now()->format('Y-m-d') }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                    </div>
                    <div class="relative flex-1">
                        <label class="block text-sm text-gray-600 mb-1">End date</label>
                        <input type="date" name="end_date" required value="{{ now()->format('Y-m-d') }}" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                    </div>
                </div>

                <div class="relative">
                    <label class="block text-sm text-gray-600 mb-1">Reason (optional)</label>
                    <input type="text" name="reason" class="w-full border-0 border-b border-gray-400 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-lg text-gray-900 outline-none">
                </div>

                <div class="flex justify-end items-center gap-4 mt-8">
                    <button type="button" @click="leaveModal = false" class="text-[#713FCE] font-semibold hover:bg-black/5 px-4 py-2 rounded-full transition">Cancel</button>
                    <button type="submit" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow-sm">Log leave</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection