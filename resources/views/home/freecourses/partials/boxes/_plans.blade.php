@php
    use Carbon\Carbon;

    $hasActiveSubscription = $plans->some(function ($plan) {
        $latestSubscription = $plan->subscription->last();
        return $latestSubscription &&
            Carbon::parse($latestSubscription->end_date, 'America/Lima')->startOfDay() >=
                now('America/Lima')->startOfDay();
    });

@endphp

@foreach ($plans as $plan)
    <div class="col-md-4 mb-4">
        <div class="plan-card {{ $plan->flg_recom ? 'highlighted' : '' }}">

            <div class="plan-image">
                <img src="{{ verifyImage($plan->file) }}" alt="{{ $plan->title }}" class="img-fluid">
            </div>

            <div class="plan-header">{{ $plan->title }} </div>
            <div class="plan-price">${{ $plan->price }}
                <span class="plan-features">x{{ getDetailPlan($plan) }}</span>
            </div>
            <div class="plan-description">
                {!! $plan->description !!}
            </div>
            @php
                $latestSubscription = $plan->subscription->last();
            @endphp

            @if (
                $latestSubscription &&
                    Carbon::parse($latestSubscription->end_date, 'America/Lima')->startOfDay() >= now('America/Lima')->startOfDay())
                <span class="subscription-active">Suscripción activa:
                    {{ $plan->subscription->last()->end_date }}
                </span>
            @elseif ($hasActiveSubscription)
                <span></span>
            @else
                @if ($plan->cart_count === 0 && !$hasActiveSubscription)
                    <button type="button" data-url="{{ route('home.cart.store', $plan) }}"
                        class="btn plan-button plan-add-button">
                        Añadir al carrito
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
                    </button>
                @else
                    @php
                        $cart = $plan->cart->where('user_id', Auth::user()->id)->first();
                    @endphp

                    <button type="button"
                        data-url="{{ route('home.cart.destroy', ['shoppingCart' => $cart, 'course' => $course]) }}"
                        class="btn plan-button plan-delete-button">
                        Eliminar del carrito
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
                    </button>
                @endif
            @endif
        </div>
    </div>
@endforeach
