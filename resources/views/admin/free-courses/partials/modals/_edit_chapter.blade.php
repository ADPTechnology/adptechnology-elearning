<div class="modal fade" id="editChapterModal" tabindex="-1" aria-labelledby="editChapterModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editSectionModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Editar capítulo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editChapterForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Título *</label>
                            <div class="input-group">
                                <input type="text" name="title" id="title" class="form-control title"
                                    placeholder="Ingrese el título del capítulo">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Descripción * (Max: 500 caracteres)</label>
                            <div class="input-group">
                                <textarea name="description" id="description-text-area-edit" class="form-control edit"
                                    placeholder="Ingrese la descripción del capítulo"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="selectOrder">Orden *</label>
                            <div class="input-group">
                                <select name="chapter_order" class="form-control select2" id="editOrderSelectChapter">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="input-file-chapter-edit">Video *</label>
                            <div class="input-group">
                                <input type="file" id="input-file-chapter-edit" class="input-chapter-video"
                                    name="file" style="display: none;">
                                <label for="input-file-chapter-edit" id="label-input-file-chapter-edit"
                                    class="btn btn-primary custom-file-label">Seleccionar
                                    video</label>
                                <span id="file-name" class="file-name">Ningún archivo seleccionado</span>
                            </div>
                        </div>
                    </div>

                    <div id="progress-bar-container-edit" class="progress mt-3" style="display: none;">
                        <div id="progress-bar-edit" class="progress-bar progress-bar-striped" role="progressbar"
                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btn-chapter-update-submit" class="btn btn-primary btn-save">
                        Guardar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
