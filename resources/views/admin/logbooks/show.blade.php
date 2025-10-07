@extends('layouts.admin')

@section('title', 'Logbook Details - ' . ($logbook->title ?: 'Untitled'))

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    LOGBOOK DETAILS
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">
                    {{ $logbook->title ?: 'Untitled Logbook' }}
                    <span class="text-mds-blue-500 font-mono">(#{{ $logbook->id }})</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.logbooks.index') }}"
                   class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>BACK TO LIST</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Logbook Info Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-mds-black font-black mb-2">Title</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $logbook->title ?: 'Untitled Logbook' }}
                    </div>
                </div>
                <div>
                    <label class="block text-mds-black font-black mb-2">Date</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $logbook->date?->format('d M Y') ?? 'No date' }}
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-mds-black font-black mb-2">Student</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $logbook->user?->name ?? 'No user' }}
                        <span class="text-mds-blue-500 font-mono">@{{ $logbook->user?->username ?? 'No username' }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-mds-black font-black mb-2">Status</label>
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-4 py-2 font-black text-lg {{
                            $logbook->is_approved 
                                ? 'bg-mds-green-500 text-white' 
                                : 'bg-mds-orange-500 text-white'
                        }} border-4 border-mds-black">
                            {{ $logbook->is_approved ? 'APPROVED' : 'PENDING' }}
                        </span>
                        <form action="{{ route('admin.logbooks.approve', ['intern_logbook' => $logbook->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-green-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                                {{ $logbook->is_approved ? 'UNAPPROVE' : 'APPROVE' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-mds-black font-black mb-2">Activity Description</label>
            <div class="bg-mds-gray-100 border-4 border-mds-black p-4 font-bold text-mds-black leading-relaxed whitespace-pre-wrap">
                {{ $logbook->activity }}
            </div>
        </div>
    </div>

    <!-- Additional Info Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <h3 class="text-2xl font-black text-mds-blue-500 mb-4">Additional Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-mds-black font-black mb-2">Created At</label>
                <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-mds-black">
                    {{ $logbook->created_at?->format('d M Y H:i:s') ?? 'No date' }}
                </div>
            </div>
            <div>
                <label class="block text-mds-black font-black mb-2">Updated At</label>
                <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-mds-black">
                    {{ $logbook->updated_at?->format('d M Y H:i:s') ?? 'No date' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <h3 class="text-2xl font-black text-red-500 mb-4">DELETE LOGBOOK</h3>
        <p class="text-mds-black mb-4">
            This action cannot be undone. The logbook will be permanently deleted.
        </p>
        <form method="POST" action="{{ route('admin.logbooks.destroy', ['intern_logbook' => $logbook->id]) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] px-6 py-3 text-lg hover:bg-red-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none"
                    onclick="return confirm('⚠️ Are you sure you want to DELETE this logbook?\n\nThis action cannot be undone!')">
                DELETE LOGBOOK
            </button>
        </form>
    </div>
</div>
@endsection