<div class="me-1">
    <button type="button" id="cart-icon" class="btn btn-shopping py-2 px-lg-4 d-block w-100">
        <i class="fas fa-shopping-cart"></i>
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
    </button>
</div>
@php
    if (activeInformationPage()) {
        $route = route('home.cart.index', ['course' => $course]);
    } else {
        $route = route('home.cart.index.free');
    }
@endphp

<!-- Panel del carrito -->
<div id="cart-panel" data-url="{{ $route }}">
    <h4>Tu Carrito</h4>
    <div id="cart-items">
    </div>
</div>
