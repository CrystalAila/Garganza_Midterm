<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <!-- Main Alpine Data for Modal State -->
    <div x-data="{
        openEditModal: false,
        openDeleteModal: false, // NEW: State for delete modal
        editingCategory: { id: null, name: '', route: '' },
        deletingCategory: { id: null, name: '', route: '' }, // NEW: State for category to delete
        
        // Function to set data and open the edit modal
        editCategory(category) {
            this.editingCategory.id = category.id;
            this.editingCategory.name = category.name;
            // Dynamically construct the PUT route (e.g., /categories/1)
            this.editingCategory.route = '{{ url('categories') }}/' + category.id;
            this.openEditModal = true;
        },

        // NEW: Function to set data and open the delete modal
        deleteCategory(category) {
            this.deletingCategory.id = category.id;
            this.deletingCategory.name = category.name;
            // Dynamically construct the DELETE route
            this.deletingCategory.route = '{{ url('categories') }}/' + category.id;
            this.openDeleteModal = true;
        }
    }" class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Create Category Form -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100 border-b pb-3">Create New Category</h3>
                
                <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-grow w-full">
                        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Category Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="e.g., Electronics, Apparel, Books">
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Add Category
                    </button>
                </form>
            </div>


            <!-- Category List Section -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">

                <!-- Categories Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category Name</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Products</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ $category->products_count ?? 'N/A' }} 
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">

                                        <button 
                                            @click="editCategory({{ json_encode($category) }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150"
                                            title="Edit Category: {{ $category->name }}"
                                        >
                                            Edit
                                        </button>


                                        <button 
                                            @click="deleteCategory({{ json_encode($category) }})" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition duration-150"
                                            title="Delete Category: {{ $category->name }}"
                                        >
                                            Delete
                                        </button>
                                        
                                        {{-- The form is removed from here and moved into the modal --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No categories defined yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Category Modal (Hidden by Default) -->
        <div x-show="openEditModal" x-cloak
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
                                    Edit Category: <span x-text="editingCategory.name"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Update the name for the category below.
                                    </p>
                                </div>

                                <!-- The Update Form -->
                                <form method="POST" :action="editingCategory.route" class="mt-4">
                                    @csrf
                                    <!-- Laravel requires @method('PUT') for updates -->
                                    @method('PATCH') 
                                    
                                    <label for="edit_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">New Category Name</label>
                                    <input id="edit_name" name="name" type="text" x-model="editingCategory.name" required
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        placeholder="Category Name">
                                    
                                    <!-- A hidden input to pass the original ID if needed, though the route handles it -->
                                    <input type="hidden" name="id" :value="editingCategory.id">

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
        <!-- End Edit Category Modal -->

        <!-- START DELETE CONFIRMATION MODAL -->
        <div x-show="openDeleteModal" x-cloak
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
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation-triangle -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.021 3.376 1.63 3.376h13.143c1.609 0 2.495-1.876 1.63-3.376L13.91 3.25c-.866-1.5-3.09-.75-3.956 0L2.697 16.126zM12 15.75h.007v.008H12z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="delete-modal-title">
                                    Delete Category: <span x-text="deletingCategory.name"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete the category: <strong x-text="deletingCategory.name"></strong>?
                                        This action is irreversible. Any products currently linked to this category will become Uncategorized.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- The Delete Form -->
                        <form method="POST" :action="deletingCategory.route" class="mt-4" id="delete-category-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" :value="deletingCategory.id">

                            <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Yes
                                </button>
                                <button type="button" @click="openDeleteModal = false"
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
</x-app-layout>