<div class="modal fade" id="RegisterBackgroundImageModal" tabindex="-1" aria-labelledby="RegisterBackgroundImageModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="RegisterBackgroundImageModalTitle">
                    <div class="title-header-show mt-0">
                        <i class="fa-solid fa-plus"></i>&nbsp;
                        <span>
                            Añadir fondo
                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> &times; </span>
                </button>
            </div>

            <form action="{{ route('admin.settings.config.update.background', $config) }}" id="registerBackgroundImageForm" method="POST" enctype="multipart/form-data">

                <div class="modal-body">


                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-8">
                            <label>Imagen * </label>
                            <div>
                                <div id="image-preview" class="image-preview banner-container-image">
                                    <label for="image-upload" id="image-label">Subir Imagen</label>
                                    <input type="file" name="image" id="input-background-image-register" class="input-background-image">
                                    <div class="img-holder">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

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
