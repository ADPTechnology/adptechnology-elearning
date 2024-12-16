<div class="modal fade" id="viewTestimonyModal" tabindex="-1" aria-labelledby="viewTestimonyModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="viewTestimonyModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-building"></i>
                        &nbsp;
                        Testimonio:

                        <span class="modal_testimony_participant_name">

                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="" id="testimonyForm" method="POST">
                    @csrf

                    <div class="modal-body">

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmail">Testimonio</label>
                                <div class="input-group">
                                    <textarea name="testimony" class="form-control" placeholder="Escriba el testimonio del participante"></textarea>
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
</div>
