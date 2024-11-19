@extends('layouts.app1')

@section('styles')
    <link rel="stylesheet" href="{{ asset('CSS/product/create.css') }}">
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

    <script src="{{ asset('JS/product/create.js') }}"></script>
@endsection
