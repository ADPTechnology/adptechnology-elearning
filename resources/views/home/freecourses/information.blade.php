@extends('home.layout.masterpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@section('content')
    <!-- Header Start -->

    <div class="container-fluid bg-head-section py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h3 class="display-4 text-white animated slideInDown">
                        {{ ucfirst(mb_strtolower($course->description, 'UTF-8')) }}
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('home.freecourses.categories.index') }}" class="text-white">
                                    Cursos
                                </a>
                            </li>
                            <li class="breadcrumb-item text-white active" aria-current="page">
                                <a href="{{ route('home.information.course', ['course' => $course]) }}" class="text-white">
                                    {{ $course->description }}
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <!-- Header End -->


    <div class="container mt-2">
        <div class="row justify-content-center" id="container_plans">

            @auth
                @include('home.freecourses.partials.boxes._plans')
            @endauth

            @guest
                @include('home.freecourses.partials.boxes._plans_session')
            @endguest


        </div>
    </div>


    <!-- Courses Start -->

    <div class="container-xxl py-5 courses-container" id="events-list-container">

        @include('home.freecourses.partials.boxes._information')

    </div>




    <!-- Courses End -->
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/home/js/freeCourses.js') }}"></script>
@endsection

@section('modals')
    <div id="login_register_modal" class="is_webinar">

    </div>

    @include('home.common.partials.modals._information_free-courses')
@endsection
