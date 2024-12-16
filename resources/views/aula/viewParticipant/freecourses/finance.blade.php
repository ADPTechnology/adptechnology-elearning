@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>ANÁLISIS FINANCIERO</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">


            <div class="" id="general-financed">

                <div class="row">
                    <div class="w-100">
                        <form action="{{ route('aula.freecourse.showFinance') }}" id="getFinanceForm" method="POST"
                            data-validate="">
                            @csrf

                            <div class="form-group col-8">
                                <label>Código *</label>
                                <input type="text" name="code" class="form-control" placeholder="Ingrese el código">
                            </div>
                            <div class="form-group col-4">
                                <button type="submit" class="btn btn-primary btn-save">
                                    Consultar
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="w-100" id="container-finance">
                    {{--  --}}
                </div>

            </div>

        </div>

    </div>
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/freeCourses/finance.js') }}"></script>
@endsection
