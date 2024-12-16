<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-12">
            <label>Número *</label>
            <input type="number" name="whatsapp_number" class="form-control content" placeholder="Ingresa el número"
                value="{{ $config->whatsapp_number }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Mensaje que se muestra al pasar el cursor *</label>
            <input type="text" name="message_hover_whatsapp" class="form-control content"
                placeholder="Ingresa el mensake" value="{{ $config->message_hover_whatsapp }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Texto de ayuda *</label>
            <input type="text" name="text_whatsapp" class="form-control content" placeholder="Ingresa el texto"
                value="{{ $config->text_whatsapp }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Mensaje (opcional)</label>
            <textarea type="text" name="whatsapp_message" class="form-control content" placeholder="Ingresa el mensaje">{{ $config->whatsapp_message }}</textarea>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-save">
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
