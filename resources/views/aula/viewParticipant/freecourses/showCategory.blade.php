@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container free-courses">
            <div class="card-header">
                <div class="total-width-container">
                    <h4> Cursos libres: {{ $category->description }} </h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="courses-cards-container">

                @forelse ($courses as $course)
                    <x-common.course-menu :course="$course" />
                @empty
                    <h4 class="text-center empty-records-message"> Aún no hay cursos en esta categoría </h4>
                @endforelse
            </div>

        </div>

    </div>
@endsection
