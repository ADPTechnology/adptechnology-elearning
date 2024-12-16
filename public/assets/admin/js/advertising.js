import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitSelect2,
} from "../../common/js/utils.js";

$(() => {
    setActiveCheckbox(".coupon-status-checkbox", ".coupon-txt-status-checkbox");

    const COUPON_RULES = {
        text: {
            required: true,
        },
        code: {
            required: true,
            maxlength: 16,
            minlength: 4,
        },
        plan: {
            required: true,
        },
        type: {
            required: true,
        },
        amount_type: {
            required: true,
        },
        amount: {
            required: true,
            number: true,
            max: 100,
            min: 0,
        },
        expired_date: {
            required: true,
        },
        "courses[]": {
            required: true,
        },
        "users[]": {
            required: true,
        },
        image: {
            required: true,
        },
    };

    var advertisingTable;

    if ($("#advertising-table").length) {
        var advertisingTableEle = $("#advertising-table");
        var getDataUrl = advertisingTableEle.data("url");
        advertisingTable = advertisingTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "text", name: "text" },
                { data: "plan", name: "plan" },
                { data: "coupon.code", name: "coupon.code" },
                { data: "type", name: "type" },
                { data: "amount", name: "amount" },
                { data: "coupon.expired_date", name: "coupon.expired_date" },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });

        var FORM_GROUP_CODE =
            '<div class="form-group col-12">\
                                    <label>Código (Max: 16 caracteres) *</label>\
                                    <input type="text" name="code" class="form-control code" placeholder="Abc2de3FGhijklmn">\
                                </div>';

        var SELECT_CONFIG = {
            closeOnSelect: false,
        };

        $("html").on("click", "input[name=amount_type]", function () {
            var input = $(this);
            var form = input.closest("form");
            var inputQtty = form.find(".amount");

            form.validate();

            if (input.val() == "MONEY") {
                inputQtty.rules("add", { max: 999999 });
            } else if (input.val() == "PERCENTAGE") {
                inputQtty.rules("add", { max: 100 });
            }
        });

        // ------------ STORE -------------

        if ($("#registerAdvertisingForm").length) {
            let advertisingImageRegister = $(
                "#input-advertising-image-register"
            );
            advertisingImageRegister.val("");
            advertisingImageRegister.on("change", function () {
                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#registerAdvertisingForm")
                    .find(".img-holder");
                var img_extension = img_path
                    .substring(img_path.lastIndexOf(".") + 1)
                    .toLowerCase();

                if (
                    img_extension == "jpeg" ||
                    img_extension == "jpg" ||
                    img_extension == "png"
                ) {
                    if (typeof FileReader != "undefined") {
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img/>", {
                                src: e.target.result,
                                class: "img-fluid category_img",
                            }).appendTo(img_holder);
                        };
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    } else {
                        $(img_holder).html(
                            "Este navegador no soporta Lector de Archivos"
                        );
                    }
                } else {
                    $(img_holder).empty();
                    advertisingImageRegister.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            });

            var formRegister = $("#registerAdvertisingForm");
            var registerSubmitButton = formRegister.find("button[type=submit]");
            setActiveSubmitButton(registerSubmitButton);

            var registerAdvertisingForm = $(
                "#registerAdvertisingForm"
            ).validate({
                rules: COUPON_RULES,
                submitHandler: function (form, event) {
                    event.preventDefault();

                    var form = $(form);
                    var button = form.find("button[type=submit][clicked=true]");
                    var loadSpinner = button.find(".loadSpinner");
                    var modal = $("#registerAdvertisingModal");
                    let img_holder = form.find(".img-holder");

                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").attr("disabled", "disabled");

                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                let htmlPlans = data.htmlPlans;

                                var dateInput = form.find(
                                    "input[name=expired_date]"
                                );

                                var componentsClassArray = [
                                    ".input-code-form-row",
                                    ".select-especify-courses-container",
                                    ".select-especify-users-container",
                                ];

                                $.each(
                                    componentsClassArray,
                                    function (_, value) {
                                        let container = form.find(value);
                                        if (container.length) {
                                            container.empty();
                                        }
                                    }
                                );

                                advertisingTable.draw();

                                registerAdvertisingForm.resetForm();
                                form.trigger("reset");

                                dateInput.val(moment().format("YYYY-MM-DD"));

                                $("#container-select-plan").html = htmlPlans;

                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });

                                $(img_holder).empty();

                                modal.modal("hide");
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: data.message,
                                });
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

            $("html").on(
                "click",
                "#registerAdvertisingForm input[name=generation_type]",
                function () {
                    var input = $(this);
                    var form = input.closest("form");

                    var container = form.find(".input-code-form-row");

                    if (input.val() == "MANUAL") {
                        container.html(FORM_GROUP_CODE);
                    } else {
                        container.empty();
                    }
                }
            );

            $("html").on(
                "change",
                "#registerAdvertisingForm input[name=especify_courses]",
                function () {
                    var form = $(this).closest("form");
                    var selectContainer = form.find(
                        ".select-especify-courses-container"
                    );

                    if (this.checked) {
                        var url = $(this).data("url");

                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                part: "courses",
                            },
                            dataType: "JSON",
                            success: function (data) {
                                let html = data.html;
                                selectContainer.html(html);
                                InitSelect2(
                                    "#registerAdvertisingForm .select_courses_coupons",
                                    SELECT_CONFIG
                                );
                            },
                            error: function (data) {
                                ToastError.fire();
                            },
                        });
                    } else {
                        selectContainer.empty();
                    }
                }
            );

            $("html").on(
                "change",
                "#registerAdvertisingForm input[name=especify_users]",
                function () {
                    var form = $(this).closest("form");
                    var selectContainer = form.find(
                        ".select-especify-users-container"
                    );

                    if (this.checked) {
                        var url = $(this).data("url");
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                part: "users",
                            },
                            dataType: "JSON",
                            success: function (data) {
                                let html = data.html;
                                selectContainer.html(html);

                                InitSelect2(
                                    "#registerAdvertisingForm .select_users_coupons",
                                    SELECT_CONFIG
                                );
                            },
                            error: function (data) {
                                ToastError.fire();
                            },
                        });
                    } else {
                        selectContainer.empty();
                    }
                }
            );
        }

        // ---------- EDIT ----------------

        if ($("#editAdvertisingForm").length) {
            let inputCategoryEdit = $("#input-advertising-image-edit");

            inputCategoryEdit.on("change", function () {
                $("#editAdvertisingForm").validate();

                $("#input-advertising-image-edit").rules("add", {
                    required: true,
                });

                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#editAdvertisingForm")
                    .find(".img-holder");
                var currentImagePath = $(this).data("value");
                var img_extension = img_path
                    .substring(img_path.lastIndexOf(".") + 1)
                    .toLowerCase();

                if (
                    img_extension == "jpeg" ||
                    img_extension == "jpg" ||
                    img_extension == "png"
                ) {
                    if (typeof FileReader != "undefined") {
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img/>", {
                                src: e.target.result,
                                class: "img-fluid advertising_img",
                            }).appendTo(img_holder);
                        };
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    } else {
                        $(img_holder).html(
                            "Este navegador no soporta Lector de Archivos"
                        );
                    }
                } else {
                    $(img_holder).html(currentImagePath);
                    inputCategoryEdit.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            });

            var formEdit = $("#editAdvertisingForm");
            var modalEdit = $("#editAdvertisingModal");

            var inputGenType = formEdit.find("input[name=generation_type]");

            var inputCodeContainer = formEdit.find(".input-code-form-row");
            var inputSpecifyCourses = formEdit.find(
                "input[name=especify_courses]"
            );
            var inputSpecifyUsers = formEdit.find("input[name=especify_users]");

            var selectCoursesCont = formEdit.find(
                ".select-especify-courses-container"
            );
            var selectUsersCont = formEdit.find(
                ".select-especify-users-container"
            );

            var editAdvertisingForm = $("#editAdvertisingForm").validate({
                rules: COUPON_RULES,
                submitHandler: function (form, event) {
                    event.preventDefault();

                    var form = $(form);
                    var loadSpinner = form.find(".loadSpinner");

                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").attr("disabled", "disabled");

                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                advertisingTable.ajax.reload(null, false);

                                editAdvertisingForm.resetForm();
                                form.trigger("reset");

                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });

                                modalEdit.modal("hide");
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: data.message,
                                });
                            }
                        },
                        complete: function (data) {
                            form.find(".btn-save").removeAttr("disabled");
                            loadSpinner.toggleClass("active");
                        },
                        error: function (data) {
                            ToastError.fire();
                        },
                    });
                },
            });

            $("html").on("click", ".editAdvertising", function () {
                var button = $(this);
                var url = button.data("url");
                var getDataUrl = button.data("send");
                $("#input-advertising-image-edit").rules("remove", "required");
                inputGenType.prop("checked", true);

                formEdit.attr("action", url);

                $.ajax({
                    url: getDataUrl,
                    type: "GET",
                    data: {
                        part: "all",
                    },
                    dataType: "JSON",
                    success: function (data) {
                        let coupon = data.coupon;
                        let advertising = data.advertising;
                        let urlImage = data.url_img;

                        inputCodeContainer.html(FORM_GROUP_CODE);
                        inputGenType.attr("data-url", data.route);
                        inputSpecifyCourses.attr("data-url", data.route);
                        inputSpecifyUsers.attr("data-url", data.route);

                        $.each(coupon, function (key, value) {
                            let input = formEdit.find(
                                "input[name=" + key + "]:not(:radio)"
                            );
                            let inputRadio = formEdit.find(
                                "input[type=radio][name=" +
                                    key +
                                    '][value="' +
                                    value +
                                    '"]'
                            );

                            if (input) {
                                input.val(value);
                            }
                            if (inputRadio) {
                                inputRadio.prop("checked", true);
                            }
                        });

                        formEdit
                            .find("input[name='text']")
                            .val(advertising.text);

                        // console.log(data)

                        formEdit
                            .find(".img-holder")
                            .html(
                                '<img class="img-fluid advertising_img" id="image-advertising-edit" src="' +
                                    urlImage +
                                    '"></img>'
                            );
                        formEdit
                            .find("#image-advertising-edit")
                            .attr(
                                "data-value",
                                '<img scr="' +
                                    urlImage +
                                    '" class="img-fluid advertising_img"'
                            );
                        formEdit.find("#image-advertising-edit").val("");

                        //

                        inputSpecifyCourses.prop(
                            "checked",
                            coupon.especify_courses == "S"
                        );
                        inputSpecifyUsers.prop(
                            "checked",
                            coupon.especify_users == "S"
                        );

                        if (coupon.especify_courses == "S") {
                            selectCoursesCont.html(data.htmlSelectCourse);
                            InitSelect2(
                                "#editAdvertisingForm .select_courses_coupons",
                                SELECT_CONFIG
                            );
                            let inputSelect = $(
                                "#editAdvertisingForm .select_courses_coupons"
                            );
                            inputSelect.val(data.coursesIds).change();
                        } else {
                            selectCoursesCont.empty();
                        }

                        if (coupon.especify_users == "S") {
                            selectUsersCont.html(data.htmlSelectUsers);
                            InitSelect2(
                                "#editAdvertisingForm .select_users_coupons",
                                SELECT_CONFIG
                            );
                            let inputSelect = $(
                                "#editAdvertisingForm .select_users_coupons"
                            );
                            inputSelect.val(data.usersIds).change();
                        } else {
                            selectUsersCont.empty();
                        }

                        if (advertising.active == "S") {
                            formEdit
                                .find(".coupon-status-checkbox")
                                .prop("checked", true);
                            formEdit
                                .find(".coupon-txt-status-checkbox")
                                .html("Activo");
                        } else {
                            formEdit
                                .find(".coupon-status-checkbox")
                                .prop("checked", false);
                            formEdit
                                .find(".coupon-txt-status-checkbox")
                                .html("Inactivo");
                        }

                        modalEdit.modal("show");
                    },
                    error: function (data) {
                        ToastError.fire();
                    },
                });
            });

            $("html").on(
                "click",
                "#editAdvertisingForm .gen_type_radio",
                function () {
                    var inputGen = $(this);
                    let getDataUrl = inputGen.attr("data-url");
                    var form = inputGen.closest("form");

                    var container = form.find(".input-code-form-row");

                    if (inputGen.val() == "MANUAL") {
                        $.ajax({
                            url: getDataUrl,
                            type: "GET",
                            data: {
                                part: "code",
                            },
                            dataType: "JSON",
                            success: function (data) {
                                container.html(FORM_GROUP_CODE);
                                var inputCode = form.find("input[name=code]");
                                inputCode.val(data.code);
                            },
                        });
                    } else {
                        container.empty();
                    }
                }
            );

            // $("html").on(
            //     "change",
            //     "#editAdvertisingForm input[name=especify_courses]",
            //     function () {
            //         var form = $(this).closest("form");
            //         var selectContainer = form.find(
            //             ".select-especify-courses-container"
            //         );

            //         if (this.checked) {
            //             var url = $(this).attr("data-url");

            //             $.ajax({
            //                 type: "GET",
            //                 url: url,
            //                 data: {
            //                     part: "courses",
            //                 },
            //                 dataType: "JSON",
            //                 success: function (data) {
            //                     let html = data.html;
            //                     selectContainer.html(html);
            //                     InitSelect2(
            //                         "#editAdvertisingForm .select_courses_coupons",
            //                         SELECT_CONFIG
            //                     );
            //                     let inputSelect = $(
            //                         "#editAdvertisingForm .select_courses_coupons"
            //                     );
            //                     inputSelect.val(data.coursesIds).change();
            //                 },
            //                 error: function (data) {
            //                     console.log(data);
            //                     ToastError.fire();
            //                 },
            //             });
            //         } else {
            //             selectContainer.empty();
            //         }
            //     }
            // );

            $("html").on(
                "change",
                "#editAdvertisingForm input[name=especify_users]",
                function () {
                    var form = $(this).closest("form");
                    var selectContainer = form.find(
                        ".select-especify-users-container"
                    );

                    if (this.checked) {
                        var url = $(this).data("url");
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                part: "users",
                            },
                            dataType: "JSON",
                            success: function (data) {
                                console.log(url);
                                let html = data.html;
                                selectContainer.html(html);
                                InitSelect2(
                                    "#editAdvertisingForm .select_users_coupons",
                                    SELECT_CONFIG
                                );
                                let inputSelect = $(
                                    "#editAdvertisingForm .select_users_coupons"
                                );
                                inputSelect.val(data.usersIds).change();
                            },
                            error: function (data) {
                                ToastError.fire();
                            },
                        });
                    } else {
                        selectContainer.empty();
                    }
                }
            );
        }

        // ---------- ELIMINAR -------------

        $("html").on("click", ".deleteAdvertising", function () {
            var url = $(this).data("url");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no podrá ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "¡Sí!",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "#161616",
                confirmButtonColor: "#de1a2b",
                showLoaderOnConfirm: true,
                reverseButtons: true,
                preConfirm: async () => {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    advertisingTable.ajax.reload(null, false);
                                }
                            },
                            error: function (result) {
                                // Swal.showValidationMessage(`
                                //     Request failed: ${result}
                                //   `);
                                ToastError.fire();
                            },
                        });
                        setTimeout(function () {
                            resolve();
                        }, 500);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: "success",
                        text: "¡Registro eliminado!",
                    });
                }
            });
        });
    }
});
