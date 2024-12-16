@extends('auth.layouts.login-layout')

@section('title', 'Aula Virtual | Login')

@section('content')

    @php
        $config = getConfig();
    @endphp

    <section class="background-radial-gradient overflow-hidden" data-url="{{ getConfig()->background_url }}">
        <span class="bg-filter"></span>

        <div class="container px-4 py-5 px-md-5 text-center text-lg-start h-100 d-flex justify-content-center  min-vh-100">

            <div class="row w-100 gx-lg-5 align-items-center">

                <div class="col-lg-5 col-md-12 col-sm-12 mb-lg-0 position-relative ">



                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass" style="">

                        <div class="container-image">
                            <img src="{{ asset('assets/common/images/adp-logo-white.png') }}" alt="logo"
                                class="img-fluid">
                        </div>
                        <br>

                        <div class="card-body container-form px-5 py-4 px-md-5">
                            <form method="POST" action="{{ route('login') }}" role="form">
                                @csrf

                                <div class="container-description">
                                    <h1>Bienvenido(a)</h1>
                                    <p>a un paso de vivir la <strong>#experienciaADP</strong></p>
                                </div>


                                <div class="container mb-2">
                                    <div class="d-flex justify-content-center align-items-center">

                                        {{-- <a href="{{ route('auth.facebook.redirect') }}"
                                            class="btn btn-light rounded-circle d-flex justify-content-center align-items-center"
                                            style="width: 48px; height: 48px;" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="none" viewBox="0 0 28 28">
                                                <path fill="#1877F2"
                                                    d="M14.001.67C6.64.67.67 6.638.67 14c0 6.654 4.875 12.168 11.25 13.172v-9.317H8.532v-3.855h3.387v-2.937c0-3.344 1.99-5.188 5.034-5.188 1.46 0 2.987.26 2.987.26v3.279h-1.685c-1.654 0-2.17 1.029-2.17 2.084v2.5h3.694l-.59 3.854h-3.105v9.318c6.375-.999 11.25-6.515 11.25-13.17C27.333 6.64 21.363.67 14 .67z">
                                                </path>
                                            </svg>
                                        </a> --}}

                                        <a href="{{ route('auth.google.redirect') }}"
                                            class="btn btn-light rounded-circle d-flex justify-content-center align-items-center"
                                            style="width: 48px; height: 48px;" type="button" id="customGoogleButton-1">
                                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 512 512" width="32"
                                                height="32" enable-background="new 0 0 512 512">
                                                <path
                                                    d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256 c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456 C103.821,274.792,107.225,292.797,113.47,309.408z"
                                                    style="fill: rgb(251, 187, 0);"></path>
                                                <path
                                                    d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451 c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535 c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"
                                                    style="fill: rgb(81, 142, 248);"></path>
                                                <path
                                                    d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512 c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771 c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"
                                                    style="fill: rgb(40, 180, 70);"></path>
                                                <path
                                                    d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012 c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0 C318.115,0,375.068,22.126,419.404,58.936z"
                                                    style="fill: rgb(241, 67, 54);"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="email" class="form-label">Correo</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="Ingresa tu correo">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        value="{{ old('password') }}" required autocomplete="password" autofocus
                                        placeholder="Ingresa tu contraseña">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <a href="{{ route('password.request') }}">
                                        ¿Olvidaste la contraseña?
                                    </a>
                                </div>

                                <button type="submit" class="btn btn-ingresar w-100 my-2 ">{{ 'INICIAR SESIÓN' }}</button>

                                <p class="span-register text-center mt-2">
                                    ¿Es la primera vez que usas Adp-Elerarning?
                                    <a href="{{ route('register.show') }}">Regístrate</a>
                                </p>

                            </form>
                        </div>
                    </div>

                </div>


                <div id="capaTwo" class="col-lg-7 col-md-12 col-sm-12 mb-lg-0 capaTwo" style="z-index: 10">

                    <div class="container-carousel d-flex justify-content-center">
                        <div id="carouselExampleIndicators" style="width: 500px; height: 500px;"
                            class="carousel slide carousel-fade" data-bs-ride="carousel">
                            <div class="carousel-indicators">

                                @php
                                    $activeIndicators = 'active';
                                    $number = 0;
                                @endphp

                                @forelse ($sliderImages as $sliderImage)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        class="{{ $activeIndicators }}" data-bs-slide-to="{{ $number }}"
                                        aria-current="true" aria-label="Slide {{ $number + 1 }}"></button>
                                    @php
                                        $activeIndicators = '';
                                        $number++;
                                    @endphp
                                @empty
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        class="{{ $activeIndicators }}" data-bs-slide-to="{{ $number }}"
                                        aria-current="true" aria-label="Slide {{ $number + 1 }}"></button>
                                @endforelse

                            </div>
                            <div class="carousel-inner container-carousel-inner" style="width: 100%; height: 100%">

                                @php
                                    $activeCarousel = 'active';
                                @endphp

                                @forelse ($sliderImages as $sliderImage)
                                    <div class="carousel-item {{ $activeCarousel }}">
                                        <img src="{{ verifyImage($sliderImage->file) }}" class="d-block h-100 img-slider"
                                            style="object-fit:cover;">
                                        {!! $sliderImage->content !!}

                                    </div>
                                    @php
                                        $activeCarousel = '';
                                    @endphp
                                @empty
                                    <div class="carousel-item {{ $activeCarousel }}">
                                        <img src=" {{ asset('assets/login/img/left-login.jpg') }} "
                                            class="d-block h-100 img-slider" style="object-fit:cover;">
                                    </div>
                                @endforelse

                            </div>

                            @if ($sliderImages->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            @endif

                        </div>
                    </div>


                </div>

            </div>
        </div>
    </section>

@endsection

@section('js')
    <script>
        let bg = document.querySelector('.background-radial-gradient');
        let url = bg.getAttribute('data-url');
        bg.style.backgroundImage = `url(${url})`;
        bg.classList.add('background-image');
    </script>

@endsection
