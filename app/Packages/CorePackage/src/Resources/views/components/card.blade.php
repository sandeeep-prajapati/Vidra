@props(['title' => null, 'noPadding' => false])
<div {{ $attributes->merge(['style' => 'background:var(--bg-surface);border:1px solid var(--border);border-radius:.75rem;box-shadow:var(--shadow-sm);overflow:hidden;']) }}>
    @if($title || isset($action))
    <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.875rem 1.25rem;border-bottom:1px solid var(--border-light);">
        @if($title)
        <h3 style="font-size:.8125rem;font-weight:600;color:var(--text-primary);margin:0;text-transform:uppercase;letter-spacing:.04em;">{{ $title }}</h3>
        @endif
        @if(isset($action))
        <div style="flex-shrink:0;">{{ $action }}</div>
        @endif
    </div>
    @endif
    <div @if(!$noPadding) style="padding:1.25rem;" @endif>
        {{ $slot }}
    </div>
</div>
