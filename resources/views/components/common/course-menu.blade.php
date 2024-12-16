<div class="card course-card">
    <div class="course-img-container">
        <img class="card-img-top course-img" src="{{ verifyImage($course->file) }}" alt="{{ $course->description }}">
        <span class="subscription-date">
            Suscripción activa hasta:
            {{ getLastSubscription($course)->end_date ?? 'No hay suscripción registrada' }}
        </span>
    </div>

    <div class="card-body">

        @if (getActiveSubscription($course))
            <div class="start-button-container freecourses">
                <form method="POST" action="{{ route('aula.freecourse.start', ['course' => $course]) }}">
                    @csrf
                    <button type="submit">
                        Ingresar &nbsp;
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </form>
            </div>
        @else
            <a type="button" href="{{ route('home.information.course', $course) }}" class="btn purchase-button">
                Renovar suscripción &nbsp;
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        @endif

        <div class="course-title-box">
            {{ $course->description }}
        </div>

        <div class="course-info-box">
            <div class="category-box">
                <div>
                    (Categoría)
                </div>
                <div>
                    <i class="fa-solid fa-table-cells-large"></i>
                    {{ $course->courseCategory->description }}
                </div>
            </div>
        </div>

        <div class="course-info-box">
            <div class="hours-box">
                <i class="fa-regular fa-clock"></i>
                Duración: {{ $course->hours }} hrs.
            </div>
        </div>

    </div>

</div>
