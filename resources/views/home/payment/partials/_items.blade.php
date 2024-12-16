@forelse ($items as $item)
    <div class="cart-item">
        <img src="{{ verifyImage($item->buyable->file) }}" alt="{{ $item->buyable->title }}">
        <div class="cart-item-details">
            <p class="cart-item-name">{{ $item->buyable->title }}</p>
            <p class="cart-item-price">${{ $item->buyable->price }}</p>
        </div>
    </div>
@empty
    <p class="text-center">
        <span class="font-italic" style="color: rgb(189, 189, 189)">Carrito de compras vacio.</span>
    </p>
@endforelse
