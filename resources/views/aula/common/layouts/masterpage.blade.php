<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('aula.common.partials.head')

<body>

    {{--   WHATSAPP FLOATING BUTTON   --}}

    @php
        $config = getWhatsappConfig();
    @endphp

    <div class="container-social-media">

        <x-common.whatsapp-chat :config="$config" />

        @if ($config->link_group_telegram)
            <a href="{{ $config->link_group_telegram }}" target="_BLANK" class="btn-telegram-pulse">
                <i class="fa-brands fa-telegram"></i>
            </a>
        @endif
    </div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <div class="navbar-bg @yield('navbarClass')"></div>

            @hasSection('navbar-extra-content')
                @yield('navbar-extra-content')
            @else
                @include('aula.common.partials.navbar')
            @endif

            @include('aula.common.partials.sidebar')

            <div class="main-content @yield('main-content-extra-class')">

                <section class="section">

                    @yield('content')

                </section>

            </div>

            @include('aula.common.partials.footer')

        </div>
    </div>



    @include('aula.common.partials.scripts')

</body>

</html>
