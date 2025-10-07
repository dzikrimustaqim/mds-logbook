@extends('layouts.student')

@section('title', 'Create Logbook')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-mds-yellow-500 border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-mds-black">
                    CREATE LOGBOOK
                </h2>
                <p class="text-lg font-bold text-mds-black mt-2">
                    Add a new logbook entry
                </p>
            </div>
            <a href="{{ route('student.logbooks.index') }}"
               class="bg-white text-mds-black font-black border-4 border-mds-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] px-4 py-2 hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>BACK TO LOGBOOKS</span>
            </a>
        </div>
    </div>

    <!-- Create Form Section -->
    <div class="bg-white border-6 border-mds-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
        <form method="POST" action="{{ route('student.logbooks.store') }}" class="space-y-6">
            @csrf

            <!-- Date Field -->
            <div>
                <label for="date" class="block text-mds-black font-black mb-2 text-xl">
                    DATE
                </label>
                <input type="date" 
                       id="date" 
                       name="date" 
                       value="{{ old('date') }}"
                       required
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500">
                @error('date')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title Field -->
            <div>
                <label for="title" class="block text-mds-black font-black mb-2 text-xl">
                    TITLE (OPTIONAL)
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500 placeholder:text-mds-gray-400"
                       placeholder="Enter a title for your logbook entry...">
                @error('title')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Activity Field -->
            <div>
                <label for="activity" class="block text-mds-black font-black mb-2 text-xl">
                    ACTIVITY DESCRIPTION
                </label>
                <textarea id="activity" 
                          name="activity" 
                          rows="6"
                          required
                          class="w-full p-4 border-4 border-mds-black font-black text-lg focus:outline-none focus:ring-4 focus:ring-mds-yellow-500 placeholder:text-mds-gray-400"
                          placeholder="Describe your daily activities...">{{ old('activity') }}</textarea>
                @error('activity')
                    <p class="text-red-500 font-black mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit"
                        class="w-full bg-mds-green-500 text-white font-black border-6 border-mds-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] py-4 text-xl hover:bg-mds-green-700 transition active:translate-x-[6px] active:translate-y-[6px] active:shadow-none">
                    CREATE LOGBOOK ENTRY
                </button>
            </div>
        </form>
    </div>
</div>
@endsection