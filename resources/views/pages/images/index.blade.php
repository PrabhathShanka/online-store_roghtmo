@extends('layouts.app')

@section('styles')
    <style>
        h1 {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .image-item {
            width: 370px;
            height: 370px;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }

        /* Image styling */
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        /* Zoom effect */
        .image-item img.zoomed {
            transform: scale(1.5);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .delete-btn {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #e60000;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-link {
            color: #000000;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: #098e1d;
            color: white;
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

    {{-- Session message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <h1>Product Images</h1>

    <!-- Add Image Button -->

    <div class="text-center mb-3">
        <a href="{{ route('products.images.upload', $id) }}" class="btn btn-success">Add Image</a>
    </div>

    <div class="image-gallery">
        @foreach ($images as $image)
            <div class="image-item">
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image">

                <!-- Delete Button -->
                <form action="{{ route('products.images.destroy', ['id' => $id, 'imageId' => $image->id]) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn"
                        onclick="return confirm('Are you sure you want to delete this image?')">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="pagination">
        {{ $images->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.image-item img');
            images.forEach(image => {
                image.addEventListener('click', function() {
                    this.classList.toggle('zoomed');
                });
            });
        });
    </script>

    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('hide');
            }
        }, 2000); // milliseconds
    </script>
@endsection
