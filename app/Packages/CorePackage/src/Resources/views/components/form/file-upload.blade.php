@props([
    'label'    => null,
    'name',
    'required' => false,
    'accept'   => 'image/*',
    'preview'  => null,
    'hint'     => null,
    'multiple' => false,
])
@php
$uid      = 'fu_' . md5($name . uniqid());
$isImage  = str_contains($accept, 'image');
$hasError = isset($errors) && $errors->has($name);
@endphp

<div id="{{ $uid }}_wrap">
    @if($label)
    <label for="{{ $uid }}_input"
           style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">
        {{ $label }}@if($required)<span style="color:#ef4444;margin-left:.125rem;">*</span>@endif
    </label>
    @endif

    {{-- Drop zone --}}
    <div id="{{ $uid }}_zone"
         onclick="document.getElementById('{{ $uid }}_input').click()"
         ondragover="event.preventDefault();this.style.borderColor='#6366f1';this.style.background='#eef2ff';"
         ondragleave="this.style.borderColor='{{ $hasError ? '#fca5a5' : '#d1d5db' }}';this.style.background='#f9fafb';"
         ondrop="handleFileDrop_{{ $uid }}(event)"
         style="border:2px dashed {{ $hasError ? '#fca5a5' : '#d1d5db' }};border-radius:.625rem;background:#f9fafb;padding:1.5rem 1rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;position:relative;">

        {{-- Existing preview --}}
        <div id="{{ $uid }}_existing"
             style="{{ $preview ? '' : 'display:none;' }}margin-bottom:.75rem;">
            @if($isImage && $preview)
            <img id="{{ $uid }}_existing_img"
                 src="{{ $preview }}"
                 alt="Current file"
                 style="max-height:6rem;max-width:100%;border-radius:.5rem;object-fit:cover;border:1px solid #e2e8f0;margin:0 auto;display:block;">
            <p style="font-size:.7rem;color:#94a3b8;margin:.375rem 0 0;">Current file</p>
            @else
            <div id="{{ $uid }}_existing_doc" style="display:inline-flex;align-items:center;gap:.5rem;background:#e0e7ff;border-radius:.375rem;padding:.375rem .75rem;">
                <svg style="width:1rem;height:1rem;color:#4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span style="font-size:.75rem;font-weight:500;color:#3730a3;">{{ $preview ? basename($preview) : '' }}</span>
            </div>
            @endif
        </div>

        {{-- New preview (after selection) --}}
        <div id="{{ $uid }}_preview" style="display:none;margin-bottom:.75rem;">
            <img id="{{ $uid }}_preview_img"
                 src="" alt="Preview"
                 style="max-height:6rem;max-width:100%;border-radius:.5rem;object-fit:cover;border:1px solid #e2e8f0;margin:0 auto;display:none;">
            <div id="{{ $uid }}_preview_file" style="display:none;align-items:center;justify-content:center;gap:.5rem;background:#dcfce7;border-radius:.375rem;padding:.375rem .75rem;width:fit-content;margin:0 auto;">
                <svg style="width:1rem;height:1rem;color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span id="{{ $uid }}_filename" style="font-size:.75rem;font-weight:500;color:#166534;max-width:14rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
            </div>
        </div>

        {{-- Idle state --}}
        <div id="{{ $uid }}_idle">
            <div style="width:2.5rem;height:2.5rem;background:#e0e7ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .625rem;">
                <svg style="width:1.25rem;height:1.25rem;color:#4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <p style="font-size:.8125rem;font-weight:500;color:#374151;margin:0 0 .25rem;">
                Drag &amp; drop {{ $isImage ? 'an image' : 'a file' }} here, or click to browse
            </p>
            <p style="font-size:.7rem;color:#94a3b8;margin:0;">
                {{ $hint ?: ($isImage ? 'JPG, PNG, GIF, WEBP up to 2 MB' : 'Max 10 MB') }}
            </p>
        </div>

        {{-- Change / remove bar (shown after selection or when existing) --}}
        <div id="{{ $uid }}_actions"
             style="{{ ($preview) ? '' : 'display:none;' }}margin-top:.625rem;display:{{ $preview ? 'flex' : 'none' }};align-items:center;justify-content:center;gap:.75rem;">
            <button type="button"
                    onclick="event.stopPropagation();document.getElementById('{{ $uid }}_input').click()"
                    style="font-size:.7rem;font-weight:600;color:#4f46e5;background:none;border:none;cursor:pointer;padding:0;">
                Change
            </button>
            <button type="button"
                    onclick="event.stopPropagation();clearFile_{{ $uid }}()"
                    style="font-size:.7rem;font-weight:600;color:#dc2626;background:none;border:none;cursor:pointer;padding:0;">
                Remove
            </button>
        </div>
    </div>

    {{-- Hidden file input --}}
    <input type="file"
           id="{{ $uid }}_input"
           name="{{ $name }}"
           accept="{{ $accept }}"
           {{ $multiple ? 'multiple' : '' }}
           style="display:none;"
           onchange="handleFileChange_{{ $uid }}(this)">

    {{-- Clear flag (sent when user removes existing file) --}}
    <input type="hidden" id="{{ $uid }}_clear" name="{{ $name }}_clear" value="0">

    @if(isset($errors))
    @error($name)
    <p style="margin-top:.375rem;font-size:.7rem;color:#dc2626;display:flex;align-items:center;gap:.25rem;">
        <svg style="width:.75rem;height:.75rem;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ $message }}
    </p>
    @enderror
    @endif
