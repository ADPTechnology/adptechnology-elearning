<div class="modal-body">

    <div class="form-group">
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

    </div>

    <div class="form-group">
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
    </div>

    <div class="form-row">
        <div class="form-group col-8">
            <label>Cantidad *</label>
            <input type="number" name="amount" class="form-control amount" placeholder="Ingrese la cantidad">
        </div>

        <div class="form-group col-4">
            <label class="form-label">Tipo de descuento: </label>

            <div class="selectgroup w-100">

                <label class="selectgroup-item">

                    <input type="radio" name="amount_type" value="PERCENTAGE" class="selectgroup-input " checked>
                    <span class="selectgroup-button selectgroup-button-icon flex-center px-3" style="height: 42px;">
                        <i class="fa-solid fa-percent"></i>
                    </span>

                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="amount_type" value="MONEY" class="selectgroup-input">
                    <span class="selectgroup-button selectgroup-button-icon flex-center px-3" style="height: 42px;">
                        <b>$</b>
                    </span>
                </label>
            </div>
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Fecha de expiración *</label>
            <input type="text" name="expired_date" class="form-control datepicker">
        </div>

    </div>

    {{-- <div class="form-row">

        <div class="form-group col-6">
            <label class="custom-switch mt-2">
                <input type="checkbox" name="especify_courses" class="custom-switch-input"
                data-url="{{ route('admin.coupons.create') }}">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">
                    Especificar Cursos
                </span>
            </label>
        </div>

    </div> --}}


    <div class="form-row select-especify-courses-container">
    </div>


    <div class="form-row">

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

    </div>

    <div class="form-row select-especify-users-container">
    </div>

    <div class="form-row">

        <div class="form-group col-6">
            <label class="custom-switch mt-2">
                <input type="checkbox" name="active" checked class="custom-switch-input coupon-status-checkbox">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description coupon-txt-status-checkbox">
                    Activo
                </span>
            </label>
        </div>

    </div>

</div>
