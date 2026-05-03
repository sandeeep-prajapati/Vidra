---
name: ui-design-components
description: Complete UI design system for the school management app. Covers the CorePackage component library, design tokens, layout patterns, form patterns, and all reusable Blade components. Reference this skill whenever building or modifying any view in any module.
---

# UI Design System — School Management App

> All UI is built using **CorePackage** (`app/Packages/CorePackage/`). Every module view extends the shared layout and uses the shared Blade component library. Never inline raw CSS blocks or duplicate styling — use components.

---

## 1. Design Tokens

### Colors
| Role        | Hex       | Usage |
|-------------|-----------|-------|
| Primary     | `#4f46e5` | Buttons, active nav, links, focus rings |
| Success     | `#16a34a` | Enroll actions, save confirmations |
| Warning     | `#d97706` | Document actions, caution states |
| Danger      | `#dc2626` | Delete, destructive, error states |
| Blue        | `#2563eb` | Health log actions, info states |
| Dark        | `#374151` | Contact save, neutral actions |
| Slate BG    | `#f1f5f9` | Page background |
| Surface     | `#ffffff` | Cards, inputs |
| Border      | `#e2e8f0` | Card borders, table dividers |
| Text Primary   | `#1e293b` | Headings, strong values |
| Text Secondary | `#64748b` | Labels, muted descriptions |
| Text Muted     | `#94a3b8` | Placeholders, timestamps, uppercase labels |

### Typography
- **Font**: Inter (via bunny.net CDN), fallback: `ui-sans-serif, system-ui`
- **Base size**: `0.8125rem` (13px) for body/inputs
- **Labels**: `0.8125rem`, `font-weight:500`, `color:#374151`
- **Section headings**: `0.9375rem`, `font-weight:600`, `color:#1e293b`
- **Page title**: `1.375rem`, `font-weight:700`, `color:#1e293b`
- **Table headers**: `0.65rem`, `font-weight:600`, `color:#94a3b8`, uppercase, `letter-spacing:.05em`
- **Badge / pill text**: `0.7rem`, `font-weight:700`

### Spacing
- Card padding: `1.25rem`
- Card header padding: `0.875rem 1.25rem`
- Form gap between fields: `1.25rem`
- Section gap: `2rem` (between form sections)
- Input padding: `0.5rem 0.75rem`
- Table cell padding: `0.875rem 1.25rem` (first col), `0.875rem 1rem` (others)

### Border Radius
- Cards: `0.75rem`
- Inputs / selects: `0.5rem`
- Buttons: `0.5rem`
- Badges: `9999px` (pill)
- Avatars: `50%`
- Mini cards / doc items: `0.5rem`

---

## 2. App Layout

The shared layout lives at:
`app/Packages/CorePackage/src/Resources/views/layouts/app.blade.php`

Every module view must start with:
```blade
@extends('core-package::layouts.app')
```

### Layout Structure
```
┌─────────────────────────────────────────────────┐
│  Sidebar (fixed 16rem)   │  Main Content Area    │
│  ─ Logo + app name       │  ─ Top bar (breadcrumb│
│  ─ Nav links (icons)     │    + date)            │
│  ─ User area (bottom)    │  ─ Toast notifications│
│                          │  ─ @yield('content')  │
│                          │  ─ Footer             │
└─────────────────────────────────────────────────┘
```

### Breadcrumb Slot
Each view should yield a breadcrumb:
```blade
@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('students.index') }}" style="color:#94a3b8;text-decoration:none;">Students</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Current Page</span>
</nav>
@endsection
```

### Flash Messages
Set `session('success')` or `session('error')` in controllers — the layout renders them automatically as auto-dismissing toast cards (top-right, 5s timeout).

---

## 3. CorePackage Component Library

All components registered via `anonymousComponentPath` in `CorePackageServiceProvider`.
Usage prefix: `<x-core-package::component-name>`

