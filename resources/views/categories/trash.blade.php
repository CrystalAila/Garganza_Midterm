<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories Trash') }}
            </h2>

            <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-sm">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div x-data="{
        openRestoreModal: false,
        openPermanentDeleteModal: false,
        selectedCategory: { id: null, name: '', route: '' },

        confirmRestore(category) {
            this.selectedCategory = category;
            this.selectedCategory.route = `{{ url('categories') }}/${category.id}/restore`;
            this.openRestoreModal = true;
        },

        confirmPermanentDelete(category) {
            this.selectedCategory = category;
            this.selectedCategory.route = `{{ url('categories') }}/${category.id}/force-delete`;
            this.openPermanentDeleteModal = true;
        }
    }" class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="p-4 mb-4 text-sm text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category Name</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Products Count</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deleted Date</th>
                                <th class="relative px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ $category->products_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-500">
                                        {{ $category->deleted_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <button @click="confirmRestore({ id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })" 
                                            class="text-green-600 hover:text-green-900 dark:hover:text-green-400">Restore</button>
                                        
                                        <button @click="confirmPermanentDelete({ id: {{ $category->id }}, name: '{{ addslashes($category->name) }}' })" 
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Perm. Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        Trash is empty.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        {{-- Restore Confirmation Modal --}}
        <div x-show="openRestoreModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="openRestoreModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 relative z-10">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Restore Category</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to restore <strong x-text="selectedCategory.name"></strong>?</p>
                    <form method="POST" :action="selectedCategory.route" class="mt-4 flex justify-end space-x-3">
                        @csrf
                        <button type="button" @click="openRestoreModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Confirm Restore</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Permanent Delete Modal --}}
        <div x-show="openPermanentDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="openPermanentDeleteModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 relative z-10">
                    <h3 class="text-lg font-medium text-red-600">Permanent Deletion</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Warning: Deleting <strong x-text="selectedCategory.name"></strong> is permanent. Any products currently linked to this will lose their category connection.</p>
                    <form method="POST" :action="selectedCategory.route" class="mt-4 flex justify-end space-x-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="openPermanentDeleteModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>