@extends('auth.layouts.login-layout')

@section('title', 'Aula Virtual | Login')

@section('content')

    <main class="main-content main-login mt-0" data-url="{{ getConfig()->background_url }}">

        <span class="bg-filter"></span>

        <div class="page-header min-vh-100">

            <div class="right-container register container">

                <div class="right-form-container register">

                    <div class="cont-txt-login d-flex">
                        <img src="{{ asset('assets/common/images/adp-logo.png') }}" alt="">
                    </div>

                    <div class="aula-title text-center">
                        REGISTRARSE
                    </div>

                    <div class="card-body" id="register-content-box">

                        @include('auth.partials.boxes._register_form')

                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection


@section('js')
    <script>
        let bgRegister = document.querySelector('.main-login');
        let urlRegister = bgRegister.getAttribute('data-url');
        bgRegister.style.backgroundImage = `url(${urlRegister})`;
        bgRegister.classList.add('background-image');
    </script>
@endsection
