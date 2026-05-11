@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentMark.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Student Marks</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Add Marks</span>
</nav>
@endsection

@section('content')

<style>
/* ── Lazy-load searchable dropdown ── */
.ls-wrap { position: relative; margin-bottom: 1rem; }
.ls-wrap label { display:block; font-size:.8125rem; font-weight:500; color:#374151; margin-bottom:.375rem; }

.ls-field {
    position: relative;
    display: flex; align-items: center;
}

.ls-input {
    width: 100%;
    padding: .5rem 2.25rem .5rem .75rem;
    border: 1px solid #d1d5db;
    border-radius: .5rem;
    font-size: .875rem; color: #111827;
    font-family: inherit;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    background: #fff;
}

.ls-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}

.ls-input.has-value { color: #1e293b; font-weight: 500; }
.ls-input.error     { border-color: #ef4444; }

.ls-chevron {
    position: absolute; right: .6rem;
    pointer-events: none; color: #9ca3af;
    transition: transform .2s;
}

.ls-wrap.open .ls-chevron { transform: rotate(180deg); }

.ls-clear {
    position: absolute; right: 1.8rem;
    cursor: pointer; color: #9ca3af; font-size: .75rem;
    padding: 2px 4px; border-radius: 3px;
    display: none;
    transition: color .15s;
    z-index: 1;
}
.ls-clear:hover { color: #ef4444; }

/* dropdown panel */
.ls-panel {
    display: none;
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: .5rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    z-index: 200;
    max-height: 260px;
    overflow-y: auto;
}

.ls-panel.open { display: block; }

.ls-search-row {
    padding: .5rem .625rem;
    border-bottom: 1px solid #f3f4f6;
    position: sticky; top: 0;
    background: #fff; z-index: 1;
}

.ls-search-inner {
    display: flex; align-items: center; gap: .375rem;
    padding: .3rem .5rem;
    background: #f9fafb; border: 1px solid #e5e7eb;
    border-radius: .375rem;
}

.ls-search-inner svg { color: #9ca3af; flex-shrink: 0; }

.ls-search-box {
    border: none; outline: none; background: transparent;
    font-size: .8125rem; color: #111827; width: 100%;
    font-family: inherit;
}

.ls-option {
    padding: .55rem .875rem;
    font-size: .8125rem; color: #374151;
    cursor: pointer;
    transition: background .12s;
}

.ls-option:hover, .ls-option.focused { background: #eef2ff; color: #4338ca; }

.ls-empty {
    padding: 1.25rem .875rem;
    font-size: .8rem; color: #9ca3af;
    text-align: center;
}

.ls-loading {
    padding: 1rem .875rem;
    font-size: .8rem; color: #6b7280;
    text-align: center;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}

.ls-spinner {
    width: 14px; height: 14px;
    border: 2px solid #e5e7eb;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: ls-spin .7s linear infinite;
}

@keyframes ls-spin { to { transform: rotate(360deg); } }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Student Marks</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Enter marks for a student</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('studentMark.store') }}" id="marks-form">
        @csrf

        {{-- ── Lazy-load Student Picker ── --}}
        <div class="ls-wrap" id="student-wrap">
            <label for="student_search">
                Student <span style="color:#ef4444;">*</span>
            </label>

            {{-- hidden input submitted with the form --}}
            <input type="hidden" id="student_id" name="student_id" required>

            <div class="ls-field">
                <input
                    type="text"
                    id="student_search"
                    class="ls-input"
                    placeholder="Search by name or admission number…"
                    autocomplete="off"
                    readonly
                    onfocus="openStudentPicker()"
                >
                <span class="ls-clear" id="student-clear" onclick="clearStudent()">✕</span>
                <svg class="ls-chevron" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="ls-panel" id="student-panel">
                <div class="ls-search-row">
                    <div class="ls-search-inner">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input
                            type="text"
                            id="student-search-box"
                            class="ls-search-box"
                            placeholder="Type to search…"
                            oninput="fetchStudents(this.value)"
                        >
                    </div>
                </div>
                <div id="student-results"></div>
            </div>
        </div>

        <x-core-package::form.select name="schedule_id" label="Exam Schedule" required>
            <option value="">Select Exam Schedule</option>
            @foreach($examSchedules ?? [] as $schedule)
            <option value="{{ $schedule->schedule_id }}">
                {{ $schedule->exam?->exam_name ?? 'Exam #'.$schedule->exam_id }} – {{ $schedule->subject?->subject_name ?? 'Subject #'.$schedule->subject_id }} (Max: {{ $schedule->total_marks }})
            </option>
            @endforeach
        </x-core-package::form.select>

        <x-core-package::form.input name="marks_obtained" label="Marks Obtained" type="number" step="0.01" required />

        <x-core-package::form.input name="grade" label="Grade" placeholder="Auto-calculated if not provided" />

        <x-core-package::form.textarea name="remarks" label="Remarks" placeholder="Optional comments…" rows="3" />

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Save Marks</x-core-package::btn>
            <x-core-package::btn :href="route('studentMark.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

<script>
(function () {
    var debounceTimer = null;
    var isOpen = false;

    window.openStudentPicker = function () {
        document.getElementById('student_search').removeAttribute('readonly');
        document.getElementById('student-wrap').classList.add('open');
        document.getElementById('student-panel').classList.add('open');
        isOpen = true;
        // focus the inner search box
        setTimeout(function () {
            document.getElementById('student-search-box').focus();
        }, 50);
        fetchStudents('');
    };

    window.closeStudentPicker = function () {
        document.getElementById('student-wrap').classList.remove('open');
        document.getElementById('student-panel').classList.remove('open');
        document.getElementById('student_search').setAttribute('readonly', true);
        isOpen = false;
    };

    window.clearStudent = function () {
        document.getElementById('student_id').value = '';
        document.getElementById('student_search').value = '';
        document.getElementById('student_search').classList.remove('has-value');
        document.getElementById('student-clear').style.display = 'none';
        document.getElementById('student-search-box').value = '';
        openStudentPicker();
    };

    window.fetchStudents = function (q) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            var results = document.getElementById('student-results');
            results.innerHTML = '<div class="ls-loading"><div class="ls-spinner"></div>Loading…</div>';

            fetch('/students/search?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.length) {
                    results.innerHTML = '<div class="ls-empty">No students found</div>';
                    return;
                }
                results.innerHTML = data.map(function (s) {
                    return '<div class="ls-option" data-id="' + s.id + '" data-text="' + escHtml(s.text) + '" onclick="selectStudent(' + s.id + ', \'' + escJs(s.text) + '\')">' + escHtml(s.text) + '</div>';
                }).join('');
            })
            .catch(function () {
                results.innerHTML = '<div class="ls-empty">Error loading students</div>';
            });
        }, 200);
    };

    window.selectStudent = function (id, text) {
        document.getElementById('student_id').value = id;
        document.getElementById('student_search').value = text;
        document.getElementById('student_search').classList.add('has-value');
        document.getElementById('student-clear').style.display = 'inline';
        closeStudentPicker();
    };

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function escJs(str) {
        return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'");
    }

    // close on outside click
    document.addEventListener('click', function (e) {
        if (isOpen && !document.getElementById('student-wrap').contains(e.target)) {
            closeStudentPicker();
            // if nothing selected, restore placeholder
            if (!document.getElementById('student_id').value) {
                document.getElementById('student_search').value = '';
            }
        }
    });
})();
</script>

@endsection
