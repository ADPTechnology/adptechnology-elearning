<nav class="navbar navbar-expand-lg  navbar-light navbar-payment sticky-top p-0">
    <a href="{{ route('home.index') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img src="{{ asset('assets/common/images/adp-logo-white.png') }}" {{-- src="{{ verifyUrl(getConfig()->logo_url) }}" --}} alt="Valuem Finance"
            style="width: 100%; height: auto; max-height: 75px; object-fit: cover;">
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">

        <div class="navbar-nav m-auto p-4 p-lg-0">

            @auth
                <span class="me-4 my-auto d-flex align-items-center">

                    <span class="user-avatar-container me-3">
                        <img src="{{ verifyUserAvatar(Auth::user()->avatar()) }}" alt="">
                    </span>

                    <b class="text-small">¡Hola, {{ ucwords(mb_strtolower(Auth::user()->full_name, 'UTF-8')) }}!</b>
                </span>

            @endauth


            <div>
                <span style="color: white">
                    <i class="fa-solid fa-lock me-1"></i>
                    Pago 100% seguro
                </span>
            </div>


        </div>



    </div>


</nav>
