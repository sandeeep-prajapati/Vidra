@props(['label' => null, 'name', 'required' => false, 'rows' => 3, 'hint' => null])
@php
$hasError = isset($errors) && $errors->has($name);
$taStyle = 'display:block;width:100%;border-radius:.5rem;border:1px solid ' . ($hasError ? '#fca5a5;background:#fef2f2;color:#111827;' : 'var(--border);background:var(--bg-surface);color:var(--text-primary);') . 'padding:.5rem .75rem;font-size:.8125rem;outline:none;resize:vertical;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;font-family:inherit;';
@endphp
<div>
    @if($label)
    <label for="{{ $name }}" style="display:block;font-size:.8125rem;font-weight:500;color:var(--text-secondary);margin-bottom:.375rem;">
        {{ $label }}@if($required)<span style="color:#ef4444;margin-left:.125rem;">*</span>@endif
    </label>
    @endif
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['style' => $taStyle]) }}
        onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,.15)'"
        onblur="this.style.borderColor='{{ $hasError ? '#fca5a5' : '#d1d5db' }}';this.style.boxShadow='none'"
    >{{ $slot }}</textarea>
    @if($hint && !$hasError)
    <p style="margin-top:.25rem;font-size:.7rem;color:#9ca3af;">{{ $hint }}</p>
    @endif
    @if(isset($errors))
    @error($name)
    <p style="margin-top:.25rem;font-size:.7rem;color:#dc2626;display:flex;align-items:center;gap:.25rem;">
        <svg style="width:.75rem;height:.75rem;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ $message }}
    </p>
    @enderror
    @endif
</div>
