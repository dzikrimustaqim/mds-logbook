@extends('layouts.admin')

@section('title', 'Edit Student - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    EDIT STUDENT
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">
                    {{ $user->name }}
                    <span class="text-mds-blue-500 font-mono">@{{ $user->username }}</span>
                </p>
            </div>
            <a href="{{ route('admin.users.show', $user->id) }}"
               class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>BACK TO DETAILS</span>
            </a>
        </div>
    </div>

    <!-- Edit Form Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-mds-black font-black mb-2 text-xl">
                    FULL NAME
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}"
                       required
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500 placeholder:text-mds-gray-400">
                @error('name')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-mds-black font-black mb-2 text-xl">
                    USERNAME
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username', $user->username) }}"
                       required
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500 placeholder:text-mds-gray-400">
                @error('username')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-mds-black font-black mb-2 text-xl">
                    EMAIL
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       required
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500 placeholder:text-mds-gray-400">
                @error('email')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field (Optional) -->
            <div>
                <label for="password" class="block text-mds-black font-black mb-2 text-xl">
                    NEW PASSWORD (OPTIONAL)
                </label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500"
                       placeholder="Leave blank to keep current password">
                @error('password')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div>
                <label for="password_confirmation" class="block text-mds-black font-black mb-2 text-xl">
                    CONFIRM NEW PASSWORD
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation"
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500"
                       placeholder="Leave blank to keep current password">
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit"
                        class="w-full bg-mds-green-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] py-4 text-xl hover:bg-mds-green-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none">
                    UPDATE STUDENT ACCOUNT
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <h3 class="text-2xl font-black text-red-500 mb-4">DELETE ACCOUNT</h3>
        <p class="text-mds-black mb-4">
            This action cannot be undone. All data related to this student will be permanently deleted.
        </p>
        <button type="button"
                onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}')"
                class="bg-red-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] px-6 py-3 text-lg hover:bg-red-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none">
            DELETE ACCOUNT
        </button>
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
                    All associated data will also be deleted permanently.
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
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameDisplay = document.getElementById('studentNameDisplay');
        
        // Set form action
        form.action = `/admin/users/${userId}`;
        
        // Set student name
        nameDisplay.textContent = userName;
        
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