@extends('layouts.admin')

@section('title', 'Manage Students')

@section('content')
<div class="space-y-6">
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    MANAGE STUDENTS
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">Total: {{ $students->total() }} Students</p>
            </div>
            
            <a href="{{ route('admin.users.create') }}"
               class="bg-mds-green-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] px-6 py-3 text-lg hover:bg-white hover:text-mds-green-500 transition-all duration-200 active:translate-x-[6px] active:translate-y-[6px] active:shadow-none flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>ADD STUDENT</span>
            </a>
        </div>
    </div>

    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-mds-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                        name="search" 
                        placeholder="Search by name, username, or email..." 
                        value="{{ request('search') }}"
                        class="w-full pl-14 pr-4 py-3 border-4 border-mds-black font-bold text-mds-black placeholder:text-mds-gray-400 focus:outline-none focus:ring-4 focus:ring-mds-yellow-500">
            </div>
            
            <button type="submit" 
                    class="bg-mds-blue-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-white hover:text-mds-blue-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap">
                SEARCH
            </button>
            
            @if(request('search'))
            <a href="{{ route('admin.users.index') }}" 
               class="bg-red-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-white hover:text-red-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none whitespace-nowrap text-center">
                CLEAR
            </a>
            @endif
        </form>
    </div>

    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-mds-blue-500">
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">ID</th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">Name</th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">Username</th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">Email</th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">Logbooks</th>
                        <th class="border-4 border-mds-black p-4 text-white font-black text-center uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    <tr class="group hover:bg-mds-yellow-100 transition-colors duration-150 {{ $index % 2 === 0 ? 'bg-white' : 'bg-mds-gray-50' }}">
                        <td class="border-4 border-mds-black p-4 text-center">
                            <span class="inline-block bg-mds-blue-500 text-white font-black px-3 py-1 border-2 border-mds-black">
                                {{ $student->id }}
                            </span>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <div class="font-bold text-lg text-mds-black">{{ $student->name }}</div>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <span class="font-mono font-bold text-mds-blue-500">@{{ $student->username }}</span>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <span class="font-bold text-mds-gray-700">{{ $student->email }}</span>
                        </td>
                        <td class="border-4 border-mds-black p-4 text-center">
                            <a href="{{ route('admin.users.show', $student->id) }}"
                               class="inline-flex items-center gap-2 bg-mds-orange-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-white hover:text-mds-orange-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                <span>{{ $student->logbooks->count() }}</span>
                            </a>
                        </td>
                        <td class="border-4 border-mds-black p-4">
                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                <a href="{{ route('admin.users.show', $student->id) }}"
                                   class="inline-flex items-center gap-1 bg-mds-purple-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-mds-purple-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                   title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs">VIEW</span>
                                </a>

                                <a href="{{ route('admin.users.edit', $student->id) }}"
                                   class="inline-flex items-center gap-1 bg-mds-blue-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-mds-blue-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                   title="Edit Student">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    <span class="text-xs">EDIT</span>
                                </a>

                                <button type="button"
                                        onclick="openDeleteModal('{{ $student->id }}', '{{ $student->name }}')"
                                        class="inline-flex items-center gap-1 bg-red-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-3 py-2 hover:bg-white hover:text-red-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none"
                                        title="Delete Student">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    <span class="text-xs">DELETE</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border-4 border-mds-black p-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-mds-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                                </svg>
                                <div class="text-2xl font-black text-mds-gray-700">NO STUDENTS FOUND</div>
                                <p class="text-lg font-bold text-mds-gray-500">
                                    @if(request('search'))
                                        Try searching with different keywords
                                    @else
                                        Start by adding your first student!
                                    @endif
                                </p>
                                @if(!request('search'))
                                <a href="{{ route('admin.users.create') }}"
                                   class="mt-4 bg-mds-green-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-white hover:text-mds-green-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                                    ADD FIRST STUDENT
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($students->hasPages())
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="font-bold text-mds-black">
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
            </div>
            <div class="flex gap-2">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60" onclick="closeDeleteModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white border-6 border-mds-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-md w-full animate-bounce-in">
        <!-- Header -->
        <div class="bg-red-500 border-b-6 border-mds-black p-6">
            <div class="flex items-center gap-4">
                <div class="bg-white border-4 border-mds-black p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white">WARNING</h3>
                    <p class="text-sm font-bold text-white opacity-90">This action cannot be undone</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-lg font-bold text-mds-black mb-2">
                Are you sure you want to delete:
            </p>
            <p class="text-xl font-black text-red-500 mb-4" id="studentNameDisplay"></p>
            <p class="text-sm font-bold text-mds-gray-600">
                All associated logbooks will also be deleted permanently.
            </p>
        </div>

        <!-- Footer -->
        <div class="border-t-6 border-mds-black p-6 flex gap-3">
            <button type="button"
                    onclick="closeDeleteModal()"
                    class="flex-1 bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-mds-gray-200 transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                CANCEL
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-500 text-white font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-6 py-3 hover:bg-red-700 transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                    DELETE
                </button>
            </form>
        </div>
    </div>
</div>

<style>
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

    @keyframes bounce-in {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.3s ease-out;
    }
</style>

<script>
    function openDeleteModal(studentId, studentName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameDisplay = document.getElementById('studentNameDisplay');
        
        // Set form action
        form.action = `/admin/users/${studentId}`;
        
        // Set student name
        nameDisplay.textContent = studentName;
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection