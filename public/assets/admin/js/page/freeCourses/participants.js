import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitSelect2,
} from "../../../../common/js/utils.js";

$(() => {
    // InitSelect2("#fc-register-participants-form .select_users_course", {
    //     closeOnSelect: false,
    // });

    InitSelect2("#fc-register-participants-form .select_plan_course", {
        closeOnSelect: true,
    });

    // var registerParticipantsForm = $("#fc-register-participants-form").validate(
    //     {
    //         rules: {
    //             plan: {
    //                 required: true,
    //             },
    //             "users[]": {
    //                 required: true,
    //             },
    //         },
    //         submitHandler: function (form, event) {
    //             event.preventDefault();

    //             var form = $(form);
    //             var button = form.find(".btn-save");
    //             var loadSpinner = button.find(".loadSpinner");
    //             var modal = $("#fc_registerParticipantsModal");

    //             loadSpinner.toggleClass("active");
    //             button.attr("disabled", "disabled");

    //             $.ajax({
    //                 method: form.attr("method"),
    //                 url: form.attr("action"),
    //                 data: form.serialize(),
    //                 dataType: "JSON",
    //                 success: function (data) {
    //                     if (data.success) {
    //                         usersOnCourseTable.draw();
    //                         registerParticipantsForm.resetForm();
    //                         form.trigger("reset");

    //                         Toast.fire({
    //                             icon: "success",
    //                             text: data.message,
    //                         });
    //                         modal.modal("hide");
    //                     } else {
    //                         Toast.fire({
    //                             icon: "error",
    //                             text: data.message,
    //                         });
    //                     }
    //                 },
    //                 complete: function (data) {
    //                     loadSpinner.toggleClass("active");
    //                     form.find(".btn-save").removeAttr("disabled");
    //                 },
    //                 error: function (data) {
    //                     console.log(data);
    //                     ToastError.fire();
    //                 },
    //             });
    //         },
    //     }
    // );

    //

    let usersDataTableEle = $("#users-free-courses-table");
    let getDataUrl = usersDataTableEle.data("url");

    let usersOnCourseTable = usersDataTableEle.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: {
            url: getDataUrl,
            data: function (data) {
                data.statusCertification = $(
                    "#search_from_status_certification_select"
                ).val();
                data.type = $("#search_from_type_select").val();
            },
        },
        columns: [
            { data: "id", name: "id" },
            { data: "email", name: "email" },
            { data: "name", name: "name" },
            {
                data: "productCertifications.flg_finished",
                name: "product_certifications.flg_finished",
            },
            {
                data: "productCertifications.score",
                name: "product_certifications.score",
            },
            // {
            //     data: "enabled",
            //     name: "enabled",
            //     orderable: false,
            //     searchable: false,
            // },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
        ],
        order: [[0, "asc"]],
    });



    $("html").on("change", ".select-filter-users", function () {
        usersOnCourseTable.draw();
    });

    $("html").on("change", ".select-status-course-habilitation", function () {
        usersOnCourseTable.draw();
    });

    // ------------ UPDATE UNLOCK CERT ---------------

    $("html").on(
        "change",
        "input[type=checkbox].unlock_cert_user_checkbox",
        function () {
            var button = $(this);
            var value = button.prop("checked");
            var url = button.data("url");

            button.attr("disabled", "disabled").addClass("disabled");

            $.ajax({
                method: "POST",
                url: url,
                data: {
                    unlock_cert: value,
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {
                        usersOnCourseTable.ajax.reload(null, false);
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: data.message,
                        });
                    }
                },
                complete: function (data) {
                    button
                        .removeAttr("disabled", "disabled")
                        .removeClass("disabled");
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        }
    );

    $("html").on("click", ".reset-free-course-cert", function () {
        var url = $(this).data("url");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "JSON",
                        success: function (result) {
                            if (result.success === true) {
                                usersOnCourseTable.ajax.reload(null, false);

                                Toast.fire({
                                    icon: "success",
                                    text: result.message,
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: result.message,
                                });
                            }
                        },
                        error: function (result) {
                            ToastError.fire();
                            console.log(result);
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

    $("html").on("click", ".delete-btn-user-course", function () {
        var url = $(this).data("url");
        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "JSON",
                        success: function (result) {
                            if (result.success === true) {
                                usersOnCourseTable.ajax.reload(null, false);

                                Toast.fire({
                                    icon: "success",
                                    text: result.message,
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: result.message,
                                });
                            }
                        },
                        error: function (result) {
                            ToastError.fire();
                            console.log(result);
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

    // REGISTER USER TO COURSE

    var innerParticipantsTable;

    $("html").on("change", "#search_from_company_select", function () {
        participantsTable.draw();
    });

    const BUTTON_DISABLED =
        '<button class="btn btn-primary btn-save not-user-allowed" disabled> \
                                     Registrar participantes \
                                     <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                                 </button>';

    const BUTTON_ENABLED =
        '<button type="submit" class="btn btn-primary btn-save"> \
                                 Registrar participantes \
                                 <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                             </button>';

    $("html").on("click", "#btn-register-participant-on-course", function () {

        var modal = $("#fc_registerParticipantsModal");

        if (!$("#fc-course-users-participants-table_wrapper").length) {
            var fcIntParticipantsTable = $(
                "#fc-course-users-participants-table"
            );

            var getDataUrl = fcIntParticipantsTable.data("url");

            innerParticipantsTable = fcIntParticipantsTable.DataTable({
                responsive: true,
                language: DataTableEs,
                serverSide: true,
                processing: true,
                ajax: {
                    url: getDataUrl,
                },
                columns: [
                    {
                        data: "choose",
                        name: "choose",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                    },
                    { data: "id", name: "id" },
                    { data: "email", name: "email" },
                    { data: "name", name: "name" },
                ],
                order: [[1, "asc"]],
            });
        } else {
            innerParticipantsTable.draw();
        }

        modal.modal("show");
    });

    $("html").on("change", ".search_from_company_select", function () {
        innerParticipantsTable.draw();
    });

    $("#fc-course-users-participants-table").on("draw.dt", function () {
        $("#btn-store-participant-container").html(BUTTON_DISABLED);
    });

    let usersCount = 0;
    let planVal = false;
    let buttonContainer = $("#btn-store-participant-container");

    $("html").on("click", ".checkbox-user-label", function () {
        let input = $("#" + $(this).attr("for"));
        let count = 0;

        if (input.is(":checked")) {
            count--;
        } else {
            count++;
        }
        count += $(".checkbox-user-input:checked").length;
        usersCount = count;

        if (usersCount > 0 && planVal) {
            buttonContainer.html(BUTTON_ENABLED);
        } else {
            buttonContainer.html(BUTTON_DISABLED);
        }
    });

    $("html").on("change", ".select_plan_course", function () {
        let selectPlan = $(".select_plan_course").val();
        planVal = selectPlan;

        if (usersCount > 0 && planVal) {
            buttonContainer.html(BUTTON_ENABLED);
        } else {
            buttonContainer.html(BUTTON_DISABLED);
        }
    });

    $("#fc-register-participants-form").on("submit", function (e) {
        e.preventDefault();
        var form = $(this);

        Swal.fire({
            title: "Registrar participantes",
            text: "Confirme antes de continuar",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: form.serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            // console.log(data);
                            if (data.success) {
                                var dataStatus = data.status;
                                innerParticipantsTable.draw();
                                usersOnCourseTable.draw();

                                $("#btn-store-participant-container").html(
                                    BUTTON_DISABLED
                                );

                                $(".select_plan_course")
                                    .val(null)
                                    .trigger("change");
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: data.message,
                                });
                            }
                        },
                        error: function (data) {
                            console.log(data);
                            ToastError.fire();
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
});
