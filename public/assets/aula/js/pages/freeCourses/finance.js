import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(function () {
    let containerFinance = $("#container-finance");

    let financeForm = $("#getFinanceForm").validate({
        rules: {
            code: {
                required: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $(form);
            let button = form.find(".btn-save");
            let loadSpinner = button.find(".loadSpinner");

            loadSpinner.toggleClass("active");
            form.find(".btn-save").attr("disabled", "disabled");

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                dataType: "JSON",
                data: form.serialize(),
                success: function (data) {
                    if (data.success) {
                        let html = data.html;
                        containerFinance.html(html);
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: "No se encontraron resultados",
                        });
                        containerFinance.html("");
                    }
                },
                complete: function (data) {
                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").removeAttr("disabled");
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        },
    });
});
