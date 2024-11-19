<link rel="stylesheet" href="{{ asset('CSS/components/nav.css') }}">
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">STORE</a>


        @yield('add_nav_right_side')


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                @yield('add_nav_left_side')


            </ul>
        </div>
    </div>
</nav>
