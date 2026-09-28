<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') — ATL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200">

    <div class="flex min-h-screen p-2 sm:p-4 gap-2 sm:gap-4">

        {{-- OVERLAY (mobile) --}}
        <div id="sidebar-overlay"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40
                    hidden lg:hidden transition-opacity duration-300"
             onclick="toggleSidebar()">
        </div>

        {{-- SIDEBAR --}}
        <aside id="sidebar"
               class="fixed lg:sticky top-0 lg:top-4 left-0 z-50 lg:z-auto
                      w-64 h-screen lg:h-[calc(100vh-2rem)]
                      flex flex-col
                      bg-white/95 lg:bg-white/70 backdrop-blur-xl
                      border-r lg:border border-white/60
                      lg:rounded-3xl
                      shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                      overflow-hidden shrink-0
                      -translate-x-full lg:translate-x-0
                      transition-transform duration-300 ease-in-out">

            <div class="flex flex-col h-full">

                {{-- Logo --}}
                <div class="px-5 pt-6 pb-1 flex justify-center">
                    <img src="{{ asset('images/atl new logo.png') }}"
                         alt="Logo Perusahaan"
                         class="h-24 lg:h-30 w-auto object-contain">
                </div>

                <div class="mx-4 border-t border-slate-200/60"></div>

                {{-- MENU --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

                    <x-admin.sidebar-link href="{{ route('admin.dashboard') }}"
                                          :active="request()->routeIs('admin.dashboard')">
                        <span>Dashboard</span>
                    </x-admin.sidebar-link>

                    <div class="px-3 pt-4 pb-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Master Data
                        </p>
                    </div>

                   <x-admin.sidebar-link href="{{ route('admin.produk.index') }}"
                      :active="request()->routeIs('admin.produk*')">
    <span>Produk</span>
</x-admin.sidebar-link>

<x-admin.sidebar-link href="{{ route('admin.boq.index') }}"
                                          :active="request()->routeIs('admin.boq*')">
                        <span>BoQ</span>
                    </x-admin.sidebar-link>

                </nav>

                {{-- User Info + Logout --}}
                <div class="px-3 pb-5 pt-3 border-t border-slate-200/60 mt-auto">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2
                                       px-4 py-2.5 rounded-2xl
                                       text-sm font-medium text-rose-600
                                       bg-rose-50/70 hover:bg-rose-100
                                       border border-rose-200/60
                                       transition-all duration-200
                                       hover:shadow-[0_4px_12px_rgba(244,63,94,0.15)]
                                       active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 min-w-0 flex flex-col gap-2 sm:gap-4">

            {{-- Topbar (mobile only) --}}
            <header class="lg:hidden sticky top-2 z-30
                           bg-white/70 backdrop-blur-xl
                           border border-white/60
                           rounded-3xl
                           shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                           px-4 py-3
                           flex items-center gap-3">

                <button onclick="toggleSidebar()"
                        class="w-10 h-10 rounded-2xl
                               bg-slate-100 hover:bg-slate-200
                               flex items-center justify-center
                               text-slate-700
                               transition-all active:scale-95 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-base font-bold text-slate-800 truncate">
                    @yield('page-title', 'Dashboard')
                </h1>
            </header>

            {{-- Content --}}
            <div class="flex-1">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="text-center text-xs text-slate-400 py-2">
                &copy; {{ date('Y') }} ATL — All rights reserved.
            </footer>

        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>