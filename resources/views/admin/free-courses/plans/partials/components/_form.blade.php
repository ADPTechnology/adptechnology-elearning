<div class="modal-body">

    {{-- <div class="form-group">
        <label class="form-label">Tipo de generación: </label>
        <div class="selectgroup selectgroup-pills">
            <label class="selectgroup-item">
                <input type="radio" name="generation_type" value="AUTOMATIC" class="selectgroup-input gen_type_radio" checked>
                <span class="selectgroup-button selectgroup-button-icon d-align-items-center px-3">
                    <i class="fa-solid fa-wand-sparkles me-2"></i>
                    AUTOMÁTICO
                </span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="generation_type" value="MANUAL" class="selectgroup-input gen_type_radio">
                <span class="selectgroup-button selectgroup-button-icon d-align-items-center px-3">
                    <i class="fa-solid fa-pencil me-2"></i>
                    MANUAL
                </span>
            </label>
        </div>
    </div>

    <div class="form-row input-code-form-row">

    </div> --}}

    {{-- <div class="form-group">
        <label class="form-label">Tipo de uso: </label>
        <div class="selectgroup selectgroup-pills">
            <label class="selectgroup-item">
                <input type="radio" name="type" value="MULTIPLE" class="selectgroup-input" checked>
                <span class="selectgroup-button selectgroup-button-icon d-align-items-center px-3">
                    <i class="fa-solid fa-dice me-2"></i>
                    USO MÚLTIPLE
                </span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="type" value="SINGULAR" class="selectgroup-input">
                <span class="selectgroup-button selectgroup-button-icon d-align-items-center px-3">
                    <i class="fa-solid fa-dice-one me-2"></i>
                    USO ÚNICO
                </span>
            </label>
        </div>
    </div> --}}

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Titulo *</label>
            <input type="text" name="title" placeholder="Ingrese el titulo" class="form-control">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Precio *</label>
            <input type="text" name="price" placeholder="Ingrese el precio" class="form-control">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-8">
            <label>Duración *</label>
            <input type="text" name="duration" class="form-control duration" placeholder="Ingrese la duración">
        </div>

        <div class="form-group col-4">
            <label class="form-label">Tipo de duración: </label>

            <div class="selectgroup w-100">

                <label class="selectgroup-item">

                    <input type="radio" name="duration_type" value="months" class="selectgroup-input " checked>
                    <span class="selectgroup-button selectgroup-button-icon flex-center px-3" style="height: 42px;">
                        <i class="fa-solid fa-calendar"></i>
                    </span>

                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="duration_type" value="days" class="selectgroup-input">
                    <span class="selectgroup-button selectgroup-button-icon flex-center px-3" style="height: 42px;">
                        <i class="fa-solid fa-calendar-day"></i>
                    </span>
                </label>
            </div>
            <span class="form-label font-italic">(meses / dias)</span>
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Imagen del plan * </label>
            <div>
                <div id="image-preview" class="image-preview">
                    <label for="image-upload" id="image-label">Subir Imagen</label>
                    <input type="file" name="image"
                        id="{{ $state === 'edit' ? 'input-plan-image-edit' : 'input-plan-image-register' }}">
                    <div class="img-holder">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Descripción (opcional)</label>
            <textarea name="description" class="summernote-card-editor"
                id="{{ $state === 'edit' ? 'plan-content-edit' : 'plan-content-register' }}"></textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label class="custom-switch mt-2">
                <input type="checkbox" name="flg_recom" id="register-plan-recom-checkbox" class="custom-switch-input">
                <span class="custom-switch-indicator"></span>
                <span id="txt-register-description-plan-recom" class="custom-switch-description">
                    Registrar como recomendado </span>
            </label>
        </div>
    </div>


    {{-- <div class="form-row select-especify-courses-container">
    </div> --}}


    {{-- <div class="form-row">

        <div class="form-group col-6">
            <label class="custom-switch mt-2">
                <input type="checkbox" name="especify_users" class="custom-switch-input"
                    data-url="{{ route('admin.coupons.create') }}">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">
                    Especificar Usuarios
                </span>
            </label>
        </div>

    </div> --}}

    {{-- <div class="form-row select-especify-users-container">
    </div> --}}

    {{-- <div class="form-row">

        <div class="form-group col-6">
            <label class="custom-switch mt-2">
                <input type="checkbox" name="active" checked class="custom-switch-input coupon-status-checkbox">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description coupon-txt-status-checkbox">
                    Activo
                </span>
            </label>
        </div>

    </div> --}}

</div>
