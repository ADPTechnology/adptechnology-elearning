@extends('aula.common.layouts.masterpage')

@section('main-content-extra-class', 'fixed-padding')

@section('navbarClass', 'free-course-view')

@section('content')

    <div class="content global-container free-courses" id="chapter-title-head">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4> Cursos: {{ $course->description }} </h4>
                </div>
            </div>
        </div>

        <input type="hidden" id="url-input-video" data-id='{{ $current_chapter->id }}' data-time='{{ $current_time }}'
            data-section='{{ $current_chapter->section_id }}' data-prodcert='{{ $productCertification->id }}'
            value='{{ route('aula.freecourse.saveTime', $current_chapter) }}'>

        <div class="card-body body-global-container freecourse-view card z-index-2 principal-container">


            <div class="video-container">

                @if ($current_chapter->fileVideo)

                    <div class="sub-video-container">
                        <video id="chapter-video" controls preload='auto' class="video-js"
                            data-setup='{
                    "fluid": true,
                    "playbackRates": [0.5, 1, 1.5, 2]
                    }'>
                            <source src="{{ verifyFile($current_chapter->fileVideo) }}">

                        </video>

                        <div class="video-label-top">
                            {{ $current_section->title }} -
                            {{ $current_chapter->title }}
                        </div>

                        @if ($previous_chapter != null)
                            <div class="btn-previous-chapter-video btn-navigation-chapter">
                                <a class="inner-btn-previous-chapter" href=""
                                    onclick="event.preventDefault();
                        document.getElementById('previous-chapter-video-form').submit();">
                                    <div class="info-previous-chapter">
                                        <div class="extra-txt-nc mb-1">
                                            Capítulo anterior:
                                        </div>
                                        <div class="txt-title-nc" style="line-height: 1em;">
                                            {{ $previous_chapter->title }}
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-angles-right fa-flip-horizontal"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $previous_chapter, $course]) }}"
                                    id="previous-chapter-video-form">
                                    @method('PATCH')
                                    @csrf
                                </form>
                            </div>
                        @endif

                        @php
                            $current_evaluation = $current_section->fcEvaluations->isNotEmpty()
                                ? $current_section->fcEvaluations->first() ?? null
                                : null;

                            if ($current_evaluation) {
                                $prodCertificationWithPivot = $current_evaluation->userEvaluations->first() ?? null;
                                $validEvaluation =
                                    $prodCertificationWithPivot &&
                                    $prodCertificationWithPivot->pivot->status === 'finished'
                                        ? true
                                        : false;
                            } else {
                                $validEvaluation = true;
                            }
                        @endphp

                        @if (
                            ($next_chapter != null && !$itsLastChapterOfSection) ||
                                ($next_chapter != null && $itsLastChapterOfSection && $validEvaluation))
                            <div class="btn-next-chapter-video btn-navigation-chapter">
                                <a class="inner-btn-next-chapter" href=""
                                    onclick="event.preventDefault();
                        document.getElementById('next-chapter-video-form').submit();">
                                    <i class="fa-solid fa-angles-right"></i>
                                    <div class="info-next-chapter">
                                        <div class="extra-txt-nc mb-1">
                                            Siguiente capítulo:
                                        </div>
                                        <div class="txt-title-nc" style="line-height: 1em;">
                                            {{ $next_chapter->title }}
                                        </div>
                                    </div>
                                </a>
                                <form method="POST"
                                    action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $next_chapter, $course]) }}"
                                    id="next-chapter-video-form">
                                    @method('PATCH')
                                    @csrf
                                </form>
                            </div>
                        @endif
                    </div>

                @endif

                <div class="card page-title-container free-courses">

                    <div class="card-header chapter-info-box">

                        <ul class="nav nav-tabs tab_center" id="tab_list_freecourse_content" role="tablist">
                            <li class="nav-item" role="presentation">

                                <button class="nav-link active button-module" id="info-tab" data-toggle="tab"
                                    data-target="#info" type="button" role="tab" aria-controls="info"
                                    aria-selected="true">
                                    <i class="fa-solid fa-book me-1"></i>
                                    Información del capítulo
                                </button>
                                {{-- <button class="nav-link active button-module" id="info-tab" data-toggle="tab" data-target="#info"
                                    type="button" role="tab" aria-controls="info" aria-selected="true"> --}}
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link button-module" id="chapter-files-tab" data-toggle="tab"
                                    data-target="#chapter-files" type="button" role="tab" aria-controls="chapter-files"
                                    aria-selected="false">
                                    <i class="fa-regular fa-folder-open me-1"></i>
                                    Recursos del capítulo
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link button-module" id="general-files-tab" data-toggle="tab"
                                    data-target="#general-files" type="button" role="tab" aria-controls="general-files"
                                    aria-selected="false">
                                    <i class="fa-solid fa-folder-tree me-1"></i>
                                    Recursos Genenales
                                </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link button-module" id="financed-tab" data-toggle="tab" role="tab"
                                    data-url="{{ route('aula.freecourse.showFinance') }}" data-target="#general-financed"
                                    aria-controls="general-financed" aria-selected="false" type="button">
                                    <i class="fa-solid fa-chart-column me-1"></i>
                                    Análisis Financiero
                                </button>
                            </li> --}}
                        </ul>


                        <div class="tab-content course_chapter_tab content_raw">
                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                aria-labelledby="info-tab">

                                <div class="mt-3">
                                    <h3 class="m-0">
                                        <b>
                                            {{ $current_chapter->title }}
                                        </b>
                                    </h3>
                                    <div class="little-text date_course_info">
                                        Publicado el
                                        {{ getDateForHummans($current_chapter->created_at) }}
                                    </div>
                                </div>

                                @if ($current_chapter->description)
                                    <div class="mb-4 mt-1">
                                        {{ $current_chapter->description }}
                                    </div>
                                @endif

                                <hr>

                                @if ($current_chapter->content)
                                    <div class="content_container_chapter">
                                        {!! $current_chapter->content !!}
                                    </div>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="chapter-files" role="tabpanel"
                                aria-labelledby="chapter-files-tab">

                                @if ($current_chapter->files->isNotEmpty())

                                    <div class="mb-4 mt-4">
                                        <h4>
                                            Archivos del capítulo:
                                        </h4>
                                    </div>

                                    <div class="resources-cards-container w-100">

                                        @foreach ($current_chapter->files as $file)
                                            <div class="resources-card">
                                                <a href="{{ route('aula.freecourse.files.download', $file) }}">

                                                    <div class="resources-card-body-box">

                                                        @php
                                                            $svg = getFileExtension($file) . '.svg';
                                                        @endphp

                                                        <div class="info-container-resources">
                                                            <div class="resources-image-cont-box p-3">
                                                                <div>
                                                                    <img
                                                                        src="{{ asset('assets/common/images/file-types/' . $svg) }}">
                                                                </div>
                                                            </div>
                                                            <div class="resources-text-cont-box p-3">
                                                                <span class="resources-content-text text-truncate">
                                                                    {{ basename($file->file_path) }}
                                                                </span>
                                                                <i class="resources-date-text date_course_info">
                                                                    {{ ucfirst(getDateForHummans($file->created_at)) }}
                                                                </i>
                                                            </div>
                                                        </div>

                                                        <div class="icon_download_container p-3">
                                                            <div>
                                                                <i class="fa-solid fa-download"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div>


                                            {{-- <h4 class="text-center">
                                        Aún no hay recursos
                                        <img src="{{ asset('assets/common/images/emptyfolder.png') }}" alt="">
                                    </h4> --}}
                                        @endforeach

                                    </div>

                                @endif
                            </div>
                            <div class="tab-pane fade" id="general-files" role="tabpanel"
                                aria-labelledby="general-files-tab">

                                @if ($files->isNotEmpty())

                                    <div class="mb-4 mt-4">
                                        <h4>
                                            Archivos del curso:
                                        </h4>
                                    </div>

                                    <div class="resources-cards-container w-100">

                                        @foreach ($files as $file)
                                            <div class="resources-card">
                                                <a href="{{ route('aula.freecourse.files.download', $file) }}">

                                                    <div class="resources-card-body-box">

                                                        @php
                                                            $svg = getFileExtension($file) . '.svg';
                                                        @endphp

                                                        <div class="info-container-resources">
                                                            <div class="resources-image-cont-box p-3">
                                                                <div>
                                                                    <img
                                                                        src="{{ asset('assets/common/images/file-types/' . $svg) }}">
                                                                </div>
                                                            </div>
                                                            <div class="resources-text-cont-box p-3">
                                                                <span class="resources-content-text text-truncate">
                                                                    {{ basename($file->file_path) }}
                                                                </span>
                                                                <i class="resources-date-text date_course_info">
                                                                    {{ ucfirst(getDateForHummans($file->created_at)) }}
                                                                </i>
                                                            </div>
                                                        </div>

                                                        <div class="icon_download_container p-3">
                                                            <div>
                                                                <i class="fa-solid fa-download"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div>


                                            {{-- <h4 class="text-center">
                                        Aún no hay recursos
                                        <img src="{{ asset('assets/common/images/emptyfolder.png') }}" alt="">
                                    </h4> --}}
                                        @endforeach

                                    </div>

                                @endif
                            </div>
                            {{-- <div class="tab-pane fade" id="general-financed" role="tabpanel"
                                aria-labelledby="general-financed-tab">

                                <div class="mb-4 mt-4">
                                    <h4>
                                        Análisis financiero:
                                    </h4>
                                </div>

                                <div class="w-100" id="container-finance">
                                    <div class="resources-cards-container w-100" id="container-finance">
                                        <div class="m-auto mt-2">
                                            <i class="fa-solid fa-spinner fa-spin fa-xl"></i>
                                        </div>
                                    </div>
                                </div>

                            </div> --}}



                            <span id="show-time"></span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="lateral-menu">

                <div class="course-header">

                    <div class="img-container">
                        <img src="{{ verifyImage($course->file) }}" alt="{{ $course->description }}">
                    </div>

                </div>

                <div class="info-head-freecourse">
                    {{ $course->description }}
                </div>

                <div class="accordion" id="lateral-menu-sections">

                    @php
                        $chapter_count = 1;
                    @endphp

                    @foreach ($sections as $section)
                        <div class="card section-accordion">

                            <div class="card-header" id="heading-{{ $section->id }}">

                                <button class="btn btn-link btn-block text-left button-section-tab" type="button"
                                    data-toggle="collapse" data-target="#collapse-{{ $section->id }}"
                                    aria-expanded="false" aria-controls="collapse-{{ $section->id }}">
                                    <div class="info-section-txt">
                                        <span>
                                            {{ $loop->iteration }}. {{ $section->title }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>

                                    <div class="info-count">
                                        {{ getNFinishedChapters($section, $allProgress) }}/{{ $section->section_chapters_count }}
                                        |
                                        {{ $section->section_chapters_sum_duration +
                                            $section->fcEvaluations->sum(function ($evaluation) {
                                                return $evaluation->exam->exam_time;
                                            }) ??
                                            0 }}
                                        min
                                    </div>
                                </button>

                            </div>

                            <div id="collapse-{{ $section->id }}"
                                class="collapse collapse-sections {{ getShowSection($current_section, $section) }}"
                                aria-labelledby="heading-{{ $section->id }}" data-parent="#lateral-menu-sections">

                                @php
                                    $prev_section = $sections
                                        ->where('section_order', $section->section_order - 1)
                                        ->first();
                                    $prev_evaluation =
                                        $prev_section != null ? $prev_section->fcEvaluations->first() ?? null : null;

                                    $productCertification_id = $productCertification->id;

                                    if ($prev_evaluation) {
                                        $prev_prodCertificationWithPivot =
                                            $prev_evaluation->userEvaluations->first() ?? null;
                                        $evaluation_finished =
                                            $prev_prodCertificationWithPivot &&
                                            $prev_prodCertificationWithPivot->pivot->status === 'finished'
                                                ? true
                                                : false;
                                    } else {
                                        $evaluation_finished = true;
                                    }
                                @endphp

                                {{-- @if (
                                    $prev_section &&
                                        ($prev_section->section_chapters_count != getFinishedChaptersCountBySection($prev_section) ||
                                            !$evaluation_finished))
                                    <div class="invalid-video-start">
                                        <span><i class="fa-solid fa-lock"></i></span>
                                        <p>
                                            Completa el módulo anterior para desbloquear
                                        </p>
                                    </div>
                                @endif --}}

                                @foreach ($section->sectionChapters->sortBy('chapter_order') as $chapter)
                                    <div class="card-body @if ($chapter->id == $current_chapter->id) active @endif">

                                        @if ($chapter->id != $current_chapter->id)
                                            <form method="POST"
                                                action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $chapter, $course]) }}">
                                                @method('PATCH')
                                                @csrf
                                            @else
                                                <form action=''>
                                        @endif

                                        <button class="btn-next-chapter" type="submit">

                                            <div class="check-chapter-icon" id="check-chapter-icon-{{ $chapter->id }}">
                                                @if (getItsChapterFinished($chapter, $allProgress))
                                                    <i class="fa-solid fa-circle-check"></i>
                                                @else
                                                    <i class="fa-regular fa-circle"></i>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="chapter-title">
                                                    <span><i class="fa-solid fa-circle fa-2xs"></i></span> &nbsp;
                                                    <span>{{ $chapter_count }}. </span>
                                                    {{ $chapter->title }}
                                                </div>

                                                <div>
                                                    <span><i class="fa-solid fa-desktop"></i></span> &nbsp;
                                                    {{ $chapter->duration }} min.
                                                </div>
                                            </div>

                                        </button>

                                        </form>

                                    </div>
                                    @php
                                        $chapter_count++;
                                    @endphp
                                @endforeach

                                @if ($section->fcEvaluations->count())
                                    @php
                                        $evaluation = $section->fcEvaluations->first();
                                    @endphp

                                    <div class="card-body card-body-evaluation" onclick="event.preventDefault();">

                                        <button class="no-before" type="">

                                            <div>
                                                <div class="chapter-title">
                                                    <span><i class="fa-solid fa-circle fa-2xs"></i></span> &nbsp;
                                                    {{ $evaluation->title }}
                                                    |
                                                    {{ $evaluation->exam->exam_time ?? 0 }} min.
                                                </div>

                                                <div>
                                                    <span><i class="fa-regular fa-file-lines"></i></span> &nbsp;
                                                    {{ $evaluation->value }}%
                                                </div>
                                                <div>
                                                    {{ $evaluation->description ?? '' }}
                                                </div>

                                                <div class="btn-start-evaluation-container mt-1">

                                                    @include('aula.viewParticipant.freecourses.components._start_evaluation_btn')

                                                </div>

                                            </div>

                                        </button>

                                    </div>
                                @endif


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    {{-- <div class="modal fade" id="fcInstructions-modal" tabindex="-1" aria-labelledby="fcInstructions-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="instructionsModalLabel">
                        CARACTERÍSTICAS DE LA EVALUACIÓN
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="container-modal-ev">

                </div>

                <div class="modal-footer">

                    <form method="POST" class="fcEvaluation-start-form">
                        @csrf
                        <button type="button" class="btn btn-close" data-dismiss="modal">Cerrar</button>

                        <button type="submit" id="btn-start-evaluation" class="btn btn-send">Comenzar
                            Evaluación</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/freeCourses/finance.js') }}"></script>

    <script>
        $(".collapse-sections .card-body-evaluation").each(function() {
            let parentPrev = $(this).prev(".card-body");
            let checkIcon = parentPrev.find("button");
            checkIcon.addClass("no-after");
        });
    </script>
@endsection
