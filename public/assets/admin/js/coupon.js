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
        code: {
            required: true,
            maxlength: 16,
            minlength: 4,
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
    };

    var couponsTable;

    if ($("#coupons-table").length) {
        var couponsTableEle = $("#coupons-table");
        var getDataUrl = couponsTableEle.data("url");
        couponsTable = couponsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "code", name: "code" },
                { data: "type", name: "type" },
                { data: "amount", name: "amount" },
                { data: "expired_date", name: "expired_date" },
                { data: "type_use", name: "type_use" },
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

        // function loadCleaveCode() {
        //     if ($('.purchase-code').length) {
        //         var cleavePC = new Cleave('.purchase-code', {
        //             delimiter: '-',
        //             blocks: [4, 4, 4, 4],
        //             uppercase: true
        //         });
        //     }
        // }

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

        if ($("#registerCouponForm").length) {
            var formRegister = $("#registerCouponForm");
            var registerSubmitButton = formRegister.find("button[type=submit]");
            setActiveSubmitButton(registerSubmitButton);

            var registerCouponForm = $("#registerCouponForm").validate({
                rules: COUPON_RULES,
                submitHandler: function (form, event) {
                    event.preventDefault();

                    var form = $(form);
                    var button = form.find("button[type=submit][clicked=true]");
                    var loadSpinner = button.find(".loadSpinner");
                    var modal = $("#registerCouponModal");

                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").attr("disabled", "disabled");

                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: form.serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
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

                                couponsTable.draw();

                                registerCouponForm.resetForm();
                                form.trigger("reset");

                                dateInput.val(moment().format("YYYY-MM-DD"));

                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });

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
                "#registerCouponForm input[name=generation_type]",
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
                "#registerCouponForm input[name=especify_courses]",
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
                                    "#registerCouponForm .select_courses_coupons",
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
                "#registerCouponForm input[name=especify_users]",
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
                                    "#registerCouponForm .select_users_coupons",
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

        if ($("#editCouponForm").length) {
            var formEdit = $("#editCouponForm");
            var modalEdit = $("#editCouponModal");

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

            var editCouponForm = $("#editCouponForm").validate({
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
                        data: form.serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                couponsTable.ajax.reload(null, false);

                                editCouponForm.resetForm();
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

            $("html").on("click", ".editCoupon", function () {
                var button = $(this);
                var url = button.data("url");
                var getDataUrl = button.data("send");

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

                        // console.log(data)

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
                                "#editCouponForm .select_courses_coupons",
                                SELECT_CONFIG
                            );
                            let inputSelect = $(
                                "#editCouponForm .select_courses_coupons"
                            );
                            inputSelect.val(data.coursesIds).change();
                        } else {
                            selectCoursesCont.empty();
                        }

                        if (coupon.especify_users == "S") {
                            selectUsersCont.html(data.htmlSelectUsers);
                            InitSelect2(
                                "#editCouponForm .select_users_coupons",
                                SELECT_CONFIG
                            );
                            let inputSelect = $(
                                "#editCouponForm .select_users_coupons"
                            );
                            inputSelect.val(data.usersIds).change();
                        } else {
                            selectUsersCont.empty();
                        }

                        if (coupon.active == "S") {
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
                "#editCouponForm .gen_type_radio",
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

            $("html").on(
                "change",
                "#editCouponForm input[name=especify_courses]",
                function () {
                    var form = $(this).closest("form");
                    var selectContainer = form.find(
                        ".select-especify-courses-container"
                    );

                    if (this.checked) {
                        var url = $(this).attr("data-url");

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
                                    "#editCouponForm .select_courses_coupons",
                                    SELECT_CONFIG
                                );
                                let inputSelect = $(
                                    "#editCouponForm .select_courses_coupons"
                                );
                                inputSelect.val(data.coursesIds).change();
                            },
                            error: function (data) {
                                console.log(data);
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
                "#editCouponForm input[name=especify_users]",
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
                                    "#editCouponForm .select_users_coupons",
                                    SELECT_CONFIG
                                );
                                let inputSelect = $(
                                    "#editCouponForm .select_users_coupons"
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

        $("html").on("click", ".deleteCoupon", function () {
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
                                    couponsTable.ajax.reload(null, false);
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
