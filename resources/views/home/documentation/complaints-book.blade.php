@extends('home.layout.termspage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="w-100 mx-auto mt-6 p-5 bg-terms shadow-md overflow-hidden rounded-lg">

            <h1>Libro de reclamaciones</h1>

            <div class="container mt-5">
                <div class="row">
                    <!-- Formulario de Contacto -->
                    <div class="col-md-12 mb-4">
                        <form id="bookForm" action="{{ route('page.send-book') }}" method="POST">
                            @csrf


                            <h4>
                                1. Identificación del consumidor reclamante
                            </h4>

                            <br>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="names" class="form-label">Nombres *</label>
                                    <input type="text" class="form-control" id="names" name="names"
                                        placeholder="Ingresa tus nombres">
                                </div>
                                <div class="col-md-6">
                                    <label for="lastnames" class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" id="lastnames" name="lastnames"
                                        placeholder="Ingresa tus apellidos">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Ingresa tu correo">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Celular *</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Ingresa tu celular">
                                </div>
                            </div>

                            <br>

                            <h4>
                                2. Detalle del reclamo
                            </h4>

                            <br>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Asunto *</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="Asunto de la reclamación">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Reclamación *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Escribe tu reclamo..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-send btn-primary">Enviar ahora</button>
                        </form>
                        <div id="confirmationMessage" class="alert alert-success mt-3 message-contact-none">
                            Gracias por contactarnos. Nos comunicaremos con usted pronto.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('assets/home/js/book.js') }}"></script>
@endsection
