<head>
    <meta charset="utf-8">
    <title> @yield('title', 'E-Learning') </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('assets/home/img/icon-transparent.png') }}" rel="icon">

    @stack('first-head')

    <!-- Google Web Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    @include('scripts.font-awesome')

    <!-- Icon Font Stylesheet -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->

    <link href="{{ asset('assets/home/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/home/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    {{-- ----- IZI MODAL ---- --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/css/iziModal.min.css"
        integrity="sha512-3c5WiuZUnVWCQGwVBf8XFg/4BKx48Xthd9nXi62YK0xnf39Oc2FV43lIEIdK50W+tfnw2lcVThJKmEAOoQG84Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- ----- SELECT 2 ----- --}}

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/home/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/home/css/style.css') }}" rel="stylesheet">

    @yield('extra-head')

</head>
