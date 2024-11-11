{{--  @section('styles')  --}}
{{--  <link rel="stylesheet" href="{{ resource_path('css\home\index.css') }}">  --}}

{{--  <link rel="stylesheet" href="../{{ asset('css/home/index.css') }}">
@endsection  --}}



@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home/index.css') }}">
@endsection

@section('content')
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-12">
                <h1>Home page</h1>
            </div>
        </div>
    </div>
@endsection
