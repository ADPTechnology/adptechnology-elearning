@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>PLANES</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#registerPlanModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <table id="plans-table" class="table table-hover" data-url="{{ route('admin.plans.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Titulo</th>
                            <th>Precio</th>
                            <th>Tipo de duración</th>
                            <th>Duración</th>
                            <th class="text-center">Recomendado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.plans.partials.modals._add')
    @include('admin.plans.partials.modals._edit')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/page/plans/index.js') }}"></script>
@endsection
