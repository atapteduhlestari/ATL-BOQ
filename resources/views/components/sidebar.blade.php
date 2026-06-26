<aside class="w-64 bg-slate-900 text-white min-h-screen">
    <div class="flex flex-col h-full">
        <!-- Logo - dengan margin top yang lebih kecil -->
        <div class="px-6 pt-6 pb-5 border-b border-slate-700/50">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                    <span class="text-white text-xs font-bold tracking-wider">AT</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white tracking-tight">ATL</p>
                    <p class="text-[10px] text-slate-400 tracking-wide">BOQ Material</p>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 px-4 py-6 space-y-0.5">
            <x-sidebar-link href="/atap-standar" :active="request()->is('atap-standar')">
                <span class="text-sm font-medium text-slate-200 hover:text-white">Atap Standar</span>
            </x-sidebar-link>

            <x-sidebar-link href="/atap-kombinasi" :active="request()->is('atap-kombinasi')">
                <span class="text-sm font-medium text-slate-200 hover:text-white">Atap Kombinasi</span>
            </x-sidebar-link>

             <x-sidebar-link href="/dinding" :active="request()->is('dinding') || request()->is('dinding/*')">
        <span class="text-sm font-medium text-slate-200 hover:text-white">Dinding</span>
    </x-sidebar-link>

            <x-sidebar-link href="/jendela" :active="request()->is('jendela') || request()->is('jendela/*')">
                <span class="text-sm font-medium text-slate-200 hover:text-white">Jendela</span>
            </x-sidebar-link>
        </nav>

        <!-- Footer -->
        <div class="px-6 py-5 border-t border-slate-700/50">
            <p class="text-[10px] text-slate-500">© 2026 Atap Teduh Lestari</p>
        </div>
    </div>
</aside>