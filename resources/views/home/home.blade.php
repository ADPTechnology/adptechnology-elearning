@extends('home.layout.masterpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
    <style>
        /* Fondo borroso detrás del popup */
    </style>
@endsection


@section('content')
    <!-- Carousel Start -->

    <div class="container-fluid p-0 mb-5 principal-carrousel-container">

        <div class="owl-carousel header-carousel position-relative">

            @forelse ($banners as $banner)
                <div class="owl-carousel-item position-relative">

                    <img class="img-fluid" src="{{ verifyImage($banner->file) }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-sm-10 col-lg-9">
                                    @if ($banner->title)
                                        <span class="text-uppercase mb-3 animated slideInDown badge-pill-subtitle">
                                            {{ $banner->title }}
                                        </span>
                                    @endif
                                    <div class="mt-4 text-white animated slideInDown banner-content-container">
                                        {!! $banner->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="owl-carousel-item position-relative">

                    <img class="img-fluid" src="{{ verifyImage(false) }}" alt="">

                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="container">
                            <div class="row justify-content-start">
                            </div>
                        </div>
                    </div>

                </div>
            @endforelse

        </div>

    </div>

    <!-- Carousel End -->

    {{--
    <div class="count-up-wrapper top">
        <span id="count-up-container-top">0</span>
        <span class="additional-info">Stars in Github:</span>
    </div>
    <div class="count-up-wrapper bottom">
        <span class="additional-info">Used by:</span>
        <span id="count-up-container-bottom">0</span>
    </div> --}}


    {{-- <div class="end-container">Thanks for watching</div> --}}

    <div class="container-xxl py-5 courses-container">
        <div class="container wow fadeInUp" data-wow-delay="0.1s">

            <div class="text-center wow fadeInUp" data-wow-delay="0.1s"
                style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                <h6 class="section-title text-center px-3">
                    <div></div>
                    <i class="fa-solid fa-arrow-up-right-dots text-secondary fa-xl" aria-hidden="true"></i>
                </h6>
                <h1 class="mb-5">Construyendo equipos listos para el futuro</h1>
            </div>

            <div class="row justify-content-center">
                {{-- <h3 class="col-12 text-center p-4">Construyendo equipos listos para el futuro</h3> --}}
                <div class="col-sm-12 col-lg-3 text-center">
                    <span id="count-up-students" class="making-numbers" data-number="{{ $numberUsers }}"></span>
                    <p>Estudiantes registrados en nuestra plataforma.</p>
                </div>
                <div class="col-sm-12 col-lg-3 text-center">
                    <span id="count-up-courses" class="making-numbers" data-number="{{ $numberCourses }}"></span>
                    <p>Cursos publicados.</p>
                </div>
                {{-- <div class="col-sm-12 col-lg-3 text-center">
                    <span id="count-up-companys" class="making-numbers" data-number="{{ $numberCompanys }}"></span>
                    <p>empresas desarrollando a sus equipos con nosotros.</p>
                </div> --}}
            </div>
        </div>
    </div>



    <div class="container-xxl py-5 courses-container">
        <div class="container">

            <div class="text-center wow fadeInUp" data-wow-delay="0.1s"
                style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                <h6 class="section-title text-center px-3">
                    <div></div>
                    <i class="fa-solid fa-list-check text-secondary fa-xl" aria-hidden="true"></i>
                </h6>
                <h1 class="">Características que nos hacen únicos</h1>
                <p class="mb-5 col-12 text-center p-4">El único sistema para capacitar a tu personal de campo y atención al
                    cliente y validar su aprendizaje</p>
            </div>

            <div class="row container-characteristics">

                <div class="col-sm-12 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">

                    <div class="div-effect d-flex flex-column" style="--color:#e16e6c;--color2:#e16e6c;">


                        <div class="card-img">
                            <img class="w-100" src="{{ asset('assets/common/images/ilu1.png') }}" alt="">
                        </div>

                        <div class="bg-properties-section-item">

                            <div class="container-description p-5">

                                <h4 class="title-description">Interacción directa con <br> los contenidos</h4>
                                <p>Los estudiantes pueden interactuar con los materiales del curso de manera activa, lo que
                                    puede mejorar la comprensión y el aprendizaje.</p>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 wow fadeInUp" data-wow-delay="0.3s"">
                    <div class="div-effect d-flex flex-column" style="--color:#9d82dd;--color2:#9d82dd;">
                        <div class="card-img">
                            <img class="w-100" src="{{ asset('assets/common/images/ilu2.png') }}" alt="">
                        </div>

                        <div class="bg-properties-section-item">
                            <div class="container-description p-5">
                                <h4 class="title-description">Flexible, intuitiva y <br> autogestionable</h4>
                                <p>Personaliza y centraliza tu plan de entrenamiento segmentando el contenido en función a
                                    la
                                    estructura de tu equipo.</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 wow fadeInUp" data-wow-delay="0.5s"">
                    <div class="div-effect d-flex flex-column" style="--color:#cd843c;--color2:#cd843c;">
                        <div class="card-img">
                            <img class="w-100" src="{{ asset('assets/common/images/ilu3.png') }}" alt="">
                        </div>

                        <div class="bg-properties-section-item">

                            <div class="container-description p-5">
                                <h4 class="title-description">Nos actualizamos <br> contigo</h4>
                                <p>Actualizamos constantemente nuestro E-Learning System, tomando en cuenta tu feedback y el
                                    de
                                    tus colaboradores.</p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Courses Start -->

    {{-- @include('home.courses.partials._courses_list') --}}
    @include('home.freecourses.partials.boxes._freecourses_list')

    <br id="to-plans">

    <!-- Plans -->

    {{-- <x-common.plans /> --}}

    {{-- start popup --}}

    <x-common.advertisements />


    {{-- end popup --}}


    <!-- Courses End -->

    <!-- Categories Start -->

    {{-- @include('home.freecourses.partials.boxes._categories_list') --}}

    <!-- Categories Start -->

    {{-- start testimonials --}}

    <div class="py-5 courses-container testimonials-container">
        <div class="container">

            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title-green bg-green text-center px-3">
                    <div></div>
                    Testimonios
                </h6>
                <h1 class="mb-5 color-white">Testimonios de nuestros estudiantes</h1>
            </div>

            <div class="container mt-5">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Primer Testimonio -->

                        @php
                            $i = 1;
                        @endphp

                        @forelse ($testimonials as $testimony)
                            <div class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                <div class="row justify-content-center align-items-center testimonial">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ verifyUserAvatar($testimony->file) }}"
                                            alt="{{ $testimony->full_name }}" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <p class="color-white">"{{ $testimony->testimony }}"</p>
                                        <h5>{{ $testimony->full_name }}</h5>
                                    </div>
                                </div>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @empty
                            <p class="font-italic text-center">
                                Aún no hay testimonios.
                            </p>
                        @endforelse

                    </div>

                    @if ($testimonials->count() > 1)
                        <!-- Controles del carrusel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- end testimonials --}}


    {{-- faq start --}}

    <div class="container-xxl py-5 courses-container">
        <div class="container">



            {{-- <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center px-3">
                    <div></div>
                    Preguntas
                </h6>
                <h1 class="mb-5">Preguntas frecuentes</h1>
            </div> --}}

            <div class="text-center wow fadeInUp" data-wow-delay="0.1s"
                style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                <h6 class="section-title text-center px-3">
                    <div></div>
                    <i class="fa-solid fa-question text-secondary fa-xl" aria-hidden="true"></i>
                </h6>
                <h1 class="mb-5">Preguntas frecuentes</h1>
            </div>



            <div class="row">
                <div class="col-sm-12 col-lg-6 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <img class="w-75" src="{{ asset('assets/home/img/faq-transparent.png') }}" alt="">
                </div>
                <div class="col-sm-12 col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    ¿Cómo puedo acceder a los materiales del curso?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Los materiales del curso suelen estar disponibles en la plataforma de e-learning. Puedes
                                    encontrarlos en la sección de recursos del curso o en el aula virtual, dependiendo de la
                                    plataforma que estés utilizando.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    ¿Qué hago si tengo problemas técnicos?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Si tienes problemas técnicos, lo mejor es ponerse en contacto con el soporte técnico de
                                    la plataforma. También puedes consultar la sección de preguntas frecuentes o el manual
                                    del usuario para obtener ayuda.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    ¿Cómo puedo interactuar con otros estudiantes y con el profesor?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    La mayoría de las plataformas de e-learning ofrecen foros de discusión donde puedes
                                    interactuar con otros estudiantes y con el profesor. También puedes utilizar el correo
                                    electrónico o la mensajería instantánea para comunicarte directamente con ellos.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- faq end --}}

    {{-- <script src="{{asset('assets/home/js/countUp.js')}}"></script> --}}
    <script src="{{ asset('assets/home/js/contador.js') }}" type="module"></script>
@endsection


@section('extra-script')
    <script type="module" src="{{ asset('assets/home/js/freeCourses.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var promoModal = new bootstrap.Modal(document.getElementById('promoModal'), {
                backdrop: 'static',
                keyboard: false
            });
            promoModal.show();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElements = document.querySelectorAll('.time-left-badge');

            countdownElements.forEach(element => {
                const expiredDateString = element.getAttribute('data-expired-date');
                const expiredDate = new Date(expiredDateString.includes(':') ? expiredDateString :
                    `${expiredDateString} 23:59:59`).getTime();

                const countdownInterval = setInterval(() => {
                    const now = new Date().getTime();
                    const timeLeft = expiredDate - now;

                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    if (timeLeft > 0) {
                        element.querySelector('.countdown').innerHTML =
                            `${days} días, ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    } else {
                        element.querySelector('.countdown').innerHTML = "Oferta Expirada";
                        clearInterval(countdownInterval);
                    }
                }, 1000);
            });
        });
    </script>
@endsection

@section('modals')
    <div id="login_register_modal" class="is_webinar">

    </div>

    @include('home.common.partials.modals._information_free-courses')
@endsection
