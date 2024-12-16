@php
    $courseDetail = $course->freecourseDetail;
@endphp

<div class="card-header-information">

    <div class="image-instructor">
        <img src="{{ verifyImage($course->file) }}" alt="{{ $course->description }}">
    </div>

    {{-- <div class="information-basic">
        <div class="full-name-instructor">
            <u>
                <h4>{{ $course->description }}</h4>
            </u>
        </div>
    </div> --}}

</div>

<div class="card-body-information">
    <div class="information-instructor-content">
        {!! $courseDetail->description ?? '-' !!}
    </div>
</div>
