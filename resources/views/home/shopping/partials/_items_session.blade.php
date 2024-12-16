@forelse ($items as $item)
    <div class="cart-item">
        <img src="{{ verifyImage($item->file) }}" alt="{{ $item->title }}">
        <div class="cart-item-details">
            <p class="cart-item-name">{{ $item->title }}</p>
            <p class="cart-item-price">${{ $item->price }}</p>
        </div>
        <button type="button" class="cart-item-remove"
            data-url="{{ route('home.cart.destroy.session', $item) }}">&times;</button>
    </div>
@empty
    <p class="text-center">
        <span class="font-italic" style="color: rgb(189, 189, 189)">Carrito de compras vacio.</span>
    </p>
@endforelse

@if ($items->count() >= 1)
    <div id="container-shopping-info">
        @include('home.shopping.partials._buy')
    </div>
@endif
