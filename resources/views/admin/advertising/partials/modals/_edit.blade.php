<div class="modal fade" id="editAdvertisingModal" tabindex="-1" aria-labelledby="editAdvertisingModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editCouponModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-user-pen"></i>&nbsp;
                        Editar Publicidad
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editAdvertisingForm" method="POST">
                @csrf

                @include('admin.advertising.partials.components._form', ['state' => 'edit'])

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
