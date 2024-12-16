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

            @if (!verifyItemInShoppingSession($plan))
                <button type="button" data-url="{{ route('home.cart.store', $plan) }}"
                    class="btn plan-button plan-add-button">
                    Añadir al carrito
                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
                </button>
            @else
                <button type="button" data-url="{{ route('home.cart.destroy.session', $plan) }}"
                    class="btn plan-button plan-delete-button">
                    Eliminar del carrito
                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1" aria-hidden="true"></i>
                </button>
            @endif

        </div>
    </div>
@endforeach
