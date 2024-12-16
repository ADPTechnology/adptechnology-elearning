<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-12">
            <label>Texto *</label>
            <textarea name="data_deletion" class="summernote-card-editor" id="data-deletion-content">
                {{ $config->data_deletion }}
            </textarea>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-save">
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
