@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group ' . ($active ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white')]) }}>
    {{ $slot }}
</a>