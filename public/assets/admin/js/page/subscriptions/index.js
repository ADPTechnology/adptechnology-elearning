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
    InitSelect2("#fc-register-participants-form .select_plan_course", {
        closeOnSelect: true,
    });

    if ($("#subscriptions-table").length) {
        //
        let subscriptionsDataTableEle = $("#subscriptions-table");
        let getDataUrl = subscriptionsDataTableEle.data("url");
        let subscriptionsTable = subscriptionsDataTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "subscriptable", name: "subscriptable" },
                { data: "total_amount", name: "total_amount" },
                { data: "date_buy", name: "date_buy" },
                { data: "user.name", name: "user.name" },
                { data: "start_time", name: "start_time" },
                { data: "end_time", name: "end_time" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });

        // REGISTER SUBSCRIPTIONS

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

        $("html").on(
            "click",
            "#btn-register-participant-on-course",
            function () {
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
            }
        );

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
                                    subscriptionsTable.draw();

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
                                    subscriptionsTable.draw();

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
