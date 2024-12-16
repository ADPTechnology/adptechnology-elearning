@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="content global-container">

            <div class="card page-title-container free-courses">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>HISTORIAL DE COMPRAS</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                {{-- <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#registerCouponModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div> --}}

                <table id="history-table" class="table table-hover" data-url="{{ route('aula.purchaseHistory.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Fecha compra</th>
                            <th>Tipo de pago</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection

{{-- @section('modals')
    @include('admin.coupons.partials.modals._store')
    @include('admin.coupons.partials.modals._edit')
@endsection --}}

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/history.js') }}"></script>
@endsection
