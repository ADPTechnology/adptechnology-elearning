@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>SUSCRIPCIONES</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                {{-- <div class="mb-4">
                    <button class="btn btn-primary" id="btn-register-participant-on-course">
                        <i class="fa-solid fa-plus"></i> &nbsp;
                        Registrar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div> --}}

                <table id="subscriptions-table" class="table table-hover"
                    data-url="{{ route('admin.subscriptions.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Plan</th>
                            <th>Precio de compra</th>
                            <th>Fecha compra</th>
                            <th>Participante</th>
                            <th>Fecha de inicio</th>
                            <th>Fecha de finalización</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.free-courses.partials.modals._register-participant')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/page/subscriptions/index.js') }}"></script>
@endsection
