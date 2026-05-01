@props(['type' => 'success', 'title' => null])
@php
$cfg = match($type) {
    'success' => ['border' => '#bbf7d0', 'bg' => '#f0fdf4', 'left' => '#16a34a', 'text' => '#166534', 'sub' => '#15803d',
                  'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'],
    'error'   => ['border' => '#fecaca', 'bg' => '#fef2f2', 'left' => '#dc2626', 'text' => '#991b1b', 'sub' => '#b91c1c',
                  'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'],
    'warning' => ['border' => '#fde68a', 'bg' => '#fffbeb', 'left' => '#d97706', 'text' => '#92400e', 'sub' => '#b45309',
                  'icon' => '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'],
    default   => ['border' => '#bfdbfe', 'bg' => '#eff6ff', 'left' => '#3b82f6', 'text' => '#1e40af', 'sub' => '#1d4ed8',
                  'icon' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'],
};
@endphp
<div style="background:{{ $cfg['bg'] }};border:1px solid {{ $cfg['border'] }};border-left:4px solid {{ $cfg['left'] }};border-radius:.5rem;padding:.875rem 1rem;display:flex;gap:.75rem;margin-bottom:1rem;">
    <svg style="width:1.125rem;height:1.125rem;color:{{ $cfg['text'] }};flex-shrink:0;margin-top:.0625rem;" fill="currentColor" viewBox="0 0 20 20">{!! $cfg['icon'] !!}</svg>
    <div style="flex:1;">
        @if($title)
        <p style="font-size:.8125rem;font-weight:600;color:{{ $cfg['text'] }};margin:0;">{{ $title }}</p>
        @endif
        <div style="font-size:.8125rem;color:{{ $cfg['sub'] }};{{ $title ? 'margin-top:.25rem;' : '' }}">{{ $slot }}</div>
    </div>
</div>
