@extends('layouts.app1')
<link rel="stylesheet" href="{{ asset('CSS/category/edit.css') }}">
@section('styles')
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

    <div class="container form-container">
        <h1 class="text-center mb-4">Edit Category</h1>

        <form action="{{ route('category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Category Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Category</button>
        </form>
    </div>
    <script src="{{ asset('JS/category/index.js') }}"></script>
@endsection
