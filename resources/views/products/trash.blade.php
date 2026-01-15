<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products Trash') }}
            </h2>

            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-sm">
                Back to Products
            </a>
        </div>
    </x-slot>

    <div x-data="{
        openRestoreModal: false,
        openPermanentDeleteModal: false,
        selectedProduct: { id: null, name: '', route: '' },

        confirmRestore(product) {
            this.selectedProduct = product;
            // Matches Route: products/{id}/restore
            this.selectedProduct.route = `{{ url('products') }}/${product.id}/restore`;
            this.openRestoreModal = true;
        },

        confirmPermanentDelete(product) {
            this.selectedProduct = product;
            // Matches Route: products/{id}/force-delete
            this.selectedProduct.route = `{{ url('products') }}/${product.id}/force-delete`;
            this.openPermanentDeleteModal = true;
        }
    }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="p-4 mb-4 text-sm text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                {{-- Search & Filter Form --}}
                <form method="GET" action="{{ route('products.trash') }}" class="flex flex-col md:flex-row md:items-end gap-4 mb-6">
                    <div class="flex-1">
                        <label for="q" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search Trash</label>
                        <input id="q" name="q" value="{{ request('q') }}" type="search" placeholder="Search by name or SKU"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500" />
                    </div>

                    <div class="w-full md:w-48">
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition">Filter</button>
                        <a href="{{ route('products.trash') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Clear</a>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deleted Date</th>
                                <th class="relative px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($product->photo_path)
                                                <img src="{{ $product->photo_url }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mr-3 text-xs font-bold text-indigo-700 dark:text-indigo-300">
                                                    {{ $product->initials }}
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">₱{{ number_format($product->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                                        {{ $product->deleted_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <button @click="confirmRestore({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}' })" 
                                            class="text-green-600 hover:text-green-900 dark:hover:text-green-400">Restore</button>
                                        
                                        <button @click="confirmPermanentDelete({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}' })" 
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Perm. Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <p>Trash is empty.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        {{-- Restore Modal --}}
        <div x-show="openRestoreModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="openRestoreModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 relative z-10">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Restore Product</h3>
                    <p class="mt-2 text-sm text-gray-500">Are you sure you want to restore <strong x-text="selectedProduct.name"></strong>?</p>
                    <form method="POST" :action="selectedProduct.route" class="mt-4 flex justify-end space-x-3">
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
                    <p class="mt-2 text-sm text-gray-500">Warning: Deleting <strong x-text="selectedProduct.name"></strong> will be permanent and cannot be undone.</p>
                    <form method="POST" :action="selectedProduct.route" class="mt-4 flex justify-end space-x-3">
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