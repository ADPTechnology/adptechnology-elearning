@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="content global-container">

            <div class="card page-title-container free-courses">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>VISTA DE LA COMPRA</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="container">
                    <!-- Contenedor de la orden -->
                    <div class="order-container">
                        <!-- Encabezado de la orden -->
                        <div class="order-header">
                            <div class="order-title">Orden #{{ $order->id }}</div>
                            <div class="order-info">
                                <p>Fecha: {{ $order->order_date }}</p>
                                <p>Estado: {{ $order->status }} </p>
                                <p>Método de pago: {{ $order->payment_type }}</p>
                                <p>UUID Transacción: {{ $order->uuid_transaction ?? '-' }}</p>
                                <p>Cupón de descuento: {{ getCouponToOrder($order) }}</p>
                            </div>
                        </div>

                        <!-- Lista de items -->
                        <div class="order-items">

                            @foreach ($order->products as $product)
                                <div class="order-item">
                                    <div class="item-details">
                                        <img src="{{ verifyImage($product->orderable->file) }}"
                                            alt="{{ $product->orderable->title }}" class="item-image">
                                        <div>
                                            <span class="item-name">{{ $product->orderable->title }}</span><br>
                                            <span class="item-quantity">Cantidad: {{ $product->quantity }}</span><br>
                                        </div>
                                    </div>
                                    <div class="item-price">${{ $product->unit_price }}</div>
                                </div>
                            @endforeach

                        </div>

                        <!-- Subtotal -->
                        <div class="subtotal">
                            <span>Subtotal:</span>
                            <span class="subtotal-price">${{ $total }}</span>
                        </div>

                        <!-- Total -->
                        <div class="total">
                            <span>Total:</span>
                            <span class="total-price">${{ getAmountToOrder($total, $order) }}</span>
                        </div>

                        <!-- Botón de regreso -->
                        {{-- <div class="mt-4">
                            <a href="#" class="btn-back">Volver a las órdenes</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('modals')
    @include('admin.coupons.partials.modals._store')
    @include('admin.coupons.partials.modals._edit')
@endsection --}}

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/history.js') }}"></script>
@endsection
