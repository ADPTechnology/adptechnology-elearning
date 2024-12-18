@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.webinar.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Webinars
                        </a>
                        <span> / {{ $webinar->title }} </span> /
                        <a href="{{ route('aula.webinar.show', $webinar) }}">
                            MENÚ
                        </a> /
                        <a href="{{ route('aula.webinar.onlinelesson.index', $webinar) }}"> CLASE VIRTUAL </a> /

                        {{ $room->description }}

                    </h4>
                </div>
            </div>
        </div>


        <div class="card-body body-global-container card z-index-2 principal-container">
            <div class=" mt-5 course-container online-lessons">

                <iframe class="zoom-meeting" src="{{ $room->url_zoom }}"
                    allow="livestreaming,sharedvideo,chat,raisehand,settings,microphone,camera,desktop,fullscreen,shortcuts,tileview,mute-everyone"
                    frameborder="0" marginwidth="0" marginheight="0" scrolling="no">
                </iframe>

            </div>
        </div>



    </div>
@endsection
