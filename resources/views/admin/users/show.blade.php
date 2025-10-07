@extends('layouts.admin')

@section('title', 'Student Details - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    STUDENT DETAILS
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">
                    {{ $user->name }}
                    <span class="text-mds-blue-500 font-mono">@{{ $user->username }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="bg-white text-mds-blue-500 font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-blue-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>EDIT</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>BACK</span>
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <h3 class="text-2xl font-black text-mds-blue-500 mb-4">User Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-mds-black font-bold mb-2">Name</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $user->name }}
                    </div>
                </div>
                <div>
                    <label class="block text-mds-black font-bold mb-2">Username</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg font-mono">
                        @{{ $user->username }}
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-mds-black font-bold mb-2">Email</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $user->email }}
                    </div>
                </div>
                <div>
                    <label class="block text-mds-black font-bold mb-2">Created At</label>
                    <div class="bg-mds-gray-100 border-4 border-mds-black p-3 font-bold text-lg">
                        {{ $user->created_at->format('d M Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logbooks Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-black text-mds-orange-500">
                LOGBOOKS ({{ $user->logbooks->count() }})
            </h3>
            <a href="{{ route('student.logbooks.create') }}"
               class="bg-mds-green-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-green-700 transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>ADD LOGBOOK</span>
            </a>
        </div>

        @if($user->logbooks->count() > 0)
        <div class="space-y-4">
            @foreach($user->logbooks as $logbook)
            <div class="border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] p-4 bg-white">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="text-xl font-black text-mds-black">
                            {{ $logbook->title ?: 'Untitled Logbook' }}
                        </h4>
                        <p class="text-mds-gray-600 font-bold">
                            {{ $logbook->date->format('d M Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-3 py-1 font-black text-sm {{
                            $logbook->is_approved 
                                ? 'bg-mds-green-500 text-white' 
                                : 'bg-mds-orange-500 text-white'
                        }} border-2 border-mds-black">
                            {{ $logbook->is_approved ? 'APPROVED' : 'PENDING' }}
                        </span>
                        <a href="{{ route('admin.logbooks.show', $logbook->id) }}"
                           class="bg-white text-mds-blue-500 font-black border-2 border-mds-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] px-3 py-1 hover:bg-mds-blue-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                            VIEW
                        </a>
                    </div>
                </div>
                <p class="text-mds-black mt-2 line-clamp-3">
                    {{ Str::limit($logbook->activity, 200) }}
                </p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-mds-gray-400 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            <div class="text-2xl font-black text-mds-gray-700 mt-4">
                NO LOGBOOKS FOUND
            </div>
            <p class="text-lg font-bold text-mds-gray-500 mt-2">
                This student hasn't created any logbooks yet.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection