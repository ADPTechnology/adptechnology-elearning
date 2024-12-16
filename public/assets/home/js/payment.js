$(document).ready(function () {
    // var paymentDataForm = $("#paymentDataForm").validate({
    //     rules: {
    //         email: {
    //             email: true,
    //             required: true,
    //         },
    //     },
    //     submitHandler: function (form, event) {
    //         event.preventDefault();

    //         var form = $(form);
    //         var button = form.find(".go-to-step-2");
    //         var loadSpinner = button.find(".loadSpinner");

    //         loadSpinner.toggleClass("active");
    //         button.attr("disabled", "disabled");
    //         var formDataPlus = new FormData(form[0]);
    //         formDataPlus.append("type", "pass");

    //         $.ajax({
    //             method: form.attr("method"),
    //             url: form.attr("action"),
    //             data: formDataPlus,
    //             processData: false,
    //             contentType: false,
    //             dataType: "JSON",
    //             success: function (data) {
    //                 console.log(data);
    //                 let html = data.html;
    //                 $("#container-smart-kr-form").html(html);
    //                 document.getElementById("paymentDataForm").style.display =
    //                     "none";
    //                 document.getElementById(
    //                     "container-smart-kr-form"
    //                 ).style.display = "block";
    //                 document.querySelector(".return-btn").style.display =
    //                     "block";
    //             },
    //             complete: function (data) {
    //                 loadSpinner.toggleClass("active");
    //                 button.removeAttr("disabled");
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //             },
    //         });
    //     },
    // });

    // $(".payment-form").on("click", ".return-btn", function () {
    //     document.getElementById("paymentDataForm").style.display = "block";
    //     document.getElementById("container-smart-kr-form").style.display =
    //         "none";
    //     document.querySelector(".return-btn").style.display = "none";
    // });

    // var infoDataForm = $("#infoDataForm").validate({
    //     rules: {
    //         names: {
    //             required: true,
    //         },
    //         paternal: {
    //             required: true,
    //         },
    //         maternal: {
    //             required: true,
    //         },
    //         password: {
    //             required: true,
    //         },
    //     },
    //     submitHandler: function (form, event) {
    //         event.preventDefault();

    //         var form = $(form);
    //         var button = form.find(".finished-buy");
    //         var loadSpinner = button.find(".loadSpinner");

    //         loadSpinner.toggleClass("active");
    //         button.attr("disabled", "disabled");

    //         var email = $("#paymentDataForm .email").val();
    //         var formDataPlus = new FormData(form[0]);
    //         formDataPlus.append("email", email);

    //         $.ajax({
    //             method: form.attr("method"),
    //             url: form.attr("action"),
    //             data: formDataPlus,
    //             processData: false,
    //             contentType: false,
    //             dataType: "JSON",
    //             success: function (data) {
    //                 paymentDataForm.resetForm();
    //                 infoDataForm.resetForm();
    //                 window.location.href = data.href;
    //             },
    //             complete: function (data) {
    //                 loadSpinner.toggleClass("active");
    //                 // button.removeAttr("disabled");
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //             },
    //         });
    //     },
    // });
});