---

### 3.1 `<x-core-package::card>`

White surface card with optional titled header and action slot.

```blade
{{-- Basic card --}}
<x-core-package::card title="Section Title">
    content here
</x-core-package::card>

{{-- Card with action button in header --}}
<x-core-package::card title="Students">
    <x-slot:action>
        <x-core-package::btn href="..." color="primary" size="sm">+ Add</x-core-package::btn>
    </x-slot:action>
    content here
</x-core-package::card>

{{-- Card without padding (for tables) --}}
<x-core-package::card title="List" :noPadding="true">
    <table>...</table>
    <div style="padding:1rem;">{{ $paginator->links() }}</div>
</x-core-package::card>
```

**Props**: `title` (string, optional), `noPadding` (bool, default false)
**Slots**: default, `action` (renders in header right side)

---

### 3.2 `<x-core-package::page-header>`

Page-level title block with optional back link and action buttons.

```blade
<x-core-package::page-header
    title="Students"
    subtitle="Manage all student records."
    :back="route('students.index')"
>
    <x-slot:actions>
        <x-core-package::btn href="..." color="primary">+ Add Student</x-core-package::btn>
    </x-slot:actions>
</x-core-package::page-header>
```

**Props**: `title` (required), `subtitle` (optional), `back` (URL string, optional)
**Slots**: default (unused), `actions` (right side)

---

### 3.3 `<x-core-package::badge>`

Colored pill for statuses, types, flags.

```blade
<x-core-package::badge color="green">Active</x-core-package::badge>
<x-core-package::badge color="yellow">Inactive</x-core-package::badge>
<x-core-package::badge color="blue">Graduated</x-core-package::badge>
<x-core-package::badge color="orange">Transferred</x-core-package::badge>
<x-core-package::badge color="indigo">Primary</x-core-package::badge>
<x-core-package::badge color="red">Danger</x-core-package::badge>
<x-core-package::badge color="gray">Default</x-core-package::badge>
```

**Props**: `color` — `green | yellow | blue | red | indigo | purple | orange | gray`

#### Standard Status → Color Mapping
```php
$statusColor = match($record->status) {
    'Active'      => 'green',
    'Inactive'    => 'yellow',
    'Graduated'   => 'blue',
    'Transferred' => 'orange',
    default       => 'gray',
};
```

---

### 3.4 `<x-core-package::btn>`

Unified button/link component.

```blade
{{-- Link button --}}
<x-core-package::btn href="{{ route('students.create') }}" color="primary">Add Student</x-core-package::btn>

{{-- Submit button --}}
<x-core-package::btn type="submit" color="success">Save</x-core-package::btn>

{{-- JS button --}}
<x-core-package::btn type="button" color="ghost" onclick="doSomething()">Click</x-core-package::btn>

{{-- With icon --}}
<x-core-package::btn type="submit" color="primary">
    <svg style="width:.875rem;height:.875rem;" .../>
    Save Student
</x-core-package::btn>
```

**Props**:
- `href` — renders as `<a>` if provided, otherwise `<button>`
- `color` — `primary | secondary | success | danger | warning | dark | ghost | green | orange | red | blue | indigo`
- `size` — `xs | sm | md | lg` (default: `md`)
- `type` — `button | submit | reset` (default: `button`)

---

### 3.5 `<x-core-package::alert>`

Feedback banners for validation errors, confirmations, warnings.

```blade
{{-- Validation error summary --}}
@if($errors->any())
<x-core-package::alert type="error" title="Please fix the following errors:">
    <ul style="margin:.375rem 0 0;padding-left:1rem;list-style:disc;">
        @foreach($errors->all() as $error)
        <li style="font-size:.75rem;margin-bottom:.125rem;">{{ $error }}</li>
        @endforeach
    </ul>
</x-core-package::alert>
@endif

{{-- Info alert --}}
<x-core-package::alert type="info">
    This student has no enrollment records yet.
</x-core-package::alert>
```

