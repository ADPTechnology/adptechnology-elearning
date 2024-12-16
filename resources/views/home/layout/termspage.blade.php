<!DOCTYPE html>
<html lang="es">

@include('home.partials.head')

<!-- Spinner Start -->

<body class="page_main_body">


    <div id="spinner"
        class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>


    <!-- Spinner End -->


    <!-- Navbar Start -->

    @include('home.partials.navbar-page')

    <!-- Navbar End -->


    @yield('content')


    <!-- JavaScript Libraries -->

    @include('home.partials.scripts')

    @yield('modals')

</body>

</html>
