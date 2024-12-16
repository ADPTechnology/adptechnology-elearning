@extends('auth.layouts.login-layout')

@section('title', 'Valuem | Restablecer contraseña')

@section('content')

    <section class="background-radial-gradient overflow-hidden" data-url="{{ getConfig()->background_url }}">
        <span class="bg-filter"></span>

        <div class="container px-4 py-5 px-md-5 text-center text-lg-start h-100 d-flex justify-content-center  min-vh-100">

            <div class="row w-100 gx-lg-5 align-items-center">

                <div class="col-lg-5 col-md-12 col-sm-12 mb-lg-0 position-relative ">

                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass" style="">

                        <div class="container-image">
                            <img src="{{ asset('assets/common/images/adp-logo-white.png') }}" alt="logo" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-md-12 col-sm-12 mb-lg-0 capaTwo" style="z-index: 10">
                    <div class="card-body container-form-reset px-5 py-5" style=" border-radius: 10px;">
                        <h2 class="text-center mb-4">Restablecer Contraseña</h2>
                        <p class="text-center">
                            Te enviaremos instrucciones a tu correo electrónico de registro para restablecer tu contraseña.
                        </p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}" class="minimal-form">
                            @csrf

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

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-submit">
                                    Enviar enlace
                                </button>
                            </div>
                        </form>
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
