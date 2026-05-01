@props(['title', 'description' => null])
<div style="margin-bottom:2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:.75rem;margin-bottom:1.25rem;border-bottom:1px solid #f1f5f9;">
        <div>
            <h2 style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $title }}</h2>
            @if($description)
            <p style="font-size:.75rem;color:#64748b;margin:.25rem 0 0;">{{ $description }}</p>
            @endif
        </div>
        @if(isset($action))<div>{{ $action }}</div>@endif
    </div>
    {{ $slot }}
</div>
