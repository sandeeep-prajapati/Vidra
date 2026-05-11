@props(['title', 'subtitle' => null, 'back' => null])
<div style="margin-bottom:1.5rem;">
    @if($back)
    <a href="{{ $back }}" style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:500;color:var(--text-secondary);text-decoration:none;margin-bottom:.75rem;transition:color .15s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Back
    </a>
    @endif
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
        <div>
            <h1 style="font-size:1.375rem;font-weight:700;color:var(--text-primary);margin:0;line-height:1.3;">{{ $title }}</h1>
            @if($subtitle)
            <p style="font-size:.8125rem;color:var(--text-secondary);margin:.375rem 0 0;">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($actions))
        <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;padding-top:.125rem;">{{ $actions }}</div>
        @endif
    </div>
</div>
