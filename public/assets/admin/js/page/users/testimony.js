import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {
    /*-------------- TESTIMONY MODAL -----------------*/

    if ($("#viewTestimonyModal").length) {
        $("html").on("click", ".btnTestimony", function () {
            let button = $(this);
            let getDataUrl = button.data("send");
            let getUrlUpdate = button.data("url");
            let modal = $("#viewTestimonyModal");

            // $("#testimonyForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let testimony = data.testimony;
                    let fullName = data.full_name;

                    let nameParticipant = modal.find(
                        ".modal_testimony_participant_name"
                    );
                    nameParticipant.html(fullName);

                    modal.find("textarea[name=testimony]").val(testimony);

                    $("#testimonyForm").attr("action", getUrlUpdate);

                    var testimonyForm = $("#testimonyForm").validate({
                        rules: {
                            testimony: {
                                maxlength: 6000,
                                required: true,
                            },
                        },
                        submitHandler: function (form, event) {
                            event.preventDefault();

                            var form = $(form);
                            var loadSpinner = form.find(".loadSpinner");
                            var modal = $("#viewTestimonyModal");

                            loadSpinner.toggleClass("active");
                            form.find(".btn-save").attr("disabled", "disabled");

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

                                        modal
                                            .find("textarea[name=testimony]")
                                            .empty();

                                        Toast.fire({
                                            icon: "success",
                                            text: data.message,
                                        });
                                    } else {
                                        Toast.fire({
                                            icon: "error",
                                            text: data.message,
                                        });
                                    }
                                },
                                complete: function (data) {
                                    form.find(".btn-save").removeAttr(
                                        "disabled"
                                    );
                                    loadSpinner.toggleClass("active");
                                },
                                error: function (data) {
                                    ToastError.fire();
                                },
                            });
                        },
                    });
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    ToastError.fire();
                },
            });
        });
    }
});
