<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const textElements = document.querySelectorAll('.menu-text');
            const logoText = document.getElementById('logoText');
            const toggleIcon = document.getElementById('toggleIcon');
            const isCollapsed = sidebar.classList.contains('w-20');

            if (isCollapsed) {
                // EXPAND
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                toggleIcon.innerHTML = '«';
                
                // Tampilkan text dengan delay
                setTimeout(() => {
                    textElements.forEach(el => {
                        el.classList.remove('hidden', 'opacity-0');
                        el.classList.add('opacity-100');
                    });
                    logoText.classList.remove('hidden', 'opacity-0');
                    logoText.classList.add('opacity-100');
                }, 100);
            } else {
                // COLLAPSE
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                toggleIcon.innerHTML = '»';
                
                // Fade out text dulu
                textElements.forEach(el => {
                    el.classList.remove('opacity-100');
                    el.classList.add('opacity-0');
                });
                logoText.classList.remove('opacity-100');
                logoText.classList.add('opacity-0');
                
                // Baru hide setelah fade selesai
                setTimeout(() => {
                    textElements.forEach(el => el.classList.add('hidden'));
                    logoText.classList.add('hidden');
                }, 200);
            }
        }
    </script>
</head>
<body class="bg-mds-blue-500">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="w-64 bg-mds-blue-700 border-6 border-mds-black shadow-neubrutal p-4 transition-all duration-300 flex flex-col flex-shrink-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 id="logoText" class="text-xl font-extrabold text-white truncate transition-opacity duration-200">
                    Admin Panel
                </h2>
                <button onclick="toggleSidebar()"
                        class="bg-white text-mds-black font-bold border-4 border-mds-black shadow-neubrutal p-1 w-10 h-10 flex items-center justify-center hover:bg-mds-yellow-500 transition active:translate-x-1 active:translate-y-1 active:shadow-none flex-shrink-0">
                    <span id="toggleIcon" class="text-xl">«</span>
                </button>
            </div>

            <!-- Menu Items -->
            <ul class="space-y-3 flex-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center justify-center gap-3 py-3 px-4 bg-white text-mds-blue-500 font-bold border-4 border-mds-black shadow-neubrutal hover:bg-mds-blue-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                        </svg>
                        <span class="menu-text truncate transition-opacity duration-200">Dashboard</span>
                    </a>
                </li>

                <!-- Logbooks -->
                <li>
                    <a href="{{ route('admin.logbooks.index') }}"
                       class="flex items-center justify-center gap-3 py-3 px-4 bg-white text-mds-orange-500 font-bold border-4 border-mds-black shadow-neubrutal hover:bg-mds-orange-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        <span class="menu-text truncate transition-opacity duration-200">Logbooks</span>
                    </a>
                </li>

                <!-- Manage Students -->
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center justify-center gap-3 py-3 px-4 bg-white text-mds-green-500 font-bold border-4 border-mds-black shadow-neubrutal hover:bg-mds-green-500 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span class="menu-text truncate transition-opacity duration-200">Manage Students</span>
                    </a>
                </li>
            </ul>

            <!-- Logout -->
            <div class="mt-auto pt-4 border-t-4 border-mds-black">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white text-red-600 font-bold border-4 border-mds-black shadow-neubrutal hover:bg-red-600 hover:text-white transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 12l-3-3m0 0l-3 3m3-3h-7.5" />
                        </svg>
                        <span class="menu-text truncate transition-opacity duration-200">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <header class="mb-6">
                <h1 class="text-3xl font-extrabold text-white drop-shadow-[4px_4px_0px_rgba(0,0,0,1)]">{{ $title ?? 'Dashboard' }}</h1>
            </header>

            <div class="bg-white border-6 border-mds-black shadow-neubrutal p-6 text-gray-800">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>