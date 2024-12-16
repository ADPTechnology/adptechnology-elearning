<div class="container-xxl py-5 courses-container">
    <div class="container">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s"
            style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
            <h6 class="section-title text-center px-3">
                <div></div>
                <i class="fa-solid fa-book text-secondary fa-xl" aria-hidden="true"></i>
            </h6>
            <h1 class="">Cursos disponibles</h1>
        </div>

        @php
            $delay = 0.1;
        @endphp

        @forelse ($freeCourses as $freeCourse)
            @php
                $openRow = ($loop->iteration - 1) % 3 == 0 || $loop->first;
                $closeRow = $loop->iteration % 3 == 0 || $loop->last;

                $participants_ids = $freeCourse->userProductCertifications()->pluck('user_id')->toArray();

                if (Auth::check()) {
                    $pendingParticipantToCourse = $freeCourse
                        ->productCertifications()
                        ->select('status')
                        ->where('user_id', Auth::user()->id)
                        ->first();
                }
            @endphp

            @if ($openRow)
                <div class="row g-4 justify-content-center">
            @endif

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $delay }}s">
                <div class="course-item bg-light-primary overflow-hidden" style="border-radius: 2em;">
                    <div class="position-relative image-inner-container overflow-hidden">
                        <a href="{{ route('home.information.course', ['course' => $freeCourse]) }}">
                            <img class="img-fluid" src="{{ verifyImage($freeCourse->file) }}" alt="">
                        </a>
                        <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-4">
                            {{-- @if (Auth::check() && in_array(Auth::user()->id, $participants_ids) && $pendingParticipantToCourse->status !== 'pending')
                                <a href="{{ route('aula.freecourse.showCategory', ['category' => $freeCourse->courseCategory]) }}"
                                    class="flex-shrink-0 btn btn-sm btn-success text-white px-4 py-2"
                                    style="border-radius: 30px;">
                                    <i class="fa-regular fa-circle-check"></i> &nbsp;
                                    Ir al E-learning
                                </a>
                            @elseif (Auth::check() && in_array(Auth::user()->id, $participants_ids) && $pendingParticipantToCourse->status === 'pending')
                                <span data-url="" data-send=""
                                    class="flex-shrink-0 btn btn-sm btn-warning text-white px-4 py-2"
                                    style="border-radius: 30px;">
                                    <i class="fa-solid fa-file-signature"></i> &nbsp;
                                    Solicitud pendiente
                                </span>
                            @else
                                <a href="#"
                                    @auth data-url="{{ route('home.certifications.requestRegistrationCourse', $freeCourse) }}" @endauth
                                    data-send="{{ route('home.getRegisterModalContent', ['place' => 'external']) }}"
                                    class="flex-shrink-0 btn btn-sm btn-primary px-4 py-2 event_btn_register"
                                    style="border-radius: 30px;">
                                    <i class="fa-solid fa-file-signature"></i> &nbsp;
                                    Solicitar inscripción
                                </a>
                            @endif --}}

                        </div>

                    </div>
                    <div class="text-center p-4 pb-0">

                        <h5 class="mb-4">
                            {{ $freeCourse->description }}
                        </h5>
                    </div>

                    <div class="information w-100 d-flex">
                        <a class="btn btn-primary w-100"
                            href="{{ route('home.information.course', ['course' => $freeCourse]) }}">
                            <i class="fa-solid fa-circle-question"></i>
                            &nbsp; Más información
                        </a>
                    </div>

                    <div class="d-flex border-top">
                        <small class="flex-fill text-center border-end py-2"><i
                                class="fa fa-clock text-primary me-2"></i>
                            {{ $freeCourse->hours }} hrs.
                        </small>
                        <small class="flex-fill text-center py-2">
                            <i class="fa-solid fa-tv text-primary me-2"></i>
                            {{ $freeCourse->course_chapters_count }}
                            Capítulos
                        </small>
                    </div>
                </div>
            </div>

            @if ($closeRow)
    </div>
    @endif
    @php
        $delay += 0.2;
    @endphp
@empty

    <h4 class="text-center empty-records-message"> No hay cursos que mostrar </h4>
    @endforelse

</div>
</div>
