<!-- Total -->
<div class="total">
    <span>Total:</span>
    <span class="total-price">${{ getAmountDiscount($totalPrice) }}</span>
</div>

@if (getCoupon())
    <div class="coupon">
        <span class="discount">
            {{ getCoupon() }}
            <button type="button" class="coupon-remove" data-url="{{ route('home.cart.order.coupon.destroy') }}">&times;</button>
        </span>
    </div>
@endif

<div class="coupon-section">
    <label for="coupon-code" class="coupon-label">¿Tienes un cupón?</label>
    <div class="input-group mt-2">
        <input type="text" class="form-control" id="coupon-code" placeholder="Introduce tu código de cupón">
        <button type="button" class="btn btn-apply-coupon"
            data-url="{{ route('home.cart.order.coupon') }}">Aplicar</button>
    </div>
</div>

<div class="mt-4">
    <a type="button" class="btn btn-primary w-100 end-buy" href="{{ route('home.payment.checkout') }}">
        Comprar ahora
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
    </a>
</div>
