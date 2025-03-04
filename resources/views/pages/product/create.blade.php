@extends('layouts.app')

@section('styles')
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #52dd98;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-preview {
            margin-top: 5px;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        .remove-image-btn {
            margin-top: 10px;
            display: none;
            color: red;
            cursor: pointer;
        }
    </style>
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
        <h1 class="text-center mb-4">Add Product</h1>

        <!-- Form to create a new product -->
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group mb-3">
                <label for="stock">Stock Quantity:</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Category:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="mainImage">Cover Image:</label>
                <input type="file" class="form-control" id="mainImage" name="mainImage" accept="image/*">
                <img id="imagePreview" class="image-preview" style="display:none;">
                <span id="removeImage" class="remove-image-btn">Remove Image</span>
            </div>

            <!-- Multiple Image Upload Section -->
            <div class="form-group mb-3">
                <label for="additionalImages">Add Additional Images (up to 5):</label>
                <input type="file" class="form-control" id="additionalImages" name="additionalImages[]" accept="image/*"
                    multiple>
                <div id="additionalImagePreviews" class="d-flex flex-wrap gap-3 mt-2"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Product</button>
        </form>
    </div>

    <script>
        function handleImagePreview(inputId, previewId, removeButtonId) {
            document.getElementById(inputId).addEventListener('change', function(event) {
                const preview = document.getElementById(previewId);
                const removeButton = document.getElementById(removeButtonId);
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        removeButton.style.display = 'inline';
                    };
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById(removeButtonId).addEventListener('click', function() {
                const fileInput = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                fileInput.value = ''; // Clear the file input
                preview.style.display = 'none'; // Hide the preview
                this.style.display = 'none'; // Hide the remove button
            });
        }

        // Initialize for the main image
        handleImagePreview('mainImage', 'imagePreview', 'removeImage');

        // Multiple Images Preview
        document.getElementById('additionalImages').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('additionalImagePreviews');
            previewContainer.innerHTML = ''; // Clear existing previews
            const files = Array.from(event.target.files);

            if (files.length > 5) {
                alert("You can only upload up to 5 images.");
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
                removeButton.style.position = 'absolute';
                removeButton.style.top = '5px';
                removeButton.style.right = '5px';
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
@endsection
