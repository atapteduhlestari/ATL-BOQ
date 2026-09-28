<aside class="w-64 sticky top-4 ml-4 h-[calc(100vh-2rem)] flex flex-col
              bg-white/70 backdrop-blur-xl
              border border-white/60
              rounded-3xl
              shadow-[0_8px_32px_rgba(15,23,42,0.08)]
              overflow-hidden">
    <div class="flex flex-col h-full">

        {{-- ==================== LOGO PERUSAHAAN ==================== --}}
        <div class="px-5 pt-6 pb-1 flex justify-center">
            <img src="{{ asset('images/atl new logo.png') }}"
                 alt="Logo Perusahaan"
                 class="h-30 w-auto object-contain">
        </div>

        {{-- Divider tipis --}}
        <div class="mx-4 border-t border-slate-200/60"></div>

        {{-- ==================== MENU ==================== --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <x-sidebar-link href="/atap-standar" :active="request()->is('atap-standar')">
                <span>Atap Standar</span>
            </x-sidebar-link>

            <x-sidebar-link href="/atap-kombinasi" :active="request()->is('atap-kombinasi')">
                <span>Atap Kombinasi</span>
            </x-sidebar-link>

            <x-sidebar-link href="/dinding" :active="request()->is('dinding') || request()->is('dinding/*')">
                <span>Dinding</span>
            </x-sidebar-link>

            <x-sidebar-link href="/waterproofing" :active="request()->is('waterproofing') || request()->is('waterproofing/*')">
                <span>Waterproofing</span>
            </x-sidebar-link>

            <x-sidebar-link href="/jendela" :active="request()->is('jendela') || request()->is('jendela/*')">
                <span>Jendela</span>
            </x-sidebar-link>

            <x-sidebar-link href="/pintu" :active="request()->is('pintu') || request()->is('pintu/*')">
                <span>Pintu</span>
            </x-sidebar-link>

        </nav>
           {{-- ==================== LOGIN ADMIN ==================== --}}
        <div class="px-3 pb-5 pt-3 border-t border-slate-200/60">
            @auth
                {{-- Jika sudah login --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-2xl bg-emerald-50/70 border border-emerald-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-emerald-700 truncate">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </p>
                            <p class="text-[10px] text-emerald-600/80 truncate">
                                {{ auth()->user()->email ?? '' }}
                            </p>
                        </div>
                    </div>

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
            @else
                {{-- Jika belum login --}}
                <a href="{{ route('login') }}"
                   class="group flex items-center justify-center gap-2
                          w-full px-4 py-2.5 rounded-2xl
                          text-sm font-semibold text-white
                          bg-gradient-to-r from-slate-800 to-slate-700
                          hover:from-slate-700 hover:to-slate-600
                          shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                          hover:shadow-[0_6px_20px_rgba(15,23,42,0.35)]
                          transition-all duration-200
                          active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:scale-110"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Login Admin</span>
                </a>
            @endauth
        </div>
    </div>
</aside>