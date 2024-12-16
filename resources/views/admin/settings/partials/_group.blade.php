<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-12">
            <label>Correo electrónico *</label>
            <input type="email" name="email" class="form-control content" placeholder="Ingresa el correo electrónico"
                value="{{ $config->email }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Dirección *</label>
            <input type="text" name="address" class="form-control content" placeholder="Ingresa la dirección"
                value="{{ $config->address }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Grupo de WhatsApp *</label>
            <input type="text" name="link_group_wts" class="form-control content"
                placeholder="Ingresa el link del grupo de WhatsApp" value="{{ $config->link_group_wts }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Grupo de Telegram *</label>
            <input type="text" name="link_group_telegram" class="form-control content"
                placeholder="Ingresa el link de Telegram" value="{{ $config->link_group_telegram }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Tik Tok *</label>
            <input type="text" name="tik_tok_link" class="form-control content"
                placeholder="Ingresa el link de Tik Tok" value="{{ $config->tik_tok_link }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Youtube *</label>
            <input type="text" name="youtube_link" class="form-control content"
                placeholder="Ingresa el link de Youtube" value="{{ $config->youtube_link }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Instagram *</label>
            <input type="text" name="instagram_link" class="form-control content"
                placeholder="Ingresa el link de Instagram" value="{{ $config->instagram_link }}">
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-save">
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
