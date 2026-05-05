<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Library Management Setup</h1>
                <p class="text-gray-600">Configure and initialize the library management system</p>
            </div>

            <!-- Status Cards -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4 border-l-4 {{ $migrations ? 'border-green-500' : 'border-yellow-500' }}">
                    <h3 class="text-sm font-semibold text-gray-700">Migrations</h3>
                    <p class="text-2xl font-bold {{ $migrations ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $migrations ? '✓ Done' : '⚠ Pending' }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 {{ $permissions ? 'border-green-500' : 'border-yellow-500' }}">
                    <h3 class="text-sm font-semibold text-gray-700">Permissions</h3>
                    <p class="text-2xl font-bold {{ $permissions ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $permissions ? '✓ Done' : '⚠ Pending' }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 {{ $roles ? 'border-green-500' : 'border-yellow-500' }}">
                    <h3 class="text-sm font-semibold text-gray-700">Roles</h3>
                    <p class="text-2xl font-bold {{ $roles ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $roles ? '✓ Done' : '⚠ Pending' }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 {{ $categories ? 'border-green-500' : 'border-yellow-500' }}">
                    <h3 class="text-sm font-semibold text-gray-700">Data</h3>
                    <p class="text-2xl font-bold {{ $categories ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $categories ? '✓ Done' : '⚠ Pending' }}
                    </p>
                </div>
            </div>

            <!-- Setup Buttons -->
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Setup Options</h2>

                <div class="space-y-3 mb-6">
                    <button onclick="runSetup('migrate')" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        Run Migrations
                    </button>
                    <button onclick="runSetup('permissions')" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                        Setup Permissions
                    </button>
                    <button onclick="runSetup('roles')" class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                        Setup Roles
                    </button>
                    <button onclick="runSetup('seed-data')" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        Seed Initial Data
                    </button>
                    <button onclick="runSetup('all')" class="w-full px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg font-medium transition font-bold text-lg">
                        ⚡ Run All Setup
                    </button>
                </div>

                <!-- Status Messages -->
                <div id="status-container" class="mt-6"></div>

                <!-- Progress -->
                <div id="progress-container" class="mt-6"></div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Setup Process</h3>
                <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                    <li>Click "Run All Setup" to complete entire setup in one go</li>
                    <li>Or run individual steps in order: Migrations → Permissions → Roles → Data</li>
                    <li>After setup, all green checks ✓ should appear</li>
                    <li>You can then access the library at: <code class="bg-white px-2 py-1 rounded">/library/books</code></li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        async function runSetup(command) {
            const container = document.getElementById('status-container');
            const progressContainer = document.getElementById('progress-container');

            // Clear previous messages
            container.innerHTML = '';
            progressContainer.innerHTML = '<div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-blue-600 h-2 rounded-full" style="width: 10%"></div></div>';

            try {
                const response = await fetch('{{ route("library-management.setup.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ command })
                });

                const data = await response.json();

                if (data.success) {
                    if (data.steps) {
                        // Multi-step response
                        let html = '<div class="space-y-2">';
                        data.steps.forEach((step, index) => {
                            html += `<div class="p-3 rounded ${step.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}">
                                <p class="text-sm font-medium ${step.success ? 'text-green-800' : 'text-red-800'}">
                                    ${step.success ? '✓' : '✗'} ${step.message}
                                </p>
                            </div>`;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        // Single step response
                        container.innerHTML = `<div class="p-4 rounded bg-green-50 border border-green-200">
                            <p class="text-green-800 font-medium">✓ ${data.message}</p>
                        </div>`;
                    }
                    progressContainer.innerHTML = '<div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div></div>';

                    // Reload page after 2 seconds
                    setTimeout(() => location.reload(), 2000);
                } else {
                    container.innerHTML = `<div class="p-4 rounded bg-red-50 border border-red-200">
                        <p class="text-red-800 font-medium">✗ ${data.message}</p>
                    </div>`;
                    progressContainer.innerHTML = '<div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-red-600 h-2 rounded-full" style="width: 100%"></div></div>';
                }
            } catch (error) {
                container.innerHTML = `<div class="p-4 rounded bg-red-50 border border-red-200">
                    <p class="text-red-800 font-medium">✗ Error: ${error.message}</p>
                </div>`;
            }
        }
    </script>
</body>
</html>
