document
    .getElementById("whatsapp-btn")
    .addEventListener("mouseover", function () {
        document.getElementById("whatsapp-hover-message").style.display =
            "block";
    });

document
    .getElementById("whatsapp-btn")
    .addEventListener("mouseout", function () {
        document.getElementById("whatsapp-hover-message").style.display =
            "none";
    });

document.getElementById("whatsapp-btn").addEventListener("click", function () {
    document.getElementById("whatsapp-popup").style.display = "block";
    document.getElementById("whatsapp-hover-message").style.display = "none";
});

document.getElementById("close-popup").addEventListener("click", function () {
    document.getElementById("whatsapp-popup").style.display = "none";
});

document.getElementById("open-chat-btn").addEventListener("click", function () {
    document.getElementById("whatsapp-popup").style.display = "none";
});
