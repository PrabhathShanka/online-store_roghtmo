@extends('layouts.app1')

@section('styles')
    <link rel="stylesheet" href="{{ asset('CSS/product/index.css') }}">
@endsection

@section('add_nav_right_side')
    <div class="row">
        {{-- Category Filter --}}
        <div class="category-filter mb-3 col-lg-5">
            <form action="{{ route('home.index') }}" method="GET" class="d-inline">
                <select class="form-select" name="category_id" onchange="this.form.submit()">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Product Search --}}
        <div class="mb-3 col-lg-5">
            <form action="{{ route('home.index') }}" method="GET">
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
            <a href="{{ route('home.index') }}" class="btn refresh-button ms-1">RESET</a>
        </div>
    </div>
@endsection


@section('add_nav_left_side')
    <li class="nav-item">
        <a class="nav-link active" href="#">Home</a>
    </li>

    <li class="nav-item">
        <a class="nav-link active" href="{{ route('home.index') }}">Products</a>
    </li>
    <li class="nav-item">
        {{--  <a class="nav-link active" href="{{ route('category.index') }}">Cart</a>  --}}
        <a class="nav-link active" href="#">Cart</a>
    </li>

    <li class="nav-item">
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-responsive-nav-link :href="route('logout')"
                onclick="event.preventDefault();
                        this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
        </form>
    </li>
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

            <!-- Product Table -->
            <div class="container-fluid table-wrap">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="column-id">
                                <a
                                    href="{{ route('home.index', ['sort_column' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    ID @if ($sortColumn == 'id')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-image">Image</th>
                            <th class="column-name">
                                <a
                                    href="{{ route('home.index', ['sort_column' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Name @if ($sortColumn == 'name')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-description">
                                <a
                                    href="{{ route('home.index', ['sort_column' => 'description', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Description @if ($sortColumn == 'description')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-price">
                                <a
                                    href="{{ route('home.index', ['sort_column' => 'price', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                    Price @if ($sortColumn == 'price')
                                        <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="column-stock">
                                <a
                                    href="{{ route('home.index', ['sort_column' => 'stock', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
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
                                        {{--  <a href="{{ route('product.edit', $product->id) }}"
                                        class="btn btn-primary btn-sm me-1">Edit</a>  --}}
                                        <a href="#" class="btn btn-primary btn-sm me-1">Add to Cart</a>

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
                <br>
                <br>
                <br>
                <br>

                <br>
                <br>
                <h1 class="text-center mb-4">Product List</h1>







                <!-- Sorting Links -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div style="display: flex; justify-content: space-between;">
                            <a
                                href="{{ route('home.index', ['sort_column' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
                                Name @if ($sortColumn == 'name')
                                    <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                            <a
                                href="{{ route('home.index', ['sort_column' => 'price', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'category_id' => request('category_id')]) }}">
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
                                    {{--  <a href="{{ route('product.edit', $product->id) }}"
                                    class="btn btn-primary btn-sm">Edit</a>  --}}
                                    <a href="#" class="btn btn-primary btn-sm">Add to Cart</a>
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



    <script src="{{ asset('JS/product/index.js') }}"></script>


@endsection