**Props**: `type` — `success | error | warning | info`, `title` (optional)

---

### 3.6 `<x-core-package::stats-card>`

Metric card with accent-colored left border.

```blade
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <x-core-package::stats-card label="Total Students" :value="$students->total()"          color="indigo" />
    <x-core-package::stats-card label="Active"          :value="$activeCount"                color="green" />
    <x-core-package::stats-card label="Graduated"       :value="$graduatedCount"             color="blue" />
    <x-core-package::stats-card label="Inactive"        :value="$inactiveCount"              color="yellow" />
</div>
```

**Props**: `label` (required), `value` (required), `color` — `indigo | green | yellow | red | blue | purple`

---

## 4. Form Components

### 4.1 `<x-core-package::form.input>`

Text input with label, required marker, focus styles, and inline `@error` display.

```blade
<x-core-package::form.input
    name="first_name"
    label="First Name"
    required
    type="text"
    :value="old('first_name', $student->first_name ?? '')"
    placeholder="e.g. Ravi"
/>
```

**Props**: `name` (required), `label` (optional), `required` (bool), `type` (default: `text`), `hint` (helper text), any HTML input attribute via `$attributes`

---

### 4.2 `<x-core-package::form.select>`

Select dropdown with label, placeholder option, and inline error display.

```blade
<x-core-package::form.select name="status" label="Status" required placeholder="Select Status">
    @foreach(['Active','Inactive','Graduated','Transferred'] as $s)
    <option value="{{ $s }}" {{ old('status', $record->status ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
    @endforeach
</x-core-package::form.select>
```

**Props**: `name` (required), `label` (optional), `required` (bool), `placeholder` (first empty option text, optional)
**Slot**: option elements

---

### 4.3 `<x-core-package::form.textarea>`

Textarea with label and inline error display.

```blade
<x-core-package::form.textarea name="current_address" label="Current Address" required rows="3">
    {{ old('current_address', $student->current_address ?? '') }}
</x-core-package::form.textarea>
```

**Props**: `name` (required), `label` (optional), `required` (bool), `rows` (default: 3), `hint`
**Slot**: textarea content (the current value)

---

### 4.4 `<x-core-package::form.section>`

Titled section divider inside a form. Groups related fields visually.

```blade
<x-core-package::form.section title="Basic Information" description="Student's personal details.">
    <x-slot:action>
        {{-- optional button e.g. '+ Add Row' --}}
    </x-slot:action>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
        {{-- fields --}}
    </div>
</x-core-package::form.section>
```

**Props**: `title` (required), `description` (optional)
**Slots**: default (fields), `action` (header right side)

---

## 5. Page Patterns

### 5.1 List / Index Page

```blade
@extends('core-package::layouts.app')

@section('breadcrumb') ... @endsection

@section('content')

{{-- Header --}}
<x-core-package::page-header title="Module Name">
    <x-slot:actions>
        <x-core-package::btn href="{{ route('module.create') }}" color="primary">+ Add</x-core-package::btn>
    </x-slot:actions>
</x-core-package::page-header>

{{-- Stats row (4 columns) --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <x-core-package::stats-card ... />
</div>

{{-- Table card --}}
<x-core-package::card :noPadding="true">
    {{-- Filter bar --}}
    <form method="GET" style="padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;background:#fafafa;">
        {{-- search input + status select + filter/reset buttons --}}
    </form>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Column</th>
                    ...
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding:.875rem 1.25rem;">...</td>
                </tr>
                @empty
                <tr><td colspan="N" style="padding:3rem;text-align:center;">
                    {{-- Empty state icon + message --}}
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination (inside card, after table) --}}
    @if($records->hasPages())
    <div style="padding:.875rem 1.25rem;border-top:1px solid #f1f5f9;">
        {{ $records->links() }}
    </div>
    @endif
</x-core-package::card>

@endsection
```

