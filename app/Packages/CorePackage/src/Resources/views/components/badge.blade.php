@props(['color' => 'gray'])
@php
$styles = match($color) {
    'green'  => 'background:var(--badge-green-bg);color:var(--badge-green-clr);outline:1px solid var(--badge-green-bd);',
    'yellow' => 'background:var(--badge-yellow-bg);color:var(--badge-yellow-clr);outline:1px solid var(--badge-yellow-bd);',
    'blue'   => 'background:var(--badge-blue-bg);color:var(--badge-blue-clr);outline:1px solid var(--badge-blue-bd);',
    'red'    => 'background:var(--badge-red-bg);color:var(--badge-red-clr);outline:1px solid var(--badge-red-bd);',
    'indigo' => 'background:var(--badge-indigo-bg);color:var(--badge-indigo-clr);outline:1px solid var(--badge-indigo-bd);',
    'purple' => 'background:var(--badge-purple-bg);color:var(--badge-purple-clr);outline:1px solid var(--badge-purple-bd);',
    'orange' => 'background:var(--badge-orange-bg);color:var(--badge-orange-clr);outline:1px solid var(--badge-orange-bd);',
    default  => 'background:var(--badge-gray-bg);color:var(--badge-gray-clr);outline:1px solid var(--badge-gray-bd);',
};
@endphp
<span {{ $attributes->merge(['style' => 'display:inline-flex;align-items:center;border-radius:9999px;padding:.2rem .625rem;font-size:.7rem;font-weight:700;' . $styles]) }}>
    {{ $slot }}
</span>
