@props(['href' => '#', 'active' => false])

<a href="{{ $href }}"
   class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl
          text-sm font-medium
          transition-all duration-200
          {{ $active
              ? 'bg-gradient-to-r from-slate-800 to-slate-700 text-white shadow-[0_4px_14px_rgba(15,23,42,0.25)]'
              : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">

    {{-- Dot indicator --}}
    <span class="w-1.5 h-1.5 rounded-full transition-all
                 {{ $active ? 'bg-emerald-400' : 'bg-slate-300 group-hover:bg-slate-400' }}">
    </span>

    <span class="flex-1">{{ $slot }}</span>

    {{-- Arrow --}}
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-3.5 h-3.5 transition-all
                {{ $active ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-1 group-hover:opacity-60 group-hover:translate-x-0' }}"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
</a>