### 5.2 Create / Edit Form Page

```blade
@extends('core-package::layouts.app')
@section('breadcrumb') ... @endsection
@section('content')

<x-core-package::page-header title="Add X" subtitle="..." :back="route('module.index')" />

{{-- Validation errors --}}
@if($errors->any())
<x-core-package::alert type="error" title="Please fix the following errors:">
    <ul ...>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</x-core-package::alert>
@endif

<form action="{{ route('module.store') }}" method="POST">
@csrf

{{-- Each logical group = one card + one form.section --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Group Title" description="...">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <x-core-package::form.input name="field" label="Label" required />
            ...
            {{-- Full-width field --}}
            <div style="grid-column:1/-1;">
                <x-core-package::form.textarea name="address" label="Address" />
            </div>
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- Action row --}}
<div style="display:flex;gap:.75rem;padding:.25rem 0 1rem;">
    <x-core-package::btn type="submit" color="primary">
        <svg .../>Save
    </x-core-package::btn>
    <x-core-package::btn href="{{ route('module.index') }}" color="secondary">Cancel</x-core-package::btn>
</div>
</form>

@endsection
```

### 5.3 Detail / Show Page

Two-column layout: main info left, action sidebar right.

```blade
@section('content')

{{-- Avatar + name header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        {{-- Avatar (photo or initials) --}}
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Name</h1>
                <x-core-package::badge color="green">Active</x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Subtitle · Detail</p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('module.edit', $record)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('module.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

{{-- Two-column grid --}}
<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    {{-- Main column: info cards --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <x-core-package::card title="Section">
            {{-- 2-column info grid --}}
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem .25rem;">
                <div style="padding:.625rem .5rem;">
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Label</p>
                    <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">{{ $value ?: '—' }}</p>
                </div>
                {{-- Full-width row --}}
                <div style="grid-column:1/-1;padding:.625rem .5rem;">...</div>
            </div>
        </x-core-package::card>
    </div>

    {{-- Sidebar: action cards --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <x-core-package::card title="Quick Info">...</x-core-package::card>
        <x-core-package::card title="Action Title">
            <form method="POST" style="display:flex;flex-direction:column;gap:.75rem;">...</form>
        </x-core-package::card>
    </div>
</div>

@endsection
```

---

## 6. Avatar / Initials Pattern

Used in index tables and show pages when no profile photo exists.

```blade
@php
$initials = strtoupper(substr($record->first_name,0,1) . substr($record->last_name,0,1));
$avatarColors = ['#4f46e5','#7c3aed','#2563eb','#0891b2','#16a34a','#d97706','#dc2626'];
$avatarColor  = $avatarColors[$record->id % count($avatarColors)];
@endphp

@if($record->profile_photo)
<img src="{{ $record->profile_photo }}" alt="{{ $record->first_name }}"
     style="width:2.25rem;height:2.25rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
@else
<div style="width:2.25rem;height:2.25rem;background:{{ $avatarColor }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
    <span style="font-size:.6875rem;font-weight:700;color:#fff;">{{ $initials }}</span>
</div>
@endif
```

---

## 7. Table Pattern

### Table Row Hover
```html
<tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;"
    onmouseover="this.style.background='#f8fafc'"
    onmouseout="this.style.background='transparent'">
```

### Action Buttons in Table
Three inline mini-buttons: View (indigo), Edit (slate), Delete (red).

```blade
<td style="padding:.875rem 1rem;text-align:right;">
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:.375rem;">
        <a href="{{ route('module.show', $record) }}"
           style="display:inline-flex;align-items:center;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#4f46e5;background:#eef2ff;border-radius:.375rem;text-decoration:none;">View</a>
        <a href="{{ route('module.edit', $record) }}"
           style="display:inline-flex;align-items:center;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#374151;background:#f1f5f9;border-radius:.375rem;text-decoration:none;">Edit</a>
        <form method="POST" action="{{ route('module.destroy', $record) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit"
                onclick="return confirm('Delete this record? This cannot be undone.')"
                style="display:inline-flex;align-items:center;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#dc2626;background:#fef2f2;border:none;border-radius:.375rem;cursor:pointer;">Delete</button>
        </form>
    </div>
</td>
```

