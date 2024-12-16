@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>CURSOS</h4>
                </div>
            </div>
        </div>

        {{-- <div class="card-body">
            @if (getActiveSubscription())
                <span class="subscription-active">
                    Tienes una suscripción activa hasta: {{ getLastSubscription()->end_date }}
                </span>
            @else
                <span class="subscription-expired">
                    Tu suscripción ya ha vencido: {{ getLastSubscription()->end_date }}
                </span>
            @endif
        </div> --}}

        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="course-category-container">

                @foreach ($categories as $category)
                    <div class="category-card">
                        <img src="{{ verifyImage($category->file) }}" alt="{{ $category->description }}">
                        <div class="category-title-container">
                            <div class="box-title">
                                <div class="upper-text">
                                    CURSOS DE
                                </div>
                                <div class="title">
                                    {{ $category->description }}
                                </div>
                                <a href="{{ route('aula.freecourse.showCategory', $category) }}" class="category-start">
                                    Ver más
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>


            {{-- - CURSOS RECOMENTADOS  - --}}


            <div class="card page-title-container sub-content">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4> <i class="fa-solid fa-star" style="color: rgb(255, 199, 15)"></i>
                            &nbsp; RECOMENDADOS
                        </h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <a type="button" href="{{ route('aula.freecourse.finance') }}" class="btn finance-button mb-4">
                            Análisis Financiero &nbsp;
                            <i class="fa-solid fa-chart-column"></i>
                        </a>
                    </div>
                </div>

            </div>

            <div class="courses-cards-container">


                @forelse ($recomendedCourses as $recomendedCourse)
                    <x-common.course-menu :course="$recomendedCourse" />
                @empty
                    <h4 class="text-center empty-records-message"> No hay cursos recomendados aún </h4>
                @endforelse

            </div>


            {{-- - SIGUIENDO  - --}}

            <div class="card page-title-container sub-content">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>SIGUIENDO</h4>
                    </div>
                </div>
            </div>

            @forelse ($pendingCourses as $pendingCourse)
                <div class="courses-cards-container">
                    <x-common.course-menu :course="$pendingCourse" />
                </div>
            @empty

                <div class="courses-cards-container">

                    <div class="info-empty-section">
                        <h4> Aquí aparecerán los cursos que has iniciado </h4>
                        <i class="fa-solid fa-flag-checkered"></i>
                    </div>

                </div>
            @endforelse




            {{-- - CURSOS FINALIZADOS  - --}}


            <div class="card page-title-container sub-content">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURSOS FINALIZADOS</h4>
                    </div>
                </div>
            </div>

            <div class="courses-cards-container">

                @forelse ($finishedCourses as $finishedCourse)
                    <x-common.course-menu :course="$finishedCourse" />
                @empty
                    <div class="info-empty-section">
                        <h4> Aún no se ha finalizado ningún curso </h4>
                        <i class="fa-solid fa-hourglass-start"></i>
                    </div>
                @endforelse

            </div>

        </div>

    </div>
@endsection
