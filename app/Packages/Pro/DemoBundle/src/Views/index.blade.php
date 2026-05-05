<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8">
            <h1 class="text-4xl font-bold text-blue-900 mb-4">{{ $title }}</h1>
            <p class="text-xl text-blue-700 mb-6">{{ $message }}</p>

            <div class="space-y-4">
                <p class="text-gray-700">
                    This is a sample premium bundle that demonstrates how the bundle installation system works.
                </p>

                <div class="bg-white rounded border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Bundle Structure</h3>
                    <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto"><code>Pro/DemoBundle/
├── manifest.json
├── src/
│   ├── Providers/
│   │   └── DemoBundleServiceProvider.php
│   ├── Controllers/
│   │   └── DemoController.php
│   ├── Routes/
│   │   └── web.php
│   ├── Views/
│   │   ├── index.blade.php
│   │   └── features.blade.php
│   ├── Models/
│   ├── Database/
│   │   └── migrations/
│   └── Assets/</code></pre>
                </div>

                <div class="mt-6">
                    <a href="{{ route('demo.features') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        View Features →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
