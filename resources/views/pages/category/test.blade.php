@extends('layouts.app')

@section('styles')
    <style>
        body {}



        /* Styling for screens 877px or smaller */
        @media screen and (max-width: 877px) {
            body {
                background-color: rgb(197, 197, 22) !important;
            }

            /* Adjust container widths for smaller screens */
            .container,
            .container-md,
            .container-sm {
                max-width: 100% !important;
                padding: 0 15px;
            }

            /* Table container with scroll on small screens */
            .table-container {
                max-width: 100% !important;
                margin: 20px auto;
                overflow-x: auto;
                /* Allows horizontal scroll if needed */
            }

            /* Pagination styling */
            .pagination-container {
                max-width: 100% !important;
                margin: 10px auto;
                text-align: center;
            }

            /* Product image adjustments */
            .product-image {
                width: 100px !important;
                height: 100px !important;
                background-size: cover;
                background-position: center;
            }

            /* Hide columns for smaller screens */
            .column-id,
            .column-stock,
            .column-category,
            .column-actions {
                display: none !important;
            }

            /* Table cell adjustments */
            .table.table-bordered.table-striped td,
            .table.table-bordered.table-striped th {
                text-align: center !important;
                font-size: 0.9rem !important;
                padding: 8px !important;
            }

            /* Adjust card layout on smaller screens */
            .card {
                width: 100% !important;
                margin: 10px 0;
            }

            .card-body {
                padding: 10px !important;
            }
        }


        /* Category Filter and Refresh Button styling */
        .category-filter {
            margin-bottom: 1rem;
        }

        .refresh-button {
            background-color: rgb(35, 123, 170);
            color: white;
            border: none;
        }

        .refresh-button:hover {
            background-color: rgb(85, 181, 115);
        }

        /* Table column width settings */
        .table {
            table-layout: fixed;
            width: 100%;
            text-align: center;
        }

        table tr {
            height: 50px;
            text-align: center;
            vertical-align: middle;

        }

        table td {
            height: 150px;
            text-align: center;
            vertical-align: middle;
            /* Ensure the row height is applied through the cells */
        }

        .column-id {
            width: 7%;

        }

        .column-image {
            width: 20%;
        }

        .column-name {
            width: 10%;

        }

        .column-description {
            width: 17%;
        }

        .column-price {
            width: 9%;
        }

        .column-stock {
            width: 10%;

        }

        .column-category {
            width: 14%;
        }

        .column-actions {
            width: 14%;
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 80%;
            max-height: 80%;
            overflow-y: auto;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #aaa;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }



        #imageCarousel .carousel-item img {
            width: 450px;
            height: 450px;
            object-fit: cover;
        }

        .zoom-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .zoomable-image {
            transition: transform 0.3s ease;
            /* Smooth zoom effect */
        }

        .carousel-inner {
            position: relative;
        }

        #zoomIn,
        #zoomOut {
            font-size: 16px;
            padding: 8px 16px;
        }
    </style>
@endsection


