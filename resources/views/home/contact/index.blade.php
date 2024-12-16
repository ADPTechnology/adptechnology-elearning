@extends('home.layout.masterpage')

@section('content')
    @php
        $config = getConfig();
    @endphp
    <!-- Header Start -->
    <div class="container-fluid bg-head-section py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Contáctanos</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Contáctanos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Service Start -->

    <div class="container mt-5">
        <div class="row">
            <!-- Formulario de Contacto -->
            <div class="col-md-6 mb-4">
                <form id="contactUsForm" action="{{ route('home.about.contact.sendEmail') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="names" name="names"
                            placeholder="Ingresa tus nombres">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Ingresa tu correo">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Escribe tu mensaje..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-send btn-primary">Enviar</button>
                </form>
                <div id="confirmationMessage" class="alert alert-success mt-3 message-contact-none">
                    Gracias por contactarnos. Nos comunicaremos con usted pronto.
                </div>
            </div>


            <!-- Información Básica -->
            <div class="col-md-6">
                <p class="text-contact">Si deseas comunicarte con nosotros, no dudes en hacerlo. Estamos disponibles para
                    responder tus preguntas
                    y proporcionarte más información.</p>
                <div class="contact-icons">
                    <div class="text-contact">
                        <span class="span-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        {{ $config->address }}
                    </div>
                    <div class="text-contact">
                        <span class="span-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        {{ $config->whatsapp_number }}
                    </div>
                    <div class="text-contact">
                        <span class="span-icon">
                            <i class="fa-solid fa-at"></i>
                        </span>
                        {{ $config->email }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service End -->
@endsection

@section('extra-script')
    <script src="{{ asset('assets/home/js/contact-us.js') }}"></script>
@endsection
