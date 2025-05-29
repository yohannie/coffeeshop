@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-brown">Product Management</h1>
                    <p class="text-gray-600 mt-1">Add, edit, and manage products</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-brown hover:text-brown-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <button type="button" onclick="openAddProductModal()" class="bg-brown text-white px-4 py-2 rounded-md hover:bg-brown-dark transition-colors">
                        Add New Product
                    </button>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image_full_url)
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $product->image_full_url }}" alt="{{ $product->name }}">
                                    </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₱{{ number_format($product->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->stock }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $product->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button onclick="openEditProductModal({{ $product->id }})" class="text-brown hover:text-brown-dark mr-3">Edit</button>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-brown">Add New Product</h3>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price</label>
                    <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">Stock</label>
                    <input type="number" name="stock" id="stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category</label>
                    <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="Hot Coffee">Hot Coffee</option>
                        <option value="Cold Coffee">Cold Coffee</option>
                        <option value="Pastries">Pastries</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                    <div class="flex items-center justify-between space-x-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm mb-2" for="image">Upload Image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brown file:text-white hover:file:bg-brown-dark">
                        </div>
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">OR</span>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm mb-2" for="image_url">Image URL</label>
                            <input type="url" name="image_url" id="image_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm leading-tight focus:outline-none focus:shadow-outline" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeAddProductModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="bg-brown text-white px-4 py-2 rounded-md hover:bg-brown-dark transition-colors">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-brown">Edit Product</h3>
            <form id="editProductForm" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_name">Name</label>
                    <input type="text" name="name" id="edit_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_description">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_price">Price</label>
                    <input type="number" step="0.01" name="price" id="edit_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_stock">Stock</label>
                    <input type="number" name="stock" id="edit_stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_category">Category</label>
                    <select name="category" id="edit_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="Hot Coffee">Hot Coffee</option>
                        <option value="Cold Coffee">Cold Coffee</option>
                        <option value="Pastries">Pastries</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                    <div id="current_image" class="mb-2 hidden">
                        <img src="" alt="Current product image" class="w-20 h-20 object-cover rounded-lg">
                    </div>
                    <div class="flex items-center justify-between space-x-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm mb-2" for="edit_image">Upload New Image</label>
                            <input type="file" name="image" id="edit_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brown file:text-white hover:file:bg-brown-dark">
                        </div>
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">OR</span>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm mb-2" for="edit_image_url">Image URL</label>
                            <input type="url" name="image_url" id="edit_image_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm leading-tight focus:outline-none focus:shadow-outline" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditProductModal()" class="mr-2 px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="bg-brown text-white px-4 py-2 rounded-md hover:bg-brown-dark transition-colors">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddProductModal() {
        document.getElementById('addProductModal').classList.remove('hidden');
    }

    function closeAddProductModal() {
        document.getElementById('addProductModal').classList.add('hidden');
        document.querySelector('form').reset();
    }

    function openEditProductModal(productId) {
        fetch(`/admin/products/${productId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_price').value = data.price;
                document.getElementById('edit_stock').value = data.stock;
                document.getElementById('edit_category').value = data.category;
                document.getElementById('edit_image_url').value = data.image_url || '';
                
                const currentImageDiv = document.getElementById('current_image');
                const currentImage = currentImageDiv.querySelector('img');
                if (data.image_full_url) {
                    currentImage.src = data.image_full_url;
                    currentImageDiv.classList.remove('hidden');
                } else {
                    currentImageDiv.classList.add('hidden');
                }
                
                document.getElementById('editProductForm').action = `/admin/products/${productId}`;
                document.getElementById('editProductModal').classList.remove('hidden');
            });
    }

    function closeEditProductModal() {
        document.getElementById('editProductModal').classList.add('hidden');
        document.getElementById('editProductForm').reset();
    }
</script>
@endpush
@endsection