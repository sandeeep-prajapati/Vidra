@props(['label', 'value', 'color' => 'indigo'])
@php
$accents = [
    'indigo' => '#4f46e5',
    'green'  => '#16a34a',
    'yellow' => '#d97706',
    'red'    => '#dc2626',
    'blue'   => '#2563eb',
    'purple' => '#9333ea',
];
$accent = $accents[$color] ?? $accents['indigo'];
@endphp
<div style="background:#fff;border:1px solid #e2e8f0;border-left:4px solid {{ $accent }};border-radius:.75rem;padding:1rem 1.25rem;box-shadow:0 1px 2px rgba(15,23,42,.04);">
    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0;">{{ $label }}</p>
    <p style="font-size:1.625rem;font-weight:700;color:#1e293b;margin:.25rem 0 0;line-height:1.2;">{{ $value }}</p>
</div>
