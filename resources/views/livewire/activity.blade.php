<div class="flex h-screen bg-gray-100">
    @include('components.sidebar', ['active' => 'activity'])

    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm">
            <div class="px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Activity Management</h2>
            </div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Activity Data</h3>
                <p class="text-gray-600">Activity management content here...</p>
            </div>
        </div>
    </main>
</div>
