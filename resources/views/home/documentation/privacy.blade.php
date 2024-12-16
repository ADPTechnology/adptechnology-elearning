@extends('home.layout.termspage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="w-100 mx-auto mt-6 p-5 bg-terms shadow-md overflow-hidden rounded-lg">

            {!! $config->privacy_policies !!}

            <p>Valuem Finance responde a cualquier duda que se tenga sobre la Política de Privacidad. Envíenos un correo a
                <a href="mailto:{{ $config->email }}">{{ $config->email }}</a> y comuníquese con nosotros.</p>
        </div>
    </div>
@endsection

@section('modals')
@endsection
