@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CUPONES DE DESCUENTO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#registerCouponModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <table id="coupons-table" class="table table-hover" data-url="{{ route('admin.coupons.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha de expiración</th>
                            <th>Uso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.coupons.partials.modals._store')
    @include('admin.coupons.partials.modals._edit')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/coupon.js') }}"></script>
@endsection
