<div class="container-xxl py-5 courses-container">
    <div class="container">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center px-3">
                <div></div>
                Planes
            </h6>
            <h1 class="mb-5">Planes disponibles</h1>
        </div>

        <div class="container mt-2">
            <div class="row justify-content-center" id="container_plans">

                @auth
                    @include('home.freecourses.partials.boxes._plans')
                @endauth

                @guest
                    @include('home.freecourses.partials.boxes._plans_session')
                @endguest

            </div>
        </div>

    </div>
</div>
