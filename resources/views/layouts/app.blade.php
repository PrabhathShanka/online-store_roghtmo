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
        /* Navbar style */
        .navbar {
            width: 100%;
            background-color: #0e173d !important;
            padding: 15px;

        }




        /* Footer Container */
        footer {
            position: fixed;
            /* Fixes the footer at the bottom */
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #0e173d !important;
            color: #faf4f4 !important;
            height: 60px;
            /* Adjust the height of the footer */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            /* Optional padding inside the footer */
            font-size: 14px;
            /* Optional font size */
        }



        .navbar a:hover {
            background-color: #78a7dc !important;
            /* Change color on hover */
            border-radius: 5px;
            /* Rounded corners */
        }

        .navbar-brand {
            color: #f5f5f6;
            /* brand link color */
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-brand:hover {
            color: #194677;
            /* hover effect for brand */
        }



        .navbar-nav .nav-link {
            font-weight: bold;

        }

        .navbar-nav .nav-link.active {
            color: #fef7f7;
            /* Change the active link color if needed */
        }

        .navbar-nav .nav-link:hover {
            color: #ffffff;
            /* Change hover color */
        }


        .nav-link:hover {
            color: #115297;

            {{--  text-decoration: underline;  --}}
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            color: #007bff;
        }

        body {
            padding-top: 70px;
            /* Adjust this value to match the height of your navbar */
        }
    </style>


    <!-- Yield for page-specific styles -->
    @yield('styles')
    @yield('head')
</head>

<body>

    @include('components.nav')
    <div class="container">

        @yield('content')

        @include('libraries.scripts')
    </div>
    @include('components.footer')
</body>

</html>
