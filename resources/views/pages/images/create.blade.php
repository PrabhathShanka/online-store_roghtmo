@extends('layouts.app')

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
    <h1>Upload Image for Product ID: {{ $id }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('products.images.store', $id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Select Image</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Upload Image</button>
    </form>
@endsection
