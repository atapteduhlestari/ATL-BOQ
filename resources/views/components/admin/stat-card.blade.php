@props([
    'label'  => 'Label',
    'value'  => '0',
    'change' => null,       // contoh: '+12%'
    'color'  => 'slate',    // slate | emerald | amber | rose | blue
    'icon'   => null,       // html svg
])

@php
    $colors = [
        'slate'   => 'from-slate-800 to-slate-600',
        'emerald' => 'from-emerald-500 to-emerald-400',
        'amber'   => 'from-amber-500 to-amber-400',
        'rose'    => 'from-rose-500 to-rose-400',
        'blue'    => 'from-blue-500 to-blue-400',
    ];
    $gradient = $colors[$color] ?? $colors['slate'];
@endphp

<div class="bg-white/70 backdrop-blur-xl
            border border-white/60
            rounded-3xl
            shadow-[0_8px_32px_rgba(15,23,42,0.08)]
            hover:shadow-[0_12px_40px_rgba(15,23,42,0.12)]
            transition-all duration-200
            p-5">

    <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br {{ $gradient }}
                    flex items-center justify-center text-white shadow-sm">
            {{ $icon }}
        </div>

        @if($change)
            <span class="text-xs font-semibold px-2 py-1 rounded-xl
                         {{ str_starts_with($change, '-')
                            ? 'bg-rose-50 text-rose-600'
                            : 'bg-emerald-50 text-emerald-600' }}">
                {{ $change }}
            </span>
        @endif
    </div>

    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ $label }}</p>
    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $value }}</p>

</div>