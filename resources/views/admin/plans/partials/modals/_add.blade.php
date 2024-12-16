<div class="modal fade" id="registerPlanModal" tabindex="-1" aria-labelledby="registerPlanModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerPlanModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Registrar plan
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.plans.store') }}" id="registerPlanForm"
                enctype="multipart/form-data" data-validate="" method="POST">
                @csrf

                @include('admin.plans.partials.components._form', ['state' => 'add'])

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-save">
                        Guardar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
