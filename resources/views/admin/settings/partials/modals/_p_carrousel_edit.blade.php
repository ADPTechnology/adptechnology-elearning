<div class="modal fade" id="editPrincipalBannerModal" tabindex="-1" aria-labelledby="editPrincipalBannerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editPrincipalBannerModalTitle">
                    <div class="title-header-show mt-0">
                        <i class="fa-solid fa-pen-to-square"></i>&nbsp;
                        <span>
                            Editar banner
                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> &times; </span>
                </button>
            </div>

            <form action="" id="editPrincipalBannerForm" method="POST" enctype="multipart/form-data">

                @include('admin.settings.partials.components.p_carrousel_form')

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
