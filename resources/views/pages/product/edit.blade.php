@extends('layouts.app1')


@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('CSS/product/edit.css') }}">
@endsection

@section('content')
    {{-- Display error messages --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="position: relative; padding: 1rem;">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <span style="position: absolute; right: 10px; top: 10px;">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </span>
        </div>
    @endif


    <div class="container form-container">
        <h1 class="text-center mb-4">Edit Product</h1>

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01"
                    value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="stock">Stock Quantity:</label>
                <input type="number" class="form-control" id="stock" name="stock"
                    value="{{ old('stock', $product->stock) }}" required>
                @error('stock')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Category:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Display the current image if available --}}
            <div class="form-group mb-3">
                <label for="mainImage">Product Image:</label>
                <input type="file" class="form-control" id="mainImage" name="mainImage" accept="image/*">
                @if ($product->mainImage)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $product->mainImage) }}" alt="Product Image" class="img-thumbnail"
                            width="150">
                    </div>
                @endif
                <img id="imagePreview" class="image-preview" style="display:none;">
                <span id="removeImage" class="remove-image-btn">Remove Image</span>
            </div>

            <!-- Multiple Image Upload Section -->
            <div class="form-group mb-3">
                <label for="additionalImages">Add Additional Images (up to 5):</label>
                <input type="file" class="form-control" id="additionalImages" name="additionalImages[]" accept="image/*"
                    multiple>
                <div id="additionalImagePreviews1" class="d-flex flex-wrap gap-3 mt-2"></div>
            </div>

            <!-- Display existing images from the database -->
            <div class="form-group mb-3">
                <div id="additionalImagePreviews" class="d-flex flex-wrap gap-3 mt-2">
                    @foreach ($product->images as $image)
                        <div class="image-preview" id="image-{{ $image->id }}"
                            style="margin-bottom: 25px; margin-right: 15px;">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
                                class="img-thumbnail additional-image-preview" width="100" style="margin-right: 10px;">
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $image->id }})"
                                style="margin-left: 5px;">Delete</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <br>

            <button type="submit" class="btn btn-primary btn-block">Update Product</button>
        </form>
    </div>


    <script>
        const maxImages = 5; // Maximum images allowed (including the existing ones)
        const currentImages = {{ count($product->images) }}; // Count of existing images

        document.getElementById('additionalImages').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('additionalImagePreviews1');
            previewContainer.innerHTML = ''; // Clear previous previews

            const files = Array.from(e.target.files);

            // Check if the number of selected images exceeds the limit
            if (files.length + currentImages > maxImages) {
                alert("You can only upload a total of 5 images.");
                this.value = ''; // Clear the input
                return;
            }

            files.forEach((file, index) => {
                const reader = new FileReader();
                const previewWrapper = document.createElement('div');
                previewWrapper.style.position = 'relative';

                const previewImage = document.createElement('img');
                previewImage.classList.add('image-preview');
                previewWrapper.appendChild(previewImage);

                const removeButton = document.createElement('span');
                removeButton.textContent = 'Remove Image';
                removeButton.classList.add('remove-image-btn');
                previewWrapper.appendChild(removeButton);

                previewContainer.appendChild(previewWrapper);

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    removeButton.style.display = 'inline';
                };
                reader.readAsDataURL(file);

                // Event listener to remove image
                removeButton.addEventListener('click', function() {
                    previewWrapper.remove();
                    const fileList = Array.from(document.getElementById('additionalImages').files);
                    fileList.splice(index, 1);
                    const dataTransfer = new DataTransfer();
                    fileList.forEach(file => dataTransfer.items.add(file));
                    document.getElementById('additionalImages').files = dataTransfer.files;
                });
            });
        });
    </script>

    <script src="{{ asset('JS/product/edit.js') }}"></script>


@endsection
