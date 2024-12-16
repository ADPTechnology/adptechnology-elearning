@extends('home.layout.paymentpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/home/css/instructor.css') }}">
@endsection

@push('first-head')
    <script type="text/javascript"
        src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
        kr-public-key="{{ config('services.izipay.public_key') }}"
        kr-post-url-success="{{ route('home.payment.completed') }}" ;></script>
    <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon-reset.min.css">
    <script type="text/javascript" src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon.js"></script>
@endpush

@section('content')
    <div class="payment-container container-fluid mt-4">
        <!-- Paso 1: Formulario de pago -->
        <div id="step1" class="payment-form">
            <h2>Ejecutar Pago</h2>

            @auth
                <div class="kr-smart-form" kr-form-token="{{ $formToken }}"></div>
            @endauth

            @guest

                @php
                    $sessionEmail = getSessionEmail();
                @endphp

                @if ($sessionEmail->get('step') === 2 && $sessionEmail->get('email') !== null)
                    <div class="kr-smart-form" kr-form-token="{{ $formToken }}"></div>
                    <form method="POST" action="{{ route('home.payment.verifyUser') }}">
                        @csrf
                        <input type="text" class="form-control type" name="type" value="return" style="display: none">
                        <button type="submit" class="return-btn btn btn-secondary mt-2 prev-step w-100">
                            Regresar
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('home.payment.verifyUser') }}" id="paymentDataForm">
                        @csrf
                        <input type="text" class="form-control type" name="type" value="pass" style="display: none">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="email">Correo Electrónico *</label>
                                <div class="input-group">
                                    <input type="email" id="email" class="form-control email" name="email"
                                        value="{{ $sessionEmail->get('email') }}" placeholder="ejemplo@correo.com">
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-1 go-to-step-2 w-100">
                            Comprar ahora
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </form>
                @endif

            @endguest

        </div>

        <!-- Resumen del carrito que se mantiene en ambas secciones -->
        <div class="payment-summary">
            <h2>Tu carrito está listo</h2>

            @auth
                @include('home.payment.partials._items')
            @endauth
            @guest
                @include('home.payment.partials._items-session')
            @endguest

            <div id="container-shopping-info">
                <div class="subtotal">
                    <span>Subtotal:</span>
                    <span class="subtotal-price">${{ $totalPrice }}</span>
                </div>
                <div class="total">
                    <span>Total:</span>
                    <span class="total-price">${{ getAmountDiscount($totalPrice) }}</span>
                </div>

                @if (getCoupon())
                    <div class="coupon mt-3">
                        <span class="discount">
                            {{ getCoupon() }}
                            <a class="coupon-remove" href="{{ route('home.payment.coupon.destroy') }}">&times;</a>
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('assets/home/js/payment.js') }}"></script>
@endsection

@section('modals')
@endsection
