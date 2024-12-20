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

    {{--   WHATSAPP FLOATING BUTTON   --}}

    @php
        $config = getConfig();
    @endphp

    <div class="container-social-media">

        <x-common.whatsapp-chat :config="$config" />

        @if ($config->link_group_telegram)
            <a href="{{ $config->link_group_telegram }}" target="_BLANK" class="btn-telegram-pulse">
                <i class="fa-brands fa-telegram"></i>
            </a>
        @endif
    </div>


    <!-- Spinner End -->


    <!-- Navbar Start -->

    @include('home.partials.navbar')

    <!-- Navbar End -->


    @yield('content')


    <!-- Footer Start -->

    @include('home.partials.footer')

    <!-- Footer End -->


    <!-- Back to Top -->

    {{-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> --}}


    <!-- JavaScript Libraries -->

    @include('home.partials.scripts')

    @yield('modals')

</body>

</html>
