@props(['color' => 'gray'])
@php
$styles = match($color) {
    'green'  => 'background:#f0fdf4;color:#166534;outline:1px solid rgba(22,101,52,.15);',
    'yellow' => 'background:#fefce8;color:#854d0e;outline:1px solid rgba(133,77,14,.15);',
    'blue'   => 'background:#eff6ff;color:#1e40af;outline:1px solid rgba(30,64,175,.15);',
    'red'    => 'background:#fef2f2;color:#991b1b;outline:1px solid rgba(153,27,27,.15);',
    'indigo' => 'background:#eef2ff;color:#3730a3;outline:1px solid rgba(55,48,163,.15);',
    'purple' => 'background:#faf5ff;color:#6b21a8;outline:1px solid rgba(107,33,168,.15);',
    'orange' => 'background:#fff7ed;color:#9a3412;outline:1px solid rgba(154,52,18,.15);',
    default  => 'background:#f8fafc;color:#475569;outline:1px solid rgba(71,85,105,.15);',
};
@endphp
<span {{ $attributes->merge(['style' => 'display:inline-flex;align-items:center;border-radius:9999px;padding:.2rem .625rem;font-size:.7rem;font-weight:700;' . $styles]) }}>
    {{ $slot }}
</span>
