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

    </div>
</aside>