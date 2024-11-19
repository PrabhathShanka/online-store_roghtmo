@extends('layouts.app1')

@section('styles')
    <link rel="stylesheet" href="{{ asset('CSS/category/index.css') }}">
@endsection


@section('add_nav_right_side')
    {{-- Category Search --}}
    <div class="mb-3 col-lg-4">
        <form action="{{ route('category.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search categories..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
    </div>
    </div>
@endsection


@section('add_nav_left_side')
    <li class="nav-item">
        <a class="nav-link active" href="#">Home</a>
    </li>

    <li class="nav-item">
        <a class="nav-link active" href="{{ route('product.index') }}">Products</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('category.index') }}">Categories</a>
    </li>
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

    <div class="container table-container">
        <h1 class="text-center mb-4">Category List</h1>


        <div class="row justify-content-center">
            <!-- Add Category Button -->
            <div class="mb-3 col-lg-4">
                <a href="{{ route('category.create') }}" class="btn btn-success">Add Category</a>
            </div>


            <!-- Category Table -->
            <table class="table table-bordered table-striped ali">
                <thead>
                    <tr>
                        <th class="column-id">
                            <a
                                href="{{ route('category.index', ['sort_column' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                ID
                                @if ($sortColumn == 'id')
                                    <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="column-category">
                            <a
                                href="{{ route('category.index', ['sort_column' => 'name', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                Category
                                @if ($sortColumn == 'name')
                                    <span>{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="column-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <div class="d-inline-flex align-items-center">
                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="btn btn-primary btn-sm me-1">Edit</a>
                                    <form id="deleteForm-{{ $category->id }}"
                                        action="{{ route('category.destroy', $category->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $category->id }})">Delete</button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="pagination-container d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>



        <script src="{{ asset('JS/category/index.js') }}"></script>

    @endsection
