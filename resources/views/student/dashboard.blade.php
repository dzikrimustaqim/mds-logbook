@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-mds-orange-500 to-mds-blue-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8">
        <div>
            <h2 class="text-5xl font-black text-white mb-2">
                WELCOME BACK
            </h2>
            <p class="text-2xl font-bold text-white">
                Hello, <span class="text-mds-yellow-500">{{ auth()->user()->name }}</span>
            </p>
            <p class="text-lg font-bold text-white mt-2 opacity-90">
                {{ now()->timezone('Asia/Jakarta')->format('l, d F Y') }} • {{ now()->timezone('Asia/Jakarta')->format('H:i') }} WIB
            </p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Logbooks -->
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-mds-orange-500 border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <span class="bg-mds-orange-100 text-mds-orange-700 font-black text-xs px-3 py-1 border-2 border-mds-black">TOTAL</span>
            </div>
            <h3 class="text-5xl font-black text-mds-orange-500 mb-2">
                {{ auth()->user()->logbooks->count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Total Logbooks</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">All your entries</p>
        </div>

        <!-- Approved Logbooks -->
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-mds-green-500 border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="bg-mds-green-100 text-mds-green-700 font-black text-xs px-3 py-1 border-2 border-mds-black">APPROVED</span>
            </div>
            <h3 class="text-5xl font-black text-mds-green-500 mb-2">
                {{ auth()->user()->logbooks->where('is_approved', true)->count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Approved</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">Verified by admin</p>
        </div>

        <!-- Pending Logbooks -->
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-yellow-500 border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="bg-yellow-100 text-yellow-700 font-black text-xs px-3 py-1 border-2 border-mds-black">PENDING</span>
            </div>
            <h3 class="text-5xl font-black text-yellow-500 mb-2">
                {{ auth()->user()->logbooks->where('is_approved', false)->count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Pending Review</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">Awaiting approval</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-3xl font-black text-mds-black mb-4">QUICK ACTIONS</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- View All Logbooks -->
            <a href="{{ route('student.logbooks.index') }}" 
               class="bg-mds-orange-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 hover:bg-mds-orange-700 transition-all duration-200 active:translate-x-2 active:translate-y-2 active:shadow-none">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white border-4 border-mds-black p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-mds-orange-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white">
                            VIEW LOGBOOKS
                        </h4>
                        <p class="text-sm font-bold text-white opacity-90 mt-1">
                            See all your logbook entries
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 text-white font-black">
                    <span>GO TO LOGBOOKS</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>

            <!-- Create New Logbook -->
            <a href="{{ route('student.logbooks.create') }}" 
               class="bg-mds-green-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 hover:bg-mds-green-700 transition-all duration-200 active:translate-x-2 active:translate-y-2 active:shadow-none">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white border-4 border-mds-black p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-mds-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white">
                            CREATE NEW
                        </h4>
                        <p class="text-sm font-bold text-white opacity-90 mt-1">
                            Add a new logbook entry
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 text-white font-black">
                    <span>CREATE ENTRY</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Logbooks -->
    <div>
        <h3 class="text-3xl font-black text-mds-black mb-4">RECENT ENTRIES</h3>
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            @php
                $recentLogbooks = auth()->user()->logbooks()
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp
            
            @if($recentLogbooks->count() > 0)
                <div class="space-y-3">
                    @foreach($recentLogbooks as $logbook)
                        <a href="{{ route('student.logbooks.show', $logbook->id) }}" class="flex items-center justify-between p-4 border-4 border-mds-black hover:bg-mds-orange-100 transition-colors cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="bg-mds-orange-500 border-2 border-mds-black w-12 h-12 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-black text-mds-black">
                                        {{ $logbook->title ?: 'Untitled' }}
                                    </p>
                                    <p class="text-sm font-bold text-mds-gray-600">
                                        {{ Str::limit($logbook->activity ?? 'No activity', 50) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 border-2 border-mds-black font-black text-xs
                                    {{ $logbook->is_approved ? 'bg-mds-green-500 text-white' : 'bg-mds-yellow-500 text-white' }}">
                                    {{ $logbook->is_approved ? 'APPROVED' : 'PENDING' }}
                                </span>
                                <p class="text-xs font-bold text-mds-gray-600 mt-1">
                                    {{ $logbook->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-mds-gray-400 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <p class="text-xl font-black text-mds-gray-600 mb-2">No logbooks yet</p>
                    <p class="text-sm font-bold text-mds-gray-500 mb-4">Start by creating your first entry</p>
                    <a href="{{ route('student.logbooks.create') }}"
                       class="inline-block bg-mds-green-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-mds-green-700 transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                        CREATE FIRST ENTRY
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection