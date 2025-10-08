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
        <button type="button"
                onclick="openDeleteModal('{{ $logbook->id }}', '{{ $logbook->title ?: 'Untitled Logbook' }}')"
                class="bg-red-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] px-6 py-3 text-lg hover:bg-red-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none">
            DELETE LOGBOOK
        </button>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
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
                <p class="text-xl font-black text-red-500 mb-4" id="logbookTitleDisplay"></p>
                <p class="text-sm font-bold text-mds-gray-600">
                    The logbook will be permanently deleted.
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
</div>

<style>
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
    function openDeleteModal(logbookId, logbookTitle) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const titleDisplay = document.getElementById('logbookTitleDisplay');
        
        // Set form action
        form.action = `/admin/logbooks/${logbookId}`;
        
        // Set logbook title
        titleDisplay.textContent = logbookTitle;
        
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