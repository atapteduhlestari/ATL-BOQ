@props(['href', 'active' => false])

<a href="{{ $href }}"
   @class([
       'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 relative',
       'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-md shadow-indigo-500/20' => $active,
       'text-slate-700 hover:bg-white/60 hover:text-slate-900' => !$active,
   ])>
    {{-- Indikator kiri untuk menu aktif --}}
    @if($active)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-white/80 rounded-r-full"></span>
    @endif

    <span>{{ $slot }}</span>
</a>