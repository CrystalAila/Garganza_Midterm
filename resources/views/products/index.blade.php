<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Management') }}
        </h2>
    </x-slot>

    
    <div x-data="{
        openEditModal: false,
        // *** UPDATED: Added delete modal state ***
        openDeleteModal: false,
        deletingProduct: { id: null, name: '', route: '' },

        editingProduct: { id: null, name: '', price: '', sku: '', stock_quantity: 0, category_id: null, route: '' },
        
        // Helper function to format price for display (optional)
        formatPrice(value) {
            return parseFloat(value).toFixed(2);
        },

        // Function to set data and open the Edit modal
        editProduct(product) {
            this.editingProduct.id = product.id;
            this.editingProduct.name = product.name;
            this.editingProduct.price = product.price;
            this.editingProduct.category_id = product.category_id;
            // *** UPDATED: Bind new fields from product object ***
            this.editingProduct.sku = product.sku;
            this.editingProduct.stock_quantity = product.stock_quantity; 

            // Dynamically construct the PUT route (e.g., /products/1)
            this.editingProduct.route = '{{ url('products') }}/' + product.id;
            this.openEditModal = true;
        },

        // NEW: Function to set data and open the Delete modal
        confirmDelete(product) {
            this.deletingProduct.id = product.id;
            this.deletingProduct.name = product.name;
            // Dynamically construct the DELETE route (e.g., /products/1)
            this.deletingProduct.route = '{{ url('products') }}/' + product.id;
            this.openDeleteModal = true;
        }
    }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Notification -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="p-4 mb-4 text-sm text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-800 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Create Product Form -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100 border-b pb-3">Add New Product</h3>
                
                <!-- *** UPDATED: Form now uses 5 columns for better layout of 5 fields (Name, SKU, Price, Stock, Category) *** -->
                <form method="POST" action="{{ route('products.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    @csrf
                    
                    <div class="col-span-1">
                        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Product Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="e.g., Laptop Pro X">
                    </div>
                    
                    <!-- New SKU Field -->
                    <div class="col-span-1">
                        <label for="sku" class="block font-medium text-sm text-gray-700 dark:text-gray-300">SKU</label>
                        <input id="sku" name="sku" type="text" value="{{ old('sku') }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="LPX-001">
                    </div>

                    <div class="col-span-1">
                        <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Price (₱)</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="199.99">
                    </div>
                    
                    <!-- New Stock Quantity Field -->
                    <div class="col-span-1">
                        <label for="stock_quantity" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Stock</label>
                        <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', 0) }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="100">
                    </div>

                    <div class="col-span-1">
                        <label for="category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Category</label>
                        <select id="category_id" name="category_id" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="" disabled selected>Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-5 col-span-1 flex justify-end">
                        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>


            <!-- Product List Section -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 border-b pb-3">Products</h3>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product Name</th>
                                <!-- *** UPDATED: Added SKU Header *** -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <!-- *** UPDATED: Added Stock Header *** -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </td>
                                    <!-- *** UPDATED: Display SKU *** -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product->sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($product->price, 2) }}
                                    </td>
                                    <!-- *** UPDATED: Display Stock Quantity *** -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product->stock_quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <!-- EDIT BUTTON: Opens the modal and sets the product data -->
                                        <button 
                                            @click="editProduct({ 
                                                id: {{ $product->id }}, 
                                                name: '{{ $product->name }}', 
                                                price: '{{ $product->price }}', 
                                                sku: '{{ $product->sku }}', // Pass SKU
                                                stock_quantity: {{ $product->stock_quantity ?? 0 }}, // Pass Stock
                                                category_id: {{ $product->category_id ?? 'null' }} 
                                            })" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150"
                                            title="Edit Product: {{ $product->name }}"
                                        >
                                            Edit
                                        </button>

                                        <!-- DELETE BUTTON: Opens the confirmation modal instead of using window.confirm() -->
                                        <button 
                                            @click.prevent="confirmDelete({ 
                                                id: {{ $product->id }}, 
                                                name: '{{ $product->name }}',
                                            })"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition duration-150"
                                            title="Delete Product: {{ $product->name }}"
                                        >
                                            Delete
                                        </button>
                                        <!-- Original form removed/commented out: 
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete the product: {{ $product->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition duration-150">Delete</button>
                                        </form>
                                        -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No products defined yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal (Hidden by Default) -->
        <div x-show="openEditModal" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="openEditModal = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                </div>

                <!-- Modal Content -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Edit Product: <span x-text="editingProduct.name"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Update the details for the product below.
                                    </p>
                                </div>

                                <!-- The Update Form -->
                                <form method="POST" :action="editingProduct.route" class="mt-4 space-y-4">
                                    @csrf
                                    @method('PATCH') 
                                    
                                    <div>
                                        <label for="edit_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Product Name</label>
                                        <input id="edit_name" name="name" type="text" x-model="editingProduct.name" required
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    </div>
                                    
                                    <!-- New SKU Field in Modal -->
                                    <div>
                                        <label for="edit_sku" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                                        <input id="edit_sku" name="sku" type="text" x-model="editingProduct.sku" required
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    </div>

                                    <div>
                                        <label for="edit_price" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Price ($)</label>
                                        <input id="edit_price" name="price" type="number" step="0.01" min="0" x-model="editingProduct.price" required
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    </div>
                                    
                                    <!-- New Stock Quantity Field in Modal -->
                                    <div>
                                        <label for="edit_stock_quantity" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Stock Quantity</label>
                                        <input id="edit_stock_quantity" name="stock_quantity" type="number" min="0" x-model="editingProduct.stock_quantity" required
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    </div>

                                    <div>
                                        <label for="edit_category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                        <select id="edit_category_id" name="category_id" x-model="editingProduct.category_id" required
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="" disabled>Select a Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Save Changes
                                        </button>
                                        <button type="button" @click="openEditModal = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Edit Product Modal -->

        <!-- NEW: Delete Product Confirmation Modal (Hidden by Default) -->
        <div x-show="openDeleteModal" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-modal-title" role="dialog" aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="openDeleteModal = false"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                </div>

                <!-- Modal Content -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                    
                    <form method="POST" :action="deletingProduct.route">
                        @csrf
                        @method('DELETE')

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <!-- Exclamation Icon -->
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-.7-2.03-.7-2.8 0L4.464 16c-.77.7-1.732 2.333 0 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="delete-modal-title">
                                        Confirm Deletion
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Are you sure you want to delete the product: 
                                            <strong x-text="deletingProduct.name" class="font-semibold text-gray-900 dark:text-white"></strong>? This action cannot be undone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete Product
                            </button>
                            <button type="button" @click="openDeleteModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


    </div>
</x-app-layout>