@extends('home.layout.termspage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="w-100 mx-auto mt-6 p-5 bg-terms shadow-md overflow-hidden rounded-lg">

            {!! $config->data_deletion !!}

            {{-- <ol>
                <li>Ingresa a tu cuenta de Facebook y haz clic en “Configuración y privacidad”. Después haz clic en
                    “Configuración”. </li>
                <li>
                    Ve a la sección de “Apps y sitios web”, aquí podrás ver toda tu actividad relacionada a aplicaciones
                    y páginas web registradas en tu cuenta de Facebook.
                    <img src="https://Valuem Finance.com/img/2022-07-16.png" alt="">
                </li>
                <li>
                    Selecciona la casilla correspondiente a Valuem Finance y haz clic en “Eliminar”.
                    <img src="https://Valuem Finance.com/img/2022-07-16 (1).png" alt="">
                </li>
                <li>
                    Selecciona las casillas de acuerdo a tu preferencia y haz clic en “Eliminar”.
                    <img src="https://Valuem Finance.com/img/2022-07-16 (2).png" alt="">
                </li>
                <li>
                    ¡Listo! Eliminaste a Coders Free de tus actividades de manera exitosa.
                </li>
            </ol> --}}
        </div>
    </div>
@endsection

@section('modals')
@endsection