</div>

<script>
(function () {
    const uid      = '{{ $uid }}';
    const isImage  = {{ $isImage ? 'true' : 'false' }};

    function showFile(file) {
        const zone     = document.getElementById(uid + '_zone');
        const idle     = document.getElementById(uid + '_idle');
        const preview  = document.getElementById(uid + '_preview');
        const existing = document.getElementById(uid + '_existing');
        const actions  = document.getElementById(uid + '_actions');
        const previewImg  = document.getElementById(uid + '_preview_img');
        const previewFile = document.getElementById(uid + '_preview_file');
        const filename = document.getElementById(uid + '_filename');

        idle.style.display = 'none';
        if (existing) existing.style.display = 'none';
        preview.style.display = 'block';
        actions.style.display = 'flex';
        zone.style.borderColor = '#6366f1';
        zone.style.background  = '#f5f3ff';

        if (isImage && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewFile.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.style.display = 'none';
            previewFile.style.display = 'flex';
            filename.textContent = file.name;
        }
    }

    window['handleFileChange_' + uid] = function (input) {
        if (input.files && input.files.length > 0) {
            showFile(input.files[0]);
            document.getElementById(uid + '_clear').value = '0';
        }
    };

    window['handleFileDrop_' + uid] = function (event) {
        event.preventDefault();
        const zone = document.getElementById(uid + '_zone');
        zone.style.borderColor = '#d1d5db';
        zone.style.background  = '#f9fafb';

        const files = event.dataTransfer.files;
        if (files && files.length > 0) {
            const input = document.getElementById(uid + '_input');
            const dt    = new DataTransfer();
            dt.items.add(files[0]);
            input.files = dt.files;
            showFile(files[0]);
            document.getElementById(uid + '_clear').value = '0';
        }
    };

    window['clearFile_' + uid] = function () {
        const zone     = document.getElementById(uid + '_zone');
        const idle     = document.getElementById(uid + '_idle');
        const preview  = document.getElementById(uid + '_preview');
        const existing = document.getElementById(uid + '_existing');
        const actions  = document.getElementById(uid + '_actions');
        const previewImg  = document.getElementById(uid + '_preview_img');

        document.getElementById(uid + '_input').value = '';
        previewImg.src = '';
        preview.style.display  = 'none';
        if (existing) existing.style.display = 'none';
        idle.style.display     = 'block';
        actions.style.display  = 'none';
        zone.style.borderColor = '#d1d5db';
        zone.style.background  = '#f9fafb';
        document.getElementById(uid + '_clear').value = '1';
    };
})();
</script>