@section('content')
    <div class="container">

        {{-- Display error messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Session success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif



        <div class="table-container">
            <h1 class="text-center mb-4">Product List</h1>

            <!-- Add Product Button -->
            <div class="mb-3">
                <a href="{{ route('product.create') }}" class="btn btn-success">Add Product</a>
            </div>

            <div class="row">
                <!-- Category Filter -->
                <div class="category-filter mb-3 col-lg-5">
                    <form action="{{ route('product.index') }}" method="GET" class="d-inline">
                        <select class="form-select" name="category_id" onchange="this.form.submit()">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Product Search -->
                <div class="mb-3 col-lg-5">
                    <form action="{{ route('product.index') }}" method="GET">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search products..."
                                value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Reset Filter Button -->
                <div class="mb-3 col-lg-2">
                    <a href="{{ route('product.index') }}" class="btn refresh-button ms-1">RESET</a>
                </div>
            </div>

            <!-- Product Cards -->
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card" style="width: 18rem;">
                            <!-- Product Image -->
                            @if ($product->mainImage)
                                <img src="{{ asset('storage/' . $product->mainImage) }}" class="card-img-top"
                                    alt="Product Image">
                            @else
                                <div
                                    style="width: 100%; height: 180px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                    <span>No image available</span>
                                </div>
                            @endif

                            <!-- Card Body -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 50) }}</p>
                                <p class="card-text">Price: ${{ $product->price }}</p>
                                <p class="card-text">Stock: {{ $product->stock }}</p>
                                <p class="card-text">Category: {{ $product->category->name ?? 'N/A' }}</p>
                            </div>

                            <!-- List Group for Sorting and Category Info -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a
                                        href="{{ route('product.index', ['sort_column' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                        Sort by ID {{ $sortColumn == 'id' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                                    </a>
                                </li>
                                <!-- Additional items could be added here if needed -->
                            </ul>

                            <!-- Card Footer with Action Links -->
                            <div class="card-body d-flex justify-content-between">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form id="deleteForm-{{ $product->id }}"
                                    action="{{ route('product.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $product->id }})">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Modal for image preview (for additional image view if needed) -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div id="imageCarousel" class="carousel slide">
                                <div class="carousel-inner" id="carouselInner">
                                    <!-- Images will be dynamically added here -->
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="zoom-buttons mt-3 text-center">
                                <button id="zoomIn" class="btn btn-primary mx-2">Zoom In</button>
                                <button id="zoomOut" class="btn btn-secondary mx-2">Zoom Out</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Pagination links --}}
        <div class="pagination-container d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>


    <script>
        let currentScale = 1; // Initial scale for zoom

        function loadMoreImages(productId) {
            fetch(`/product/images/${productId}`)
                .then(response => response.json())
                .then(data => {
                    const carouselInner = document.getElementById('carouselInner');
                    carouselInner.innerHTML = ''; // Clear any previous images

                    data.images.forEach((image, index) => {
                        const carouselItem = document.createElement('div');
                        carouselItem.classList.add('carousel-item');
                        if (index === 0) {
                            carouselItem.classList.add('active'); // Set the first image as active
                        }

                        const imgElement = document.createElement('img');
                        imgElement.src = image.image_path;
                        imgElement.classList.add('d-block', 'w-100', 'zoomable-image');
                        imgElement.alt = 'Product Image';

                        // Append the image to the carousel item
                        carouselItem.appendChild(imgElement);
                        carouselInner.appendChild(carouselItem);
                    });

                    // Show the modal
                    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    imageModal.show();

                    // Reset scale when a new image is loaded
                    currentScale = 1;
                    document.querySelectorAll('.zoomable-image').forEach(image => {
                        image.style.transform = `scale(${currentScale})`;
                    });
                })
                .catch(error => console.error('Error loading images:', error));
        }

        // Zoom In function
        document.getElementById('zoomIn').addEventListener('click', () => {
            currentScale += 0.1; // Increase the scale
            document.querySelectorAll('.zoomable-image').forEach(image => {
                image.style.transform = `scale(${currentScale})`;
            });
        });

        // Zoom Out function
        document.getElementById('zoomOut').addEventListener('click', () => {
            currentScale = Math.max(1, currentScale - 0.1); // Prevent scale from going below 1
            document.querySelectorAll('.zoomable-image').forEach(image => {
                image.style.transform = `scale(${currentScale})`;
            });
        });
    </script>










    <!-- Modal for Full Description -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h4>Full Description</h4>
            <p id="fullDescription"></p>
        </div>
    </div>


    {{-- Pagination links --}}
    <div class="pagination-container d-flex justify-content-center">
        {{ $products->links() }}
    </div>
    </div>

    <script>
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => alert.remove());
        }, 5000);

        function showFullDescription(description) {
            document.getElementById('fullDescription').textContent = description;
            document.getElementById('descriptionModal').style.display = 'flex';
        }

        document.querySelector('.close-btn').addEventListener('click', function() {
            document.getElementById('descriptionModal').style.display = 'none';
        });





        //delete button

        function confirmDelete(productId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                reverseButtons: true,
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    document.getElementById(`deleteForm-${productId}`).submit();
                    Swal.fire("Deleted!", "Your imaginary file has been deleted.", "success");
                } else if (result.isDismissed) {
                    Swal.fire("Cancelled", "Your imaginary file is safe", "error");
                }
            });
        }
    </script>

    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('hide');
            }
        }, 5000); // milliseconds
    </script>

@endsection
