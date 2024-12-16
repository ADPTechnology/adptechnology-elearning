$(() => {
    let messageSuccessfully = $("#confirmationMessage");

    var bookForm = $("#bookForm").validate({
        rules: {
            names: {
                required: true,
                maxlength: 255,
            },
            lastnames: {
                required: true,
                maxlength: 255,
            },
            email: {
                required: true,
                maxlength: 255,
                email: true,
            },
            phone: {
                required: true,
                maxlength: 11,
            },
            subject: {
                required: true,
                maxlength: 200,
            },
            message: {
                required: true,
                maxlength: 4000,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var form = $(form);

            form.find(".btn-send").attr("disabled", "disabled");

            var formData = new FormData(form[0]);

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: formData,
                processData: false,
                contentType: false,
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {
                        bookForm.resetForm();
                        form.trigger("reset");

                        messageSuccessfully
                            .removeClass("message-contact-none")
                            .addClass("message-contact-active");

                        setTimeout(function () {
                            messageSuccessfully.addClass("fade-out");
                            setTimeout(function () {
                                messageSuccessfully
                                    .addClass("message-contact-none")
                                    .removeClass("message-contact-active")
                                    .removeClass("fade-out");
                                form.find(".btn-send").removeAttr("disabled");
                            }, 2000);
                        }, 3000);
                    }
                },
                complete: function (data) {
                    // loadSpinner.toggleClass("active");
                },
                error: function (data) {
                    console.log(data);
                    // ToastError.fire();
                },
            });
        },
    });
});