---

## 8. Dynamic Row Pattern (Add/Remove rows with JS)

Used for Previous Education, Contacts, etc.

```html
<div id="rows-container" style="display:flex;flex-direction:column;gap:.875rem;">
    <!-- initial row (index 0) -->
    <div class="dynamic-row" style="border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;">
        <input type="hidden" name="items_index[]" value="0">
        ...fields with name="items[0][field]"...
    </div>
</div>
```

```javascript
let rowCount = 1;

function addRow() {
    const container = document.getElementById('rows-container');
    const i = rowCount++;
    const div = document.createElement('div');
    div.className = 'dynamic-row';
    div.style.cssText = 'border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;position:relative;';
    div.innerHTML = `
        <button type="button" onclick="this.closest('.dynamic-row').remove()"
            style="position:absolute;top:.625rem;right:.625rem;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;"
            onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#94a3b8'">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <input type="hidden" name="items_index[]" value="${i}">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.875rem;">
            <!-- fields with name="items[${i}][field]" -->
        </div>`;
    container.appendChild(div);
}
```

---

## 9. Input Styling (inline, for JS-generated rows)

When creating inputs inside JavaScript (dynamic rows), use this consistent inline style:

```javascript
const inputStyle = 'display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;color:#111827;background:#fff;outline:none;box-sizing:border-box;';
const selectStyle = inputStyle; // same
const labelStyle = 'display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;';
```

---

## 10. Sidebar Navigation — Adding New Modules

In `app/Packages/CorePackage/src/Resources/views/layouts/app.blade.php`, add a new `<a>` inside the `<nav>` block:

```blade
<a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- heroicon path -->
    </svg>
    Staff
</a>
```

Active state is handled by `.sidebar-link.active { background:#eef2ff; color:#4338ca; }` in the layout's `<style>` block.

---

## 11. Component File Locations

```
app/Packages/CorePackage/src/Resources/views/
├── layouts/
│   └── app.blade.php               ← Main layout (sidebar + header + footer)
└── components/
    ├── card.blade.php               ← <x-core-package::card>
    ├── page-header.blade.php        ← <x-core-package::page-header>
    ├── badge.blade.php              ← <x-core-package::badge>
    ├── alert.blade.php              ← <x-core-package::alert>
    ├── btn.blade.php                ← <x-core-package::btn>
    ├── stats-card.blade.php         ← <x-core-package::stats-card>
    └── form/
        ├── input.blade.php          ← <x-core-package::form.input>
        ├── select.blade.php         ← <x-core-package::form.select>
        ├── textarea.blade.php       ← <x-core-package::form.textarea>
        └── section.blade.php        ← <x-core-package::form.section>
```

Components are registered in `CorePackageServiceProvider::boot()` via:
```php
$this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
    $blade->anonymousComponentPath(
        __DIR__.'/../Resources/views/components',
        'core-package'
    );
});
```

---

## 12. Rules

1. **Never** write a `<style>` block inside a module view. Use inline `style=` or Tailwind.
2. **Always** put pagination inside the card, after the table, inside a `border-top` div.
3. **Always** validate errors both inline (via form components) and with a summary alert at top.
4. **Always** use `<x-core-package::btn>` for buttons — never raw `<button>` with inline styles in views.
5. **Always** show an empty state row when a table has no data (icon + message).
6. **Use `enctype="multipart/form-data"`** on forms that have a `profile_photo` or any file field.
7. New modules **extend** the same layout and **use** the same components — no copy-paste of design styles.
