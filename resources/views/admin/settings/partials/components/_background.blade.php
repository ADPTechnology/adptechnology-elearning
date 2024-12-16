<div class="action-btn-dropdown-container show top-container-inner-box mb-3">

    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterBackgroundImageModal">
        <i class="fa-solid fa-square-plus"></i> &nbsp; Actualizar fondo
    </button>

</div>

<div class="logo-banner-container d-flex">
    <div class="image-content-container image-sliderLogin">
        <div class="image-container-logo">
            <img src="{{ verifyUrl($config->background_url) }}" alt="">
        </div>
    </div>

    <div class="btn-action-container text-center">

        <span data-url="{{ route('admin.settings.config.destroy.background', $config) }}"
            class="delete-background-img-btn delete-btn">
            <i class="fa-solid fa-trash-can"></i>
        </span>

    </div>
</div>
