<div class="whatsapp-container">
    <span id="whatsapp-btn" class="btn-whatsapp-pulse">
        <i class="fa-brands fa-whatsapp"></i>
    </span>
    <div id="whatsapp-hover-message" class="whatsapp-hover-message">
        <span>{{ $config->message_hover_whatsapp ?? '-' }}</span>
    </div>
    <div id="whatsapp-popup" class="whatsapp-popup">

        <div class="wts-popup-header">
            <button id="close-popup" class="btn-close-popup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="wts-popup-body">

            <div class="wts-body-text">
                {{ $config->text_whatsapp ?? '-' }}
            </div>

            <a href="https://wa.me/{{ $config->whatsapp_number }}?text={{ urlencode($config->whatsapp_message) }}"
                target="_BLANK" id="open-chat-btn" class="btn-open-chat">
                <i class="fa-regular fa-comment"></i>
                &nbsp;
                Chatear
            </a>
        </div>

    </div>
</div>
