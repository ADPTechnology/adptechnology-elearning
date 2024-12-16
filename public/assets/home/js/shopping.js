$(document).ready(function () {
    let cardPanel = $("#cart-panel");
    let buttonShopping = $("#cart-icon");
    let loadSpinner = buttonShopping.find(".loadSpinner");
    let cardItems = $("#cart-items");
    let containerPlans = $("#container_plans");

    const openShoppingCart = ($context) => {
        $.ajax({
            method: "GET",
            url: cardPanel.attr("data-url"),
            dataType: "JSON",
            success: function (data) {

                console.log(cardPanel.attr("data-url"));

                if (data.success) {
                    cardItems.html(data.html);
                    $("#cart-panel").toggleClass("show");
                }
            },
            complete: function (data) {
                buttonShopping.removeAttr("disabled");
                if ($context == "open") {
                    loadSpinner.toggleClass("active");
                }
            },
            error: function (data) {
                // ToastError.fire();
            },
        });
    };

    buttonShopping.click(function () {
        loadSpinner.toggleClass("active");
        buttonShopping.attr("disabled", "disabled");

        openShoppingCart("open");
    });

    // add to shopping cart

    $("html").on("click", ".plan-add-button", function () {

        let button = $(this);
        let url = button.data("url");
        let loadSpinner = button.find(".loadSpinner");

        loadSpinner.toggleClass("active");
        $(".plan-button").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");

        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            success: function (data) {

                if (data.success) {
                    openShoppingCart("addItem");
                    html = data.html;
                    containerPlans.html(html);
                }
            },
            complete: function (data) {
                loadSpinner.toggleClass("active");
                $(".plan-button").removeAttr("disabled");
            },
            error: function (data) {
                // ToastError.fire();
            },
        });
    });

    // delete to shopping cart for plan

    $("#container_plans").on("click", ".plan-delete-button", function () {
        let button = $(this);
        var url = $(this).data("url");
        $(".plan-button").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");
        $(".end-buy").attr("disabled", "disabled");
        let loadSpinner = button.find(".loadSpinner");
        loadSpinner.toggleClass("active");

        $.ajax({
            type: "DELETE",
            url: url,
            data: {
                type: "plans",
            },
            dataType: "JSON",
            success: function (data) {

                console.log(data);

                if (data.success) {
                    containerPlans.html(data.html);
                    cardItems.html(data.htmlItems);
                }
            },
            complete: function (data) {
                loadSpinner.toggleClass("active");
                $(".plan-button").removeAttr("disabled");
                $(".btn-shopping").removeAttr("disabled");
                $(".end-buy").removeAttr("disabled");
            },
            error: function (result) {},
        });
    });

    // delete to shopping cart for item

    $("#cart-items").on("click", ".cart-item-remove", function () {
        var url = $(this).data("url");
        $(".cart-item-remove").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");
        $(".end-buy").attr("disabled", "disabled");
        $(".plan-button").attr("disabled", "disabled");
        $(".btn-apply-coupon").attr("disabled", "disabled");
        $(".coupon-remove").attr("disabled", "disabled");

        $.ajax({
            type: "DELETE",
            url: url,
            data: {
                type: "items",
            },
            dataType: "JSON",
            success: function (data) {
                if (data.success) {
                    cardItems.html(data.htmlItems);
                    containerPlans.html(data.html);
                }
            },
            complete: function (data) {
                $(".cart-item-remove").removeAttr("disabled");
                $(".btn-shopping").removeAttr("disabled");
                $(".plan-button").removeAttr("disabled");
                $(".end-buy").removeAttr("disabled");
                $(".btn-apply-coupon").removeAttr("disabled");
                $(".coupon-remove").removeAttr("disabled");
            },
            error: function (result) {},
        });
    });

    // login or register not auth

    // var UserSelfEventRegisterForm;
    // var loginModalForm;
    // var registerModalForm;

    // const UserSelfEventRegisterRules = {
    //     email: {
    //         required: true,
    //         email: true,
    //         maxlength: 255,
    //     },
    //     password: {
    //         required: true,
    //         maxlength: 255,
    //     },
    // };

    // if ($("#login_register_modal").length) {
    //     var loginRegisterModal;

    //     $("html").on("click", ".plan-add-session-button", function (e) {
    //         e.preventDefault();

    //         var url = $(this).data("url");
    //         var getDataUrl = $(this).data("send");

    //         $.ajax({
    //             type: "GET",
    //             url: getDataUrl,
    //             dataType: "JSON",
    //             success: function (data) {
    //                 $("#login_register_modal").html(data.html);

    //                 loginRegisterModal = $("#login_register_modal").iziModal({
    //                     overlayClose: false,
    //                     overlayColor: "rgba(0, 0, 0, 0.6)",
    //                 });
    //             },
    //             complete: function (data) {
    //                 UserSelfEventRegisterForm = $(
    //                     "#user-self-event-register-form"
    //                 );
    //                 loginModalForm = $("#form-modal-login");
    //                 registerModalForm = $("#register-form-modal");

    //                 $(".select2").select2();

    //                 if (UserSelfEventRegisterForm.length) {
    //                     UserSelfEventRegisterForm.attr("action", url);

    //                     var userCertRegister =
    //                         UserSelfEventRegisterForm.validate({
    //                             rules: UserSelfEventRegisterRules,
    //                             submitHandler: function (form, event) {
    //                                 event.preventDefault();
    //                                 var form = $(form);
    //                                 var loadSpinner = form.find(".loadSpinner");

    //                                 form.find(
    //                                     ".error-credentials-message"
    //                                 ).addClass("hide");

    //                                 loadSpinner.toggleClass("active");
    //                                 form.find(".btn-save").attr(
    //                                     "disabled",
    //                                     "disabled"
    //                                 );

    //                                 $.ajax({
    //                                     method: form.attr("method"),
    //                                     url: form.attr("action"),
    //                                     data: form.serialize(),
    //                                     dataType: "JSON",
    //                                     success: function (data) {
    //                                         if (data.success) {
    //                                             var modalContent = $(
    //                                                 "#login_register_modal"
    //                                             );
    //                                             var eventsList = $(
    //                                                 "#events-list-container"
    //                                             );
    //                                             modalContent.html(data.html);

    //                                             eventsList.html(
    //                                                 data.htmlEvents
    //                                             );
    //                                         } else {
    //                                             form.find(
    //                                                 ".error-credentials-message"
    //                                             ).removeClass("hide");
    //                                         }
    //                                     },
    //                                     complete: function (data) {
    //                                         userCertRegister.resetForm();
    //                                         form.trigger("reset");

    //                                         loadSpinner.toggleClass("active");
    //                                         form.find(".btn-save").removeAttr(
    //                                             "disabled"
    //                                         );
    //                                     },
    //                                     error: function (data) {
    //                                         console.log(data);
    //                                         ToastError.fire();
    //                                     },
    //                                 });
    //                             },
    //                         });
    //                 }

    //                 if (loginModalForm.length) {
    //                     var userLoginForm = loginModalForm.validate({
    //                         rules: UserSelfEventRegisterRules,
    //                         submitHandler: function (form, event) {
    //                             event.preventDefault();
    //                             var form = $(form);
    //                             var loadSpinner = form.find(".loadSpinner");

    //                             form.closest("section")
    //                                 .find(".error-credentials-message")
    //                                 .addClass("hide");

    //                             loadSpinner.toggleClass("active");
    //                             form.find(".btn-save").attr(
    //                                 "disabled",
    //                                 "disabled"
    //                             );

    //                             $.ajax({
    //                                 method: form.attr("method"),
    //                                 url: form.attr("action"),
    //                                 data: {
    //                                     form: form.serialize(),
    //                                 },
    //                                 dataType: "JSON",
    //                                 success: function (data) {
    //                                     if (data.success) {
    //                                         window.location.reload();
    //                                     } else {
    //                                         form.closest("section")
    //                                             .find(
    //                                                 ".error-credentials-message"
    //                                             )
    //                                             .removeClass("hide");
    //                                     }
    //                                 },
    //                                 complete: function (data) {
    //                                     userLoginForm.resetForm();

    //                                     loadSpinner.toggleClass("active");
    //                                     form.find(".btn-save").removeAttr(
    //                                         "disabled"
    //                                     );
    //                                 },
    //                                 error: function (data) {
    //                                     console.log(data);
    //                                     ToastError.fire();
    //                                 },
    //                             });
    //                         },
    //                     });
    //                 }

    //                 if (registerModalForm.length) {
    //                     // $("#registerCompanySelect").select2({
    //                     //     placeholder: "Selecciona una empresa",
    //                     // });

    //                     // $("#registerMiningUnitsSelect").select2({
    //                     //     placeholder:
    //                     //         "Selecciona una o más unidades mineras",
    //                     //     closeOnSelect: false,
    //                     // });

    //                     var userRegisterModalForm = registerModalForm.validate({
    //                         rules: {
    //                             name: {
    //                                 required: true,
    //                                 maxlength: 255,
    //                             },
    //                             paternal: {
    //                                 required: true,
    //                                 maxlength: 255,
    //                             },
    //                             maternal: {
    //                                 required: true,
    //                                 maxlength: 255,
    //                             },
    //                             email: {
    //                                 required: true,
    //                                 maxlength: 255,
    //                                 email: true,
    //                                 remote: {
    //                                     url: $("#register-form-modal").data(
    //                                         "validate"
    //                                     ),
    //                                     type: "GET",
    //                                     dataType: "JSON",
    //                                     data: {
    //                                         email: function () {
    //                                             return $("#register-form-modal")
    //                                                 .find("input[name=email]")
    //                                                 .val();
    //                                         },
    //                                     },
    //                                 },
    //                             },
    //                             password: {
    //                                 required: true,
    //                                 maxlength: 255,
    //                                 minlength: 8,
    //                                 oneLowercase: true,
    //                                 oneUppercase: true,
    //                                 oneNumber: true,
    //                                 oneSpecialChar: true,
    //                             },
    //                         },
    //                         messages: {
    //                             email: {
    //                                 remote: "Este usuario ya está registrado",
    //                             },
    //                             password: {
    //                                 oneUppercase:
    //                                     "Ingrese al menos una mayúscula",
    //                                 oneLowercase:
    //                                     "Ingrese al menos una minúscula",
    //                                 oneNumber: "Ingrese al menos un número",
    //                                 oneSpecialChar:
    //                                     "Ingrese al menos un caracter especial",
    //                             },
    //                         },
    //                         submitHandler: function (form, event) {
    //                             event.preventDefault();
    //                             var form = $(form);
    //                             var loadSpinner = form.find(".loadSpinner");

    //                             form.closest("section")
    //                                 .find(".error-credentials-message")
    //                                 .addClass("hide");

    //                             loadSpinner.toggleClass("active");
    //                             form.find(".btn-save").attr(
    //                                 "disabled",
    //                                 "disabled"
    //                             );

    //                             $.ajax({
    //                                 method: form.attr("method"),
    //                                 url: form.attr("action"),
    //                                 data: form.serialize(),
    //                                 dataType: "JSON",
    //                                 success: function (data) {
    //                                     if (data.success) {
    //                                         userRegisterModalForm.resetForm();
    //                                         form.trigger("reset");

    //                                         let messageContainer = $(
    //                                             "#modal-register-form-container"
    //                                         );
    //                                         messageContainer.html(data.html);
    //                                     } else {
    //                                         form.closest("section")
    //                                             .find(
    //                                                 ".error-credentials-message"
    //                                             )
    //                                             .removeClass("hide");
    //                                     }
    //                                 },
    //                                 complete: function (data) {
    //                                     userRegisterModalForm.resetForm();

    //                                     loadSpinner.toggleClass("active");
    //                                     form.find(".btn-save").removeAttr(
    //                                         "disabled"
    //                                     );
    //                                 },
    //                                 error: function (data) {
    //                                     console.log(data);
    //                                     ToastError.fire();
    //                                 },
    //                             });
    //                         },
    //                     });
    //                 }

    //                 loginRegisterModal.iziModal("open");
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //                 ToastError.fire();
    //             },
    //         });
    //     });
    // }

    // $("#login_register_modal").on("click", "header a", function (event) {
    //     event.preventDefault();
    //     var index = $(this).index();
    //     $(this).addClass("active").siblings("a").removeClass("active");
    //     $(this)
    //         .parents("div")
    //         .find("section")
    //         .eq(index)
    //         .css("opacity", 0)
    //         .fadeTo(1500, 1);

    //     $(this)
    //         .parents("div")
    //         .find("section")
    //         .eq(index)
    //         .removeClass("hide")
    //         .siblings("section")
    //         .addClass("hide")
    //         .css("opacity", 0);

    //     if ($(this).index() === 0) {
    //         $("#login_register_modal .iziModal-content .icon-close").css(
    //             "background",
    //             "#ddd"
    //         );
    //     } else {
    //         $("#login_register_modal .iziModal-content .icon-close").attr(
    //             "style",
    //             ""
    //         );
    //     }
    // });

    // $(document).on("closed", "#login_register_modal", function (e) {
    //     $("#login_register_modal").iziModal("destroy");
    // });

    // -----

    $(document).click(function (e) {
        if (!$(e.target).closest("#cart-panel, #cart-icon").length) {
            $("#cart-panel").removeClass("show");
        }
    });

    $("#cart-panel").click(function (e) {
        e.stopPropagation();
    });

    // Apply discount

    $("#cart-items").on("click", ".btn-apply-coupon", function () {
        $(".end-buy").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");
        $(".cart-item-remove").attr("disabled", "disabled");
        $(".btn-apply-coupon").attr("disabled", "disabled");
        $(".coupon-remove").attr("disabled", "disabled");
        $(".plan-button").attr("disabled", "disabled");

        let button = $(this);
        let url = button.data("url");
        let loadSpinner = button.find(".loadSpinner");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                coupon: $("#coupon-code").val(),
            },
            dataType: "JSON",
            success: function (data) {
                if (data.success) {
                    html = data.html;
                    let containerBuy = $("#container-shopping-info");
                    containerBuy.html(html);
                }
            },
            complete: function (data) {
                loadSpinner.toggleClass("active");
                $(".plan-button").removeAttr("disabled");
                $(".end-buy").removeAttr("disabled");
                $(".btn-shopping").removeAttr("disabled");
                $(".cart-item-remove").removeAttr("disabled");
                $(".btn-apply-coupon").removeAttr("disabled");
                $(".coupon-remove").removeAttr("disabled");
            },
            error: function (data) {
                // ToastError.fire();
            },
        });
    });

    // Remove discount

    $("#cart-items").on("click", ".coupon-remove", function () {
        $(".plan-button").attr("disabled", "disabled");
        $(".end-buy").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");
        $(".cart-item-remove").attr("disabled", "disabled");
        $(".btn-apply-coupon").attr("disabled", "disabled");
        $(".coupon-remove").attr("disabled", "disabled");

        let button = $(this);
        let url = button.data("url");
        let loadSpinner = button.find(".loadSpinner");

        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.success) {
                    html = data.html;
                    let containerBuy = $("#container-shopping-info");
                    containerBuy.html(html);
                }
            },
            complete: function (data) {
                loadSpinner.toggleClass("active");
                $(".plan-button").removeAttr("disabled");
                $(".end-buy").removeAttr("disabled");
                $(".btn-shopping").removeAttr("disabled");
                $(".cart-item-remove").removeAttr("disabled");
                $(".btn-apply-coupon").removeAttr("disabled");
                $(".coupon-remove").removeAttr("disabled");
            },
            error: function (data) {
                // ToastError.fire();
            },
        });
    });

    // End buy

    $("#cart-items").on("click", ".end-buy", function () {
        $(".plan-button").attr("disabled", "disabled");
        $(".end-buy").attr("disabled", "disabled");
        $(".btn-shopping").attr("disabled", "disabled");
        $(".cart-item-remove").attr("disabled", "disabled");
        $(".btn-apply-coupon").attr("disabled", "disabled");
        $(".coupon-remove").attr("disabled", "disabled");

        let button = $(this);
        // let url = button.data("url");
        let loadSpinner = button.find(".loadSpinner");
        loadSpinner.toggleClass("active");

        // $.ajax({
        //     url: url,
        //     type: "POST",
        //     dataType: "JSON",
        //     success: function (data) {
        //         if (data.success) {
        //             html = data.html;
        //             cardItems.html(html);
        //             window.location.href = data.href;
        //         }
        //     },
        //     complete: function (data) {
        //         loadSpinner.toggleClass("active");
        //         $(".plan-button").removeAttr("disabled");
        //         $(".end-buy").removeAttr("disabled");
        //         $(".btn-shopping").removeAttr("disabled");
        //         $(".cart-item-remove").removeAttr("disabled");
        //         $(".btn-apply-coupon").removeAttr("disabled");
        //         $(".coupon-remove").removeAttr("disabled");
        //     },
        //     error: function (data) {
        //     },
        // });
    });
});
