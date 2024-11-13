@extends('layouts.app')

@section('styles')
    <style>
        body {

            .table-container.card {
                display: none;
            }

            .table-container {
                display: block;
            }

            {{--  background-color: lightgreen;  --}}

            /* Table container styling */
            .table-container {
                max-width: 90%;
                margin: 50px auto;
            }

            .pagination-container {
                max-width: 80%;
                margin: 0 auto;
            }

            .product-image {
                width: 170px;
                height: 170px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-size: cover;
                background-position: center;
            }



        }

        /* On screens that are 992px wide or less, the background color is blue */
        @media screen and (max-width: 1200px) {
            body {

                .table-container.card {
                    display: none;
                }

                .table-container {
                    display: block;
                }


                {{--  background-color: rgb(43, 60, 190);  --}}

                /* Table container styling */
                .table-container {
                    max-width: 90%;
                    margin: 50px auto;
                }

                .pagination-container {
                    max-width: 80%;
                    margin: 0 auto;
                }

                .product-image {
                    width: 140px;
                    height: 140px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-size: cover;
                    background-position: center;
                }
            }
        }

        /* On screens that are 600px wide or less, the background color is olive */
        @media screen and (max-width: 992px) {
            body {
                {{--  background-color: rgb(190, 43, 165);  --}} .table-container.card {
                    display: none;
                }

                .table-container {
                    display: block;
                }

                /* Table container styling */
                .container,
                .container-md,
                .container-sm {
                    max-width: max-content;
                }

                .table-container {
                    max-width: 100%;
                    margin: 50px auto;
                }

                .pagination-container {
                    max-width: 80%;
                    margin: 0 auto;
                }

                .product-image {
                    width: 140px;
                    height: 140px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-size: cover;
                    background-position: center;
                }
            }
        }


        /* Styling for screens 877px or smaller */
        @media screen and (max-width: 877px) {
            body {
                .table-container {
                    display: none;
                }



                .table-container.card {
                    display: block;
                }


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



        {{--  ----------------------------------WEB---------------------------------------  --}}

        <div class="table-container">
            <h1 class="text-center mb-4">Product List</h1>

            <!-- Add Product Button -->
            <div class="mb-3">
                <a href="{{ route('product.create') }}" class="btn btn-success">Add Product</a>
            </div>

            <div class="row">
                {{-- Category Filter --}}
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

                {{-- Product Search --}}
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

                {{-- Reset Filter Button --}}
                <div class="mb-3 col-lg-2">
                    <a href="{{ route('product.index') }}" class="btn refresh-button ms-1">RESET</a>
                </div>
            </div>

            <!-- Product Table -->
            <div class="container-fluid table-wrap">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="column-id">
                                <a
                                    href="{{ route('product.index', ['sort_column' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    ID @if ($sortColumn == 'id')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-image">Image</th>
                            <th class="column-name">
                                <a
                                    href="{{ route('product.index', ['sort_column' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Name @if ($sortColumn == 'name')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-description">
                                <a
                                    href="{{ route('product.index', ['sort_column' => 'description', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Description @if ($sortColumn == 'description')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-price">
                                <a
                                    href="{{ route('product.index', ['sort_column' => 'price', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Price @if ($sortColumn == 'price')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-stock">
                                <a
                                    href="{{ route('product.index', ['sort_column' => 'stock', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Stock @if ($sortColumn == 'stock')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-category">Category</th>
                            <th class="column-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>


                                <th style="text-align: center; vertical-align: middle;">

                                    @if ($product->mainImage)
                                        <div class="product-image"
                                            style="background-image: url('{{ asset('storage/' . $product->mainImage) }}');">
                                            <!-- Content (if any) goes here -->
                                        </div>
                                    @else
                                        <div
                                            style="width: 170px; height: 170px; display: flex; justify-content: center; align-items: center; overflow: hidden; background-color: #f0f0f0;">
                                            <span>No image available</span>
                                        </div>
                                    @endif

                                    <!-- "More" button to load additional images -->
                                    <button class="btn btn-link btn-sm" onclick="loadMoreImages({{ $product->id }})"
                                        style="margin-top: 10px;">More</button>

                                </th>





                                <td>{{ $product->name }}</td>
                                <td>
                                    <span class="short-description">{{ Str::limit($product->description, 20) }}</span>
                                    <button class="btn btn-link btn-sm"
                                        onclick="showFullDescription('{{ addslashes($product->description) }}')">More</button>
                                </td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="{{ route('product.edit', $product->id) }}"
                                            class="btn btn-primary btn-sm me-1">Edit</a>
                                        <form id="deleteForm-{{ $product->id }}"
                                            action="{{ route('product.destroy', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $product->id }})">Delete</button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>



            <!-- Modal for image preview with carousel functionality -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <!-- Carousel for images without automatic sliding -->
                            <div id="imageCarousel" class="carousel slide">
                                <div class="carousel-inner" id="carouselInner">
                                    <!-- Images will be dynamically added here -->
                                </div>
                                <!-- Carousel controls -->
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

                            <!-- Zoom In and Zoom Out Buttons -->
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

            {{-- Pagination links --}}
            <div class="pagination-container d-flex justify-content-center">
                {{ $products->links() }}
            </div>

        </div>

        {{--  -------------------------------------------------------------------------  --}}

        <!-- Centering Container -->
        <div style="display: flex; justify-content: center; align-items: center; padding: 20px; ">

            <!-- Product Table Container -->
            <div class="table-container card" style="width: 100%; max-width: 1200px;">
                <h1 class="text-center mb-4">Product List</h1>

                <!-- Add Product Button -->
                <div class="mb-3 text-center">
                    <a href="{{ route('product.create') }}" class="btn btn-success">Add Product</a>
                </div>

                <div class="row justify-content-center">
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
                                <input type="text" class="form-control" name="search"
                                    placeholder="Search products..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>

                    <!-- Reset Filter Button -->
                    <div class="mb-3 col-lg-2 text-center">
                        <a href="{{ route('product.index') }}" class="btn refresh-button ms-1">RESET</a>
                    </div>
                </div>


                <!-- Sorting Links -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div style="display: flex; justify-content: space-between;">
                            <a
                                href="{{ route('product.index', ['sort_column' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                Name @if ($sortColumn == 'name')
                                    <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                            <a
                                href="{{ route('product.index', ['sort_column' => 'price', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                Price @if ($sortColumn == 'price')
                                    <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </div>
                    </li>
                </ul>

                <!-- Product Cards -->
                <div class="row justify-content-center">





                    @foreach ($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 100%; height: 100%;">
                                <div style="width: 100%; display: flex; justify-content: center; align-items: center;">
                                    <!-- Product Image -->
                                    @if ($product->mainImage)
                                        <div class="product-image"
                                            style="width: 180px; height: 180px; background-size: cover; background-position: center; background-image: url('{{ asset('storage/' . $product->mainImage) }}');">
                                        </div>
                                    @else
                                        <div
                                            style="width: 170px; height: 170px; display: flex; justify-content: center; align-items: center; overflow: hidden; background-color: #f0f0f0;">
                                            <span>No image available</span>
                                        </div>
                                    @endif
                                </div>




                                <!-- Card Body with fixed height -->
                                <div class="card-body" style="min-height: 200px;">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">
                                        <span class="short-description">{{ Str::limit($product->description, 55) }}</span>
                                        <button class="btn btn-link btn-sm"
                                            onclick="showFullDescription('{{ addslashes($product->description) }}')">More</button>
                                    </p>
                                    <p class="card-text">Price: ${{ $product->price }}</p>
                                    <p class="card-text">Stock: {{ $product->stock }}</p>
                                    <p class="card-text">Category: {{ $product->category->name ?? 'N/A' }}</p>
                                </div>



                                <!-- Card Footer with Action Links -->
                                <div class="card-body d-flex justify-content-between">
                                    <a href="{{ route('product.edit', $product->id) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
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

                    {{-- Pagination links --}}
                    <div class="pagination-container d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>


            </div>
        </div>



    </div>

    </div>





    <!-- Modal for Full Description -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h4>Full Description</h4>
            <p id="fullDescription"></p>
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
