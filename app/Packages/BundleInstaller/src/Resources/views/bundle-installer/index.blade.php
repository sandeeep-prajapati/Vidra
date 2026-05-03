@extends('core-package::layouts.app')

@section('breadcrumb')
    <nav style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;">
        <a href="/" style="color:#0ea5e9;">Home</a>
        <span style="color:#cbd5e1;">/</span>
        <span style="color:#475569;">Bundle Installer</span>
    </nav>
@endsection

@section('content')
    <div style="padding:1.5rem;max-width:64rem;margin:0 auto;">
        <x-core-package::page-header title="Bundle Installer" subtitle="Upload and install Laravel module bundles" />

        @if(session('success'))
            <x-core-package::alert type="success">{{ session('success') }}</x-core-package::alert>
        @endif

        @if(session('error'))
            <x-core-package::alert type="error">{{ session('error') }}</x-core-package::alert>
        @endif

        {{-- Upload Bundle --}}
        <x-core-package::card title="Upload Bundle" class="mb-6">
            <form action="/bundle-installer/upload" method="POST" enctype="multipart/form-data" style="display:flex;gap:.5rem;align-items:center;">
                @csrf
                <input type="file" name="bundle" accept=".zip" required style="flex:1;padding:.5rem;border:1px solid #cbd5e1;border-radius:.375rem;" />
                <button type="submit" style="background:#4f46e5;color:#fff;padding:.5rem 1rem;border:none;border-radius:.375rem;cursor:pointer;font-weight:600;white-space:nowrap;">Upload</button>
            </form>
        </x-core-package::card>

        {{-- Bundles --}}
        <x-core-package::card title="Bundles" class="mb-6">
            @if(empty($installedBundles))
                <p style="color:#64748b;margin:0;">No bundles extracted yet. Upload a bundle first.</p>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <th style="padding:.75rem;text-align:left;font-weight:600;color:#1e293b;font-size:.875rem;">Name</th>
                                <th style="padding:.75rem;text-align:left;font-weight:600;color:#1e293b;font-size:.875rem;">Version</th>
                                <th style="padding:.75rem;text-align:left;font-weight:600;color:#1e293b;font-size:.875rem;">Author</th>
                                <th style="padding:.75rem;text-align:left;font-weight:600;color:#1e293b;font-size:.875rem;">Description</th>
                                <th style="padding:.75rem;text-align:left;font-weight:600;color:#1e293b;font-size:.875rem;">Status</th>
                                <th style="padding:.75rem;text-align:right;font-weight:600;color:#1e293b;font-size:.875rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($installedBundles as $bundle)
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:.75rem;color:#1e293b;font-weight:500;">{{ $bundle['name'] }}</td>
                                    <td style="padding:.75rem;color:#64748b;">{{ $bundle['version'] }}</td>
                                    <td style="padding:.75rem;color:#64748b;">{{ $bundle['author'] ?: '—' }}</td>
                                    <td style="padding:.75rem;color:#64748b;font-size:.875rem;">{{ $bundle['description'] ?: '—' }}</td>
                                    <td style="padding:.75rem;">
                                        <span style="background:#d4edda;color:#155724;padding:.25rem .75rem;border-radius:.25rem;font-size:.75rem;font-weight:600;">Extracted</span>
                                    </td>
                                    <td style="padding:.75rem;text-align:right;display:flex;gap:.5rem;justify-content:flex-end;">
                                        <!-- Install Button -->
                                        <form method="POST" action="/bundle-installer/install/{{ $bundle['package'] }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background:#10b981;color:#fff;padding:.375rem .75rem;border:none;border-radius:.375rem;font-size:.75rem;cursor:pointer;font-weight:600;">
                                                Install
                                            </button>
                                        </form>

                                        <!-- Remove Button -->
                                        <form method="POST" action="/bundle-installer/{{ $bundle['package'] }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Remove this bundle?')" style="background:#ef4444;color:#fff;padding:.375rem .75rem;border:none;border-radius:.375rem;font-size:.75rem;cursor:pointer;font-weight:600;">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-core-package::card>

        {{-- Bundle Format Info --}}
        <x-core-package::card title="Bundle Format" noPadding="true">
            <div style="padding:1.5rem;">
                <p style="color:#475569;margin:0 0 1rem;font-size:.875rem;">Your bundle ZIP file should have this structure:</p>
                <pre style="background:#1e293b;color:#e2e8f0;padding:1rem;border-radius:.375rem;overflow-x:auto;font-size:.75rem;line-height:1.5;margin:0;">my-bundle-v1.0.0.zip
├── manifest.json
└── src/
    ├── Providers/
    │   └── MyBundleServiceProvider.php
    ├── Controllers/
    ├── Models/
    ├── Database/
    │   └── migrations/
    ├── Routes/
    │   └── web.php
    └── Resources/
        └── views/

<strong>manifest.json</strong> must contain:
{
  "name": "MyBundle",
  "version": "1.0.0",
  "description": "...",
  "author": "...",
  "provider_class": "App\\Packages\\MyBundle\\Providers\\MyBundleServiceProvider",
  "package_path": "MyBundle"
}</pre>
            </div>
        </x-core-package::card>
    </div>

@endsection
