<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School Management App — Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .step-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0; }
        .step-pending  { background: #e5e7eb; color: #9ca3af; }
        .step-active   { background: #4f46e5; color: #fff; }
        .step-complete { background: #10b981; color: #fff; }
        .fade { animation: fadeIn .3s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

<div class="flex gap-8 w-full max-w-5xl">

    {{-- Left sidebar: step list --}}
    <aside class="hidden md:flex flex-col justify-center min-w-[220px]">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-indigo-700">🏫 SchoolMS</h1>
            <p class="text-sm text-gray-500 mt-1">Installation Wizard</p>
        </div>

        <ol id="step-list" class="space-y-4">
            @php
                $steps = [
                    'welcome'        => 'Welcome',
                    'requirements'   => 'Server Requirements',
                    'database'       => 'Database Setup',
                    'install'        => 'Install',
                    'admin'          => 'Admin Account',
                    'done'           => 'Finish',
                ];
            @endphp
            @foreach ($steps as $key => $label)
            <li class="flex items-center gap-3" data-step-item="{{ $key }}">
                <span class="step-icon step-pending" id="icon-{{ $key }}">
                    {{ $loop->index + 1 }}
                </span>
                <span class="text-sm text-gray-600" id="label-{{ $key }}">{{ $label }}</span>
            </li>
            @endforeach
        </ol>
    </aside>

    {{-- Main panel --}}
    <main class="flex-1">

        {{-- STEP: Welcome --}}
        <div id="step-welcome" class="step-panel bg-white rounded-2xl shadow p-8 fade">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Welcome to School Management App</h2>
            <p class="text-gray-500 text-sm mb-6">
                This wizard will guide you through the installation. Make sure your server meets the requirements before proceeding.
            </p>
            <ul class="text-sm text-gray-600 space-y-2 mb-8">
                <li>✅ PHP {{ $phpVersion['minimum'] }}+</li>
                <li>✅ MySQL / PostgreSQL database</li>
                <li>✅ Composer dependencies installed</li>
                <li>✅ Write permissions on <code class="bg-gray-100 px-1 rounded">storage/</code> and <code class="bg-gray-100 px-1 rounded">.env</code></li>
            </ul>
            <div class="flex justify-end">
                <button onclick="goTo('requirements')" class="btn-primary">Get Started →</button>
            </div>
        </div>

        {{-- STEP: Requirements --}}
        <div id="step-requirements" class="step-panel hidden bg-white rounded-2xl shadow p-8 fade">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Server Requirements</h2>
            <p class="text-sm text-gray-500 mb-5">Ensure all requirements are met before continuing.</p>

            {{-- PHP version --}}
            <div class="flex items-center gap-2 mb-3">
                @if($phpVersion['supported'])
                    <span class="text-green-500 text-lg">✔</span>
                @else
                    <span class="text-red-500 text-lg">✘</span>
                @endif
                <span class="text-sm font-semibold text-gray-700">
                    PHP {{ $phpVersion['current'] }}
                    <span class="font-normal text-gray-400">(minimum {{ $phpVersion['minimum'] }})</span>
                </span>
            </div>

            {{-- Extensions --}}
            @foreach ($requirements['requirements']['php'] as $ext => $ok)
            <div class="flex items-center gap-2 py-1 border-b border-gray-50">
                @if($ok)
                    <span class="text-green-500">✔</span>
                @else
                    <span class="text-red-500">✘</span>
                @endif
                <span class="text-sm text-gray-600">{{ $ext }}</span>
            </div>
            @endforeach

            @php $hasErrors = isset($requirements['errors']) && $requirements['errors']; @endphp

            @if($hasErrors)
            <p class="mt-4 text-sm text-red-600 bg-red-50 rounded p-3">
                Some requirements are not met. Please enable the missing PHP extensions and refresh.
            </p>
            @endif

            <div class="flex justify-between mt-6">
                <button onclick="goTo('welcome')" class="btn-secondary">← Back</button>
                <button onclick="goTo('database')" class="btn-primary" {{ $hasErrors ? 'disabled' : '' }}>Continue →</button>
            </div>
        </div>

        {{-- STEP: Database --}}
        <div id="step-database" class="step-panel hidden bg-white rounded-2xl shadow p-8 fade">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Database Configuration</h2>
            <p class="text-sm text-gray-500 mb-5">Enter your database connection details.</p>

            <div id="db-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 rounded p-3"></div>

            <div class="space-y-4">
                <div>
                    <label class="form-label">Connection</label>
                    <select id="db_connection" class="form-input">
                        <option value="mysql">MySQL</option>
                        <option value="pgsql">PostgreSQL</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Host</label>
                        <input id="db_hostname" type="text" class="form-input" value="127.0.0.1">
                    </div>
                    <div>
                        <label class="form-label">Port</label>
                        <input id="db_port" type="text" class="form-input" value="3306">
                    </div>
                </div>
                <div>
                    <label class="form-label">Database Name</label>
                    <input id="db_name" type="text" class="form-input" placeholder="school_db">
                </div>
                <div>
                    <label class="form-label">Username</label>
                    <input id="db_username" type="text" class="form-input" placeholder="root">
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input id="db_password" type="password" class="form-input" placeholder="(leave blank if none)">
                </div>
                <div>
                    <label class="form-label">Table Prefix <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input id="db_prefix" type="text" class="form-input" placeholder="sms_">
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button onclick="goTo('requirements')" class="btn-secondary">← Back</button>
                <button onclick="submitDatabase()" id="btn-db-continue" class="btn-primary">Continue →</button>
            </div>
        </div>

        {{-- STEP: Install --}}
        <div id="step-install" class="step-panel hidden bg-white rounded-2xl shadow p-8 fade">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Installing</h2>
            <p class="text-sm text-gray-500 mb-6">Running migrations and seeding default data. This may take a moment…</p>

            <div class="space-y-3" id="install-progress">
                <div class="flex items-center gap-3" id="prog-migrate">
                    <span class="spinner text-indigo-500" id="spin-migrate">⏳</span>
                    <span class="text-sm text-gray-600">Running database migrations…</span>
                </div>
                <div class="flex items-center gap-3 opacity-40" id="prog-seed">
                    <span id="spin-seed">⏳</span>
                    <span class="text-sm text-gray-600">Seeding default data…</span>
                </div>
            </div>

            <div id="install-error" class="hidden mt-4 text-sm text-red-600 bg-red-50 rounded p-3"></div>
        </div>

        {{-- STEP: Admin --}}
        <div id="step-admin" class="step-panel hidden bg-white rounded-2xl shadow p-8 fade">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Create Administrator Account</h2>
            <p class="text-sm text-gray-500 mb-5">This account will have full access to the system.</p>

            <div id="admin-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 rounded p-3"></div>

            <div class="space-y-4">
                <div>
                    <label class="form-label">Full Name</label>
                    <input id="admin_name" type="text" class="form-input" value="Admin" placeholder="Super Admin">
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <input id="admin_email" type="email" class="form-input" value="admin@school.com">
                </div>
                <div>
                    <label class="form-label">Password <span class="text-gray-400 font-normal">(min 6 characters)</span></label>
                    <input id="admin_password" type="password" class="form-input">
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input id="admin_password_confirm" type="password" class="form-input">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="submitAdmin()" id="btn-admin" class="btn-primary">Finish Installation →</button>
            </div>
        </div>

        {{-- STEP: Done --}}
        <div id="step-done" class="step-panel hidden bg-white rounded-2xl shadow p-8 text-center fade">
            <div class="text-5xl mb-4">🎉</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Installation Complete!</h2>
            <p class="text-gray-500 text-sm mb-8">
                School Management App is ready. Log in with the admin account you just created.
            </p>
            <a href="{{ url('/login') }}" class="btn-primary inline-block">Go to Login →</a>
        </div>

    </main>
</div>

<style>
    .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .form-input  { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; outline: none; transition: border-color .2s; }
    .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
    .btn-primary  { background: #4f46e5; color: #fff; padding: 9px 22px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; border: none; transition: opacity .2s; }
    .btn-primary:hover  { opacity: .88; }
    .btn-primary:disabled { opacity: .5; cursor: not-allowed; }
    .btn-secondary { background: #f3f4f6; color: #374151; padding: 9px 22px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; border: none; }
    .btn-secondary:hover { background: #e5e7eb; }
</style>

<script>
    const STEPS = ['welcome', 'requirements', 'database', 'install', 'admin', 'done'];
    let current = 'welcome';

    function goTo(step) {
        document.getElementById('step-' + current).classList.add('hidden');
        document.getElementById('step-' + step).classList.remove('hidden');

        const oldIdx = STEPS.indexOf(current);
        const newIdx = STEPS.indexOf(step);

        STEPS.forEach((s, i) => {
            const icon = document.getElementById('icon-' + s);
            const label = document.getElementById('label-' + s);
            if (i < newIdx) {
                icon.className = 'step-icon step-complete';
                icon.textContent = '✓';
            } else if (i === newIdx) {
                icon.className = 'step-icon step-active';
                icon.textContent = i + 1;
                label.classList.add('font-semibold', 'text-indigo-700');
            } else {
                icon.className = 'step-icon step-pending';
                icon.textContent = i + 1;
                label.classList.remove('font-semibold', 'text-indigo-700');
            }
        });

        current = step;
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    async function post(url, data) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return { ok: res.ok, status: res.status, data: await res.json() };
    }

    async function submitDatabase() {
        const btn = document.getElementById('btn-db-continue');
        const errBox = document.getElementById('db-error');
        errBox.classList.add('hidden');
        btn.disabled = true;
        btn.textContent = 'Saving…';

        const payload = {
            db_connection: document.getElementById('db_connection').value,
            db_hostname:   document.getElementById('db_hostname').value,
            db_port:       document.getElementById('db_port').value,
            db_name:       document.getElementById('db_name').value,
            db_username:   document.getElementById('db_username').value,
            db_password:   document.getElementById('db_password').value,
            db_prefix:     document.getElementById('db_prefix').value,
        };

        const res = await post('{{ route("installer.env_file_setup") }}', payload);

        if (res.ok) {
            goTo('install');
            runInstallation();
        } else {
            errBox.textContent = res.data.error || 'Failed to save configuration.';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Continue →';
        }
    }

    async function runInstallation() {
        const errBox = document.getElementById('install-error');
        errBox.classList.add('hidden');

        // Run migration
        const migrate = await post('{{ route("installer.run_migration") }}', {});
        if (!migrate.ok) {
            document.getElementById('spin-migrate').textContent = '❌';
            errBox.textContent = migrate.data.error || 'Migration failed.';
            errBox.classList.remove('hidden');
            return;
        }
        document.getElementById('spin-migrate').textContent = '✅';

        // Run seeder
        document.getElementById('prog-seed').classList.remove('opacity-40');
        const seed = await post('{{ route("installer.run_seeder") }}', {});
        if (!seed.ok) {
            document.getElementById('spin-seed').textContent = '❌';
            errBox.textContent = seed.data.error || 'Seeding failed.';
            errBox.classList.remove('hidden');
            return;
        }
        document.getElementById('spin-seed').textContent = '✅';

        setTimeout(() => goTo('admin'), 800);
    }

    async function submitAdmin() {
        const btn = document.getElementById('btn-admin');
        const errBox = document.getElementById('admin-error');
        errBox.classList.add('hidden');

        const name     = document.getElementById('admin_name').value.trim();
        const email    = document.getElementById('admin_email').value.trim();
        const password = document.getElementById('admin_password').value;
        const confirm  = document.getElementById('admin_password_confirm').value;

        if (!name || !email || !password) {
            errBox.textContent = 'All fields are required.';
            errBox.classList.remove('hidden');
            return;
        }
        if (password !== confirm) {
            errBox.textContent = 'Passwords do not match.';
            errBox.classList.remove('hidden');
            return;
        }
        if (password.length < 6) {
            errBox.textContent = 'Password must be at least 6 characters.';
            errBox.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Creating…';

        const res = await post('{{ route("installer.admin_config_setup") }}', { name, email, password });

        if (res.ok) {
            goTo('done');
        } else {
            const msg = res.data.error || Object.values(res.data.errors || {})[0]?.[0] || 'Failed to create admin.';
            errBox.textContent = msg;
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Finish Installation →';
        }
    }

    // Init: highlight first step
    goTo('welcome');
</script>
</body>
</html>
