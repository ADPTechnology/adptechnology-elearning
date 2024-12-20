@extends('home.layout.masterpage')

@section('content')
    <!-- Header Start -->

    <div class="container-fluid bg-header-section py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h3 class="display-4 text-white animated slideInDown">
                        {{ ucfirst(mb_strtolower($webinar->title, 'UTF-8')) }}
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('home.webinar.index') }}" class="text-white">
                                    Webinars
                                </a>
                            </li>
                            <li class="breadcrumb-item text-white active" aria-current="page">
                                {{ $webinar->title }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Header End -->


    <!-- events -> webinar Start -->

    <div class="container-xxl py-5 courses-container" id="events-list-container">

        @include('home.webinar.partials._events_list')

    </div>

    <!-- events -> webinar End -->
@endsection


@section('modals')
    <div id="login_register_modal" class="is_webinar">

    </div>
@endsection
