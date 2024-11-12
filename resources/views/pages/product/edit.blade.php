@extends('layouts.app')


@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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
            /* Set desired width */
            height: 150px;
            /* Set desired height */
            object-fit: cover;
            {{--  /* Crops image to fit within the area */
            border: 2px solid #52dd98;  --}}
            /* Optional border */
            border-radius: 8px;
            /* Optional rounded corners */
        }

        .remove-image-btn {
            margin-top: 10px;
            display: none;
            color: red;
            cursor: pointer;
        }

        .additional-image-preview {
            margin-top: 5px;
            width: 140px;
            height: 140px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
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

        document.getElementById('mainImage').addEventListener('change', function(event) {
            const preview = document.getElementById('imagePreview');
            const removeButton = document.getElementById('removeImage');
            const currentImage = document.querySelector('.img-thumbnail');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    removeButton.style.display = 'inline';

                    // Hide the current image preview if a new image is selected
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('removeImage').addEventListener('click', function() {
            const fileInput = document.getElementById('mainImage');
            const preview = document.getElementById('imagePreview');
            const currentImage = document.querySelector('.img-thumbnail');

            fileInput.value = ''; // Clear the file input
            preview.style.display = 'none'; // Hide the preview
            this.style.display = 'none'; // Hide the remove button

            // Show the original image again if it exists
            if (currentImage) {
                currentImage.style.display = 'block';
            }
        });

        // Image Deletion
        // Function to handle image deletion with SweetAlert confirmation
        function confirmDelete(imageId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                reverseButtons: true,
                preConfirm: () => {
                    // Send AJAX request to delete the image
                    return fetch(`/delete-image/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                imageId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Deleted!", "Your imaginary file has been deleted.", "success")
                                    .then(() => {
                                        // Reload the page after successful deletion
                                        location.reload();
                                    });
                            } else {
                                Swal.fire("Failed", "Failed to delete the image.", "error");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire("Error", "An error occurred while deleting the image.", "error");
                        });
                }
            });
        }
    </script>



@endsection
