<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Edit Item</h1>

        <form method="POST" action="{{ route('a-i-based-reporting.update', $id) }}" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded"></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
                <a href="{{ route('a-i-based-reporting.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>