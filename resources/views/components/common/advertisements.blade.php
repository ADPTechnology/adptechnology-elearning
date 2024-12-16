@if ($advertisements->count() >= 1)

    <div class="modal fade promo-modal" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoModalLabel">¡Oferta Especial!</h5>
                    <span class="btn-close-advertising" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-sharp fa-solid fa-circle-xmark"></i>
                    </span>
                </div>

                <div class="advertising modal-body">
                    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($advertisements as $advertising)
                                <div class="carousel-item {{ $i === 1 ? 'active' : 'inactive' }}">

                                    <p class="time-left-badge"
                                        data-expired-date="{{ \Carbon\Carbon::parse($advertising->coupon->expired_date)->endOfDay()->format('Y-m-d H:i:s') }}">
                                        <span class="countdown"></span>
                                    </p>

                                    <br>

                                    <img src="{{ verifyFile($advertising->file) }}" alt="{{ $advertising->text }}"
                                        class="img-fluid">
                                    <h4>{{ $advertising->text }}</h4>
                                    <p class="price">
                                        <span class="old-price">${{ $advertising->plan->price }}</span>
                                        <span class="new-price">
                                            ${{ getPriceWithCouponInAd($advertising) }}
                                            <span class="plan-features">x{{ getDetailPlan($advertising->plan) }}</span>
                                        </span>
                                    </p>
                                    <a href="{{ route('home.payment.getAdvertising', ['advertising' => $advertising->id, 'couponCode' => $advertising->coupon->code]) }}"
                                        class="btn btn-primary">
                                        Obtener ahora
                                    </a>
                                </div>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </div>
                        <!-- Controles del Carousel -->

                        @if ($advertisements->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif

                        <!-- Indicadores del Carousel -->
                        <div class="advertising carousel-indicators">
                            @php
                                $e = 1;
                            @endphp
                            @for ($x = 0; $x < $advertisements->count(); $x++)
                                <button type="button" data-bs-target="#promoCarousel"
                                    data-bs-slide-to="{{ $x }}"
                                    class="{{ $e === 1 ? 'active' : 'inactive' }}" aria-current="true"></button>
                                @php
                                    $e++;
                                @endphp
                            @endfor

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
