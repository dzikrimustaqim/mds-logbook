@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-mds-blue-500 to-mds-purple-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8">
        <div>
            <h2 class="text-5xl font-black text-white mb-2">
                WELCOME BACK
            </h2>
            <p class="text-2xl font-bold text-white">
                Hello, <span class="text-mds-yellow-500">{{ auth()->user()->name }}</span>
            </p>
            <p class="text-lg font-bold text-white mt-2 opacity-90">
                {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }}
            </p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Students Card -->
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-mds-blue-500 border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <span class="bg-mds-blue-100 text-mds-blue-700 font-black text-xs px-3 py-1 border-2 border-mds-black">ACTIVE</span>
            </div>
            <h3 class="text-5xl font-black text-mds-blue-500 mb-2">
                {{ \App\Models\User::role('student')->count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Total Students</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">Registered in system</p>
        </div>

        <!-- Total Logbooks Card -->
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
                {{ \App\Models\InternLogbook::count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Total Logbooks</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">All submitted entries</p>
        </div>

        <!-- Approved Logbooks Card -->
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-mds-green-500 border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="bg-mds-green-100 text-mds-green-700 font-black text-xs px-3 py-1 border-2 border-mds-black">TODAY</span>
            </div>
            <h3 class="text-5xl font-black text-mds-green-500 mb-2">
                {{ \App\Models\InternLogbook::whereDate('created_at', '=', today())->count() }}
            </h3>
            <p class="text-lg font-bold text-mds-black uppercase tracking-wide">Today's Entries</p>
            <p class="text-sm font-bold text-mds-gray-600 mt-2">Submitted today</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-3xl font-black text-mds-black mb-4">QUICK ACTIONS</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Manage Students Card -->
            <a href="{{ route('admin.users.index') }}" 
               class="group bg-mds-green-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 hover:bg-mds-green-700 transition-all duration-200 active:translate-x-2 active:translate-y-2 active:shadow-none">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white border-4 border-mds-black p-4 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-mds-green-500 transition-colors duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white transition-colors duration-200">
                            MANAGE STUDENTS
                        </h4>
                        <p class="text-sm font-bold text-white transition-colors duration-200 mt-1 opacity-90">
                            View, edit, and manage student accounts
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 text-white font-black transition-colors duration-200">
                    <span>GO TO STUDENTS</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>

            <!-- Review Logbooks Card -->
            <a href="{{ route('admin.logbooks.index') }}" 
               class="group bg-mds-orange-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 hover:bg-mds-orange-700 transition-all duration-200 active:translate-x-2 active:translate-y-2 active:shadow-none">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white border-4 border-mds-black p-4 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-mds-orange-500 transition-colors duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white transition-colors duration-200">
                            REVIEW LOGBOOKS
                        </h4>
                        <p class="text-sm font-bold text-white transition-colors duration-200 mt-1 opacity-90">
                            Approve or reject student logbook entries
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 text-white font-black transition-colors duration-200">
                    <span>GO TO LOGBOOKS</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div>
        <h3 class="text-3xl font-black text-mds-black mb-4">RECENT ACTIVITY</h3>
        <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            @php
                $recentLogbooks = \App\Models\InternLogbook::with('user')
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp
            
            @if($recentLogbooks->count() > 0)
                <div class="space-y-3">
                    @foreach($recentLogbooks as $logbook)
                        <div class="flex items-center justify-between p-4 border-4 border-mds-black hover:bg-mds-gray-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="bg-mds-blue-500 border-2 border-mds-black w-12 h-12 flex items-center justify-center">
                                    <span class="text-white font-black text-lg">
                                        {{ substr($logbook->user->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-black text-mds-black">
                                        {{ $logbook->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-sm font-bold text-mds-gray-600">
                                        {{ Str::limit($logbook->activity ?? 'No activity', 50) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 border-2 border-mds-black font-black text-xs bg-mds-blue-500 text-white">
                                    SUBMITTED
                                </span>
                                <p class="text-xs font-bold text-mds-gray-600 mt-1">
                                    {{ $logbook->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-mds-gray-400 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xl font-black text-mds-gray-600">No recent activity</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection