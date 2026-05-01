@props(['href' => null, 'color' => 'primary', 'size' => 'md', 'type' => 'button'])
@php
$colors = [
    'primary'   => 'background:#4f46e5;color:#fff;',
    'secondary' => 'background:#fff;color:#374151;border:1px solid #d1d5db;',
    'success'   => 'background:#16a34a;color:#fff;',
    'danger'    => 'background:#dc2626;color:#fff;',
    'warning'   => 'background:#d97706;color:#fff;',
    'dark'      => 'background:#374151;color:#fff;',
    'ghost'     => 'background:transparent;color:#475569;',
    'green'     => 'background:#16a34a;color:#fff;',
    'orange'    => 'background:#ea580c;color:#fff;',
    'red'       => 'background:#dc2626;color:#fff;',
    'blue'      => 'background:#2563eb;color:#fff;',
    'indigo'    => 'background:#4f46e5;color:#fff;',
];
$sizes = [
    'xs' => 'padding:.25rem .625rem;font-size:.7rem;border-radius:.375rem;',
    'sm' => 'padding:.375rem .75rem;font-size:.75rem;border-radius:.5rem;',
    'md' => 'padding:.5rem 1rem;font-size:.8125rem;border-radius:.5rem;',
    'lg' => 'padding:.625rem 1.25rem;font-size:.875rem;border-radius:.5rem;',
];
$base = 'display:inline-flex;align-items:center;gap:.375rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:filter .15s,opacity .15s;box-shadow:0 1px 2px rgba(0,0,0,.08);';
$style = $base . ($colors[$color] ?? $colors['primary']) . ($sizes[$size] ?? $sizes['md']);
@endphp
@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['style' => $style]) }} onmouseover="this.style.filter='brightness(.92)'" onmouseout="this.style.filter='none'">{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['style' => $style]) }} onmouseover="this.style.filter='brightness(.92)'" onmouseout="this.style.filter='none'">{{ $slot }}</button>
@endif
