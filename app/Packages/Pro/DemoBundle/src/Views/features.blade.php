<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Bundle Features</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($features as $title => $description)
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-gray-600">{{ $description }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 bg-green-50 border border-green-200 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-green-900 mb-4">Next Steps</h2>
            <ol class="list-decimal list-inside space-y-2 text-green-800">
                <li>Copy this bundle structure as a template</li>
                <li>Rename <code class="bg-white px-2 py-1 rounded">DemoBundle</code> to your bundle name</li>
                <li>Update the <code class="bg-white px-2 py-1 rounded">manifest.json</code> with your details</li>
                <li>Implement your views, controllers, and models</li>
                <li>Create a ZIP file of your bundle</li>
                <li>Upload it through the Bundle Installer UI</li>
            </ol>
        </div>

        <div class="mt-8">
            <a href="{{ route('demo.index') }}" class="inline-block text-blue-600 hover:text-blue-800">
                ← Back to Demo
            </a>
        </div>
    </div>
</div>
