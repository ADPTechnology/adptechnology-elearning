@extends('home.layout.paymentpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@section('content')
    <div class="container-success-paid">
        <div class="success-box">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="checkmark" viewBox="0 0 52 52">
                    <path fill="none" stroke="#a0f818" stroke-width="5" d="M6.5 27.5L20.5 41.5L45.5 16.5"></path>
                </svg>
            </div>
            <h1>¡Pago Completado!</h1>
            <p>Gracias por tu compra. Hemos recibido tu pago de forma exitosa.</p>
            <div class="order-details">
                <h2>Detalles del Pedido</h2>
                <p><strong>ID del Pedido:</strong> {{ $order->id }} </p>
                <p><strong>Monto Total:</strong> ${{ $totalAmount }} USD</p>
                <p><strong>Fecha:</strong> {{ $order->created_at }} </p>
            </div>
            @if ($order->new)
                <p class="font-italic mt-4">
                    Hemos detectado que eres un nuevo usuario: Te hemos enviado un email con tus credenciales. No te olvides
                    de cambiar tus
                    datos
                    personales.
                </p>
            @endif
            <a href="{{ route('aula.purchaseHistory.show', ['order' => $order]) }}" class="btn">Ver order de compra</a>
            <a href="https://wa.me/{{ $config->whatsapp_number }}?text={{ urlencode('Hola, he efectuado mi compra.') }}"
                target="_blank" class="btn-order-whatsapp mt-3">
                <i class="fa-brands fa-whatsapp"></i> &nbsp;
                Enviar Boleta por WhatsApp
            </a>
        </div>
    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('assets/home/js/payment.js') }}"></script>
@endsection

@section('modals')
@endsection
