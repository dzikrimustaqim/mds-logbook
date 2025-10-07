@extends('layouts.admin')

@section('title', 'All Logbooks')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    ALL LOGBOOKS
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">
                    Total: {{ $logbooks->total() }} logbooks
                </p>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-4">
        <form method="GET" action="{{ route('admin.logbooks.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-mds-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       placeholder="Search by title, activity, or student name..." 
                       value="{{ request('search') }}"
                       class="w-full pl-14 pr-4 py-3 border-4 border-mds-black font-bold text-mds-black placeholder:text-mds-gray-400 focus:outline-none focus:ring-4 focus:ring-mds-yellow-500">
            </div>
            <select name="status" class="border-4 border-mds-black font-bold text-mds-black p-3 focus:outline-none focus:ring-4 focus:ring-mds-yellow-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            </select>
            <button type="submit" 
                    class="bg-mds-blue-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-white hover:text-mds-blue-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
                SEARCH
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.logbooks.index') }}" 
               class="bg-red-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-white hover:text-red-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap text-center">
                CLEAR
            </a>
            @endif
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-mds-blue-500">
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">
                            ID
                        </th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-left uppercase tracking-wider">
                            Student
                        </th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-left uppercase tracking-wider">
                            Title
                        </th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">
                            Date
                        </th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">
                            Status
                        </th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logbooks as $index => $logbook)
                    <tr class="group hover:bg-mds-yellow-100 transition-colors duration-150 {{ $index % 2 === 0 ? 'bg-white' : 'bg-mds-gray-50' }}">
                        <td class="border-4 border-mds-black p-4 text-center">
                            <span class="inline-block bg-mds-blue-500 text-white font-black px-3 py-1 border-2 border-mds-black">
                                {{ $logbook->id }}
                            </span>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <div class="font-bold text-mds-black">
                                {{ $logbook->user->name }}
                            </div>
                            <div class="text-sm text-mds-gray-600 font-mono">
                                @{{ $logbook->user->username }}
                            </div>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <div class="font-bold text-mds-black max-w-xs truncate">
                                {{ $logbook->title ?: 'Untitled Logbook' }}
                            </div>
                            <div class="text-sm text-mds-gray-600 mt-1 line-clamp-2">
                                {{ Str::limit($logbook->activity, 100) }}
                            </div>
                        </td>
                        <td class="border-4 border-mds-black p-4 text-center">
                            <span class="font-bold text-mds-black">
                                {{ $logbook->date->format('d M Y') }}
                            </span>
                        </td>
                        <td class="border-4 border-mds-black p-4 text-center">
                            <span class="inline-block px-4 py-2 font-black text-sm {{
                                $logbook->is_approved 
                                    ? 'bg-mds-green-500 text-white' 
                                    : 'bg-mds-orange-500 text-white'
                            }} border-2 border-mds-black">
                                {{ $logbook->is_approved ? 'APPROVED' : 'PENDING' }}
                            </span>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                <!-- View Button -->
                                <a href="{{ route('admin.logbooks.show', $logbook->id) }}"
                                   class="inline-flex items-center gap-1 bg-mds-purple-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-mds-purple-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                   title="View Logbook">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs">VIEW</span>
                                </a>

                                <!-- Approve/Unapprove Button -->
                                @if($logbook->is_approved)
                                <form action="{{ route('admin.logbooks.approve', $logbook->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-red-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                            title="Unapprove Logbook">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        <span class="text-xs">UNAPPROVE</span>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.logbooks.approve', $logbook->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 bg-mds-green-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-mds-green-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                            title="Approve Logbook">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                        </svg>
                                        <span class="text-xs">APPROVE</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border-4 border-mds-black p-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-mds-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                <div class="text-2xl font-black text-mds-gray-700">
                                    NO LOGBOOKS FOUND
                                </div>
                                <p class="text-lg font-bold text-mds-gray-500">
                                    @if(request('search') || request('status'))
                                        Try searching with different keywords or filters
                                    @else
                                        No logbooks have been created yet.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Section -->
    @if($logbooks->hasPages())
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="font-bold text-mds-black">
                Showing {{ $logbooks->firstItem() }} to {{ $logbooks->lastItem() }} of {{ $logbooks->total() }} logbooks
            </div>
            <div class="flex gap-2">
                {{ $logbooks->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* Custom Pagination Styling for Neubrutalism */
    .pagination {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination .page-link {
        background: white;
        color: #000;
        font-weight: 900;
        border: 4px solid #000;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
        padding: 0.5rem 1rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination .page-link:hover {
        background: #fbbf24;
        transform: translate(1px, 1px);
        box-shadow: 3px 3px 0px 0px rgba(0,0,0,1);
    }
    
    .pagination .page-item.active .page-link {
        background: #3b82f6;
        color: white;
    }
    
    .pagination .page-item.disabled .page-link {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>
@endsection