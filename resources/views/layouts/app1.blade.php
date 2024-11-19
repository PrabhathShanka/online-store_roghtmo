<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Product</title>

    @include('libraries.styles')
    {{--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  --}}






    <style>

    </style>


    <!-- Yield for page-specific styles -->
    @yield('styles')
    @yield('head')
</head>

<body>

    {{--  @include('components.navbar')  --}}

    @include('components.nav')

    <div class="container">

        @yield('content')

        @include('libraries.scripts')
    </div>
    @include('components.footer')
</body>

</html>
