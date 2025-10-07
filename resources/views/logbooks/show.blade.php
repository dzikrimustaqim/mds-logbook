@extends('layouts.student')

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
                <a href="{{ route('student.logbooks.index') }}"
                   class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>BACK TO LOGBOOKS</span>
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
                        {{ $logbook->date->format('d M Y') }}
                    </div>
                </div>
            </div>
            <div class="space-y-4">
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
                    </div>
                </div>
                <div>
                    <label class="block text-mds-black font-black mb-2">Created At</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-mds-black">
                        {{ $logbook->created_at->format('d M Y H:i:s') }}
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

    <!-- Edit & Delete Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('student.logbooks.edit', $logbook->id) }}"
               class="flex-1 bg-mds-blue-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] py-4 text-xl hover:bg-mds-blue-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span>EDIT LOGBOOK</span>
            </a>
            <form method="POST" action="{{ route('student.logbooks.destroy', $logbook->id) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] py-4 text-xl hover:bg-red-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none flex items-center justify-center gap-2"
                        onclick="return confirm('⚠️ Are you sure you want to DELETE this logbook?\n\nThis action cannot be undone!')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>DELETE LOGBOOK</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection