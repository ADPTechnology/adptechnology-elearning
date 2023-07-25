@extends('aula2.common.layouts.masterpage')

@section('content')

@php
$current_time = ($current_chapter->progressUsers()->wherePivot('user_id',
Auth::user()->id)->first())->pivot->progress_time;
@endphp

<div class="content global-container" id="chapter-title-head">

    <div class="card page-title-container free-courses">
        <div class="card-header">
            <div class="total-width-container">
                <h4> Cursos libres: {{$course->description}} </h4>
            </div>
        </div>
    </div>

    <input type="hidden" id="url-input-video" data-id='{{$current_chapter->id}}' data-time='{{$current_time}}' value='{{route('aula.freecourse.saveTime',
        $current_chapter)}}'>

    <div class="card-body body-global-container freecourse-view card z-index-2 g-course-flex">

        <div class="video-container">
            <div class="sub-video-container">
                <video id="chapter-video" controls preload='auto' class="video-js" data-setup='{
                    "fluid": true,
                    "playbackRates": [0.5, 1, 1.5, 2]
                }'>
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4">
                    
                    {{-- <source src="{{asset($current_chapter->url_video)}}"> --}}

                </video>

                <div class="video-label-top">
                    {{$current_chapter->courseSection->title}} -
                    {{$current_chapter->title}}
                </div>

                @if($previous_chapter != null)
                <div class="btn-previous-chapter-video btn-navigation-chapter">
                    <a class="inner-btn-previous-chapter" href="" onclick="event.preventDefault(); 
                        document.getElementById('previous-chapter-video-form').submit();">
                        <div class="info-previous-chapter">
                            <div class="extra-txt-nc">
                                Capítulo anterior:
                            </div>
                            <div class="txt-title-nc">
                                {{$previous_chapter->title}}
                            </div>
                        </div> 
                        <i class="fa-solid fa-angles-right fa-flip-horizontal"></i>
                    </a>
                    <form method="POST" action="{{route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $previous_chapter])}}#chapter-title-head"" id="previous-chapter-video-form">
                        @method('PATCH')
                        @csrf
                    </form>
                </div>
                @endif
          
                @if($next_chapter != null)

                <div class="btn-next-chapter-video btn-navigation-chapter">
                    <a class="inner-btn-next-chapter" href="" onclick="event.preventDefault(); 
                        document.getElementById('next-chapter-video-form').submit();">
                        <i class="fa-solid fa-angles-right"></i>
                        <div class="info-next-chapter">
                            <div class="extra-txt-nc">
                                Siguiente capítulo:
                            </div>
                            <div class="txt-title-nc">
                                {{$next_chapter->title}}
                            </div>
                        </div> 
                    </a>
                    <form method="POST" action="{{route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $next_chapter])}}#chapter-title-head"" id="next-chapter-video-form">
                        @method('PATCH')
                        @csrf
                    </form>
                </div>

                @endif
            </div>


            <div class="card page-title-container free-courses">
                <div class="card-header chapter-info-box">
                    <div class="total-width-container chapter-title-info">
                        <h4>{{$current_chapter->title}} </h4>
                    </div>

                    <div class="chapter-desc-info">
                        {{$current_chapter->description}}
                    </div>

                    <span id="show-time"></span>
                </div>
            </div>
        </div>


        <div class="lateral-menu">

            <div class="course-header">
                <div class="img-container">
                    <img src="{{asset($course->url_img)}}" alt="{{$course->description}}">
                </div>

            </div>

            <div class="info-head-freecourse">
                Módulos
            </div>

            <div class="accordion" id="lateral-menu-sections">

                @php
                $chapter_count = 1;
                @endphp

                @foreach ($sections as $key => $section)

                <div class="card section-accordion">

                    <div class="card-header" id="heading-{{$section->id}}">

                        <button class="btn btn-link btn-block text-left button-section-tab" type="button"
                            data-toggle="collapse" data-target="#collapse-{{$section->id}}" aria-expanded="false"
                            aria-controls="collapse-{{$section->id}}">
                            <div class="info-section-txt">
                                <span>
                                    {{$key+1}}. {{$section->title}}
                                </span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>

                            <div class="info-count">
                                {{getNFinishedChapters($section)}}/{{count($section->sectionChapters)}}
                                @php
                                $section_duration = 0;
                                foreach ($section->sectionChapters as $chapter_duration) {
                                $section_duration += $chapter_duration->duration;
                                }
                                @endphp
                                | {{$section_duration}} min
                            </div>
                        </button>

                    </div>

                    <div id="collapse-{{$section->id}}"
                        class="collapse collapse-sections {{getShowSection($current_chapter, $section)}}"
                        aria-labelledby="heading-{{$section->id}}" data-parent="#lateral-menu-sections">

                        @foreach ($section->sectionChapters->sortBy('chapter_order') as $chapter)

                        <div class="card-body @if($chapter->id == $current_chapter->id)active @endif">

                            @if($chapter->id != $current_chapter->id)
                            <form method="POST"
                                action="{{route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $chapter])}}#chapter-title-head">
                                @method('PATCH')
                                @csrf
                                @else
                                <form action=''>
                                    @endif

                                    <button class="btn-next-chapter" type="submit">

                                        <div class="check-chapter-icon" id="check-chapter-icon-{{$chapter->id}}">
                                            @if (getItsChapterFinished($chapter))
                                            <i class="fa-solid fa-circle-check"></i>
                                            @else
                                            <i class="fa-regular fa-circle"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <div class="chapter-title">
                                                <span><i class="fa-solid fa-circle fa-2xs"></i></span> &nbsp;
                                                <span>{{$chapter_count}}. </span>
                                                {{$chapter->title}}
                                            </div>

                                            <div>
                                                <span><i class="fa-solid fa-desktop"></i></span> &nbsp;
                                                {{$chapter->duration}} min.
                                            </div>
                                        </div>

                                    </button>

                                </form>

                        </div>
                        @php
                        $chapter_count++;
                        @endphp
                        @endforeach

                    </div>

                </div>

                @endforeach


            </div>


        </div>

    </div>



</div>

@endsection


@section('extra-script')

<script>

</script>

@endsection