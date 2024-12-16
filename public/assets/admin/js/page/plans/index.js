import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";
import {
    setActiveCheckBoxForResult,
    setActiveCheckbox,
    setActiveSubmitButton,
    InitSelect2,
} from "../../../../common/js/utils.js";

$(() => {
    // STORE

    const PLAN_RULES = {
        title: {
            required: true,
        },
        description: {
            maxlength: 8000,
        },
        price: {
            number: true,
            required: true,
        },
        duration_type: {
            required: true,
        },
        duration: {
            required: true,
            number: true,
            max: 100,
            min: 0,
        },
        image: {
            required: true,
        },
    };

    var plansTable;

    if ($("#plans-table").length) {
        let plansDataTableEle = $("#plans-table");
        let getDataUrl = plansDataTableEle.data("url");
        plansTable = plansDataTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                { data: "price", name: "price" },
                { data: "duration_type", name: "duration_type" },
                { data: "duration", name: "duration" },
                {
                    data: "recom",
                    name: "recom",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
        });

        function getSummernoteConfig(summernoteElement, cardForm) {
            return {
                dialogsInBody: true,
                minHeight: 160,
                disableDragAndDrop: true,
                dialogsFade: true,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["insert", ["link"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                ],
                lang: "es-ES",
                lineHeights: ["1.2", "1.4", "1.6", "1.8", "2.0", "3.0"],
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteElement.val(
                            summernoteElement.summernote("isEmpty")
                                ? ""
                                : contents
                        );
                        cardForm.element(summernoteElement);
                    },
                },
            };
        }

        // ------------ STORE -------------

        if ($("#registerPlanForm").length) {
            let summernoteElement = $("#plan-content-register");

            let planImageRegister = $("#input-plan-image-register");
            planImageRegister.val("");
            planImageRegister.on("change", function () {
                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#registerPlanForm")
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
                    planImageRegister.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            });

            var formRegister = $("#registerPlanForm");
            var registerSubmitButton = formRegister.find("button[type=submit]");
            setActiveSubmitButton(registerSubmitButton);

            var registerPlanForm = $("#registerPlanForm").validate({
                rules: PLAN_RULES,
                errorElement: "label",
                errorClass: "is-invalid",
                validClass: "is-valid",
                ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
                errorPlacement: function (error, element) {
                    error.addClass("error");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.siblings("label"));
                    } else if (element.hasClass("summernote")) {
                        error.insertAfter(element.siblings(".note-editor"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form, event) {
                    event.preventDefault();

                    var form = $(form);
                    var button = form.find("button[type=submit][clicked=true]");
                    var loadSpinner = button.find(".loadSpinner");
                    var modal = $("#registerPlanModal");
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
                                plansTable.draw();

                                registerPlanForm.resetForm();
                                form.trigger("reset");
                                summernoteElement.summernote("reset");

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

            $("#registerPlanModal").on("show.bs.modal", function () {
                summernoteElement.summernote(
                    getSummernoteConfig(summernoteElement, registerPlanForm)
                );
                $(this).find("textarea[name=description]").empty();
            });

            $("#registerPlanModal").on("hidden.bs.modal", function () {
                summernoteElement.summernote("destroy");
                $(this).find("textarea[name=description]").empty();
            });
        }

        // EDIT

        if ($("#editPlanForm").length) {
            let inputCategoryEdit = $("#input-plan-image-edit");
            let summernoteEditElement = $("#plan-content-edit");

            inputCategoryEdit.on("change", function () {
                $("#editPlanForm").validate();

                $("#input-plan-image-edit").rules("add", {
                    required: true,
                });

                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#editPlanForm")
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
                    $(img_holder).html(currentImagePath);
                    inputCategoryEdit.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            });

            var formEdit = $("#editPlanForm");
            var modalEdit = $("#editPlanModal");

            var editPlanForm = $("#editPlanForm").validate({
                rules: PLAN_RULES,
                errorElement: "label",
                errorClass: "is-invalid",
                validClass: "is-valid",
                ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
                errorPlacement: function (error, element) {
                    error.addClass("error");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.siblings("label"));
                    } else if (element.hasClass("summernote")) {
                        error.insertAfter(element.siblings(".note-editor"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form, event) {
                    event.preventDefault();

                    var form = $(form);
                    var loadSpinner = form.find(".loadSpinner");

                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").attr("disabled", "disabled");
                    let img_holder = form.find(".img-holder");

                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                plansTable.ajax.reload(null, false);

                                editPlanForm.resetForm();
                                form.trigger("reset");

                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });
                                summernoteEditElement.summernote("reset");

                                $(img_holder).empty();
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

            $("html").on("click", ".editPlan", function () {
                var button = $(this);
                var url = button.data("url");
                var getDataUrl = button.data("send");
                var modal = $("#editPlanModal");
                $("#input-plan-image-edit").rules("remove", "required");
                formEdit.attr("action", url);

                $.ajax({
                    url: getDataUrl,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {
                        let plan = data.plan;
                        let urlImage = data.url_img;

                        modal
                            .find(".img-holder")
                            .html(
                                '<img class="img-fluid category_img" id="image-plan-edit" src="' +
                                    urlImage +
                                    '"></img>'
                            );
                        modal
                            .find("#input-plan-image-edit")
                            .attr(
                                "data-value",
                                '<img scr="' +
                                    urlImage +
                                    '" class="img-fluid category_img"'
                            );
                        modal.find("#input-plan-image-edit").val("");

                        $.each(plan, function (key, value) {
                            if (key !== "description") {
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

                                setActiveCheckBoxForResult(
                                    formEdit,
                                    "#register-plan-recom-checkbox",
                                    null,
                                    plan.flg_recom
                                );
                            }
                        });

                        modal
                            .find("textarea[name=description]")
                            .val(plan.description);

                        modalEdit.modal("show");
                    },
                    error: function (data) {
                        ToastError.fire();
                    },
                });
            });

            $("#editPlanModal").on("show.bs.modal", function () {
                summernoteEditElement.summernote(
                    getSummernoteConfig(summernoteEditElement, editPlanForm)
                );
            });

            $("#editPlanModal").on("hidden.bs.modal", function () {
                summernoteEditElement.summernote("destroy");
            });
        }

        // DELETE

        $("html").on("click", ".deletePlan", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    plansTable.draw();

                                    Toast.fire({
                                        icon: "success",
                                        text: data.message,
                                    });
                                }
                            },
                            error: function (data) {
                                Toast.fire({
                                    icon: "error",
                                    title: data.message,
                                });
                            },
                        });
                    } else {
                        e.dismiss;
                    }
                },
                function (dismiss) {
                    return false;
                }
            );
        });
    }
});
