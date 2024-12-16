import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitSelect2,
} from "../../common/js/utils.js";

$(() => {
    var historyTable;

    if ($("#history-table").length) {
        var historyTableEle = $("#history-table");
        var getDataUrl = historyTableEle.data("url");
        historyTable = historyTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "user.name", name: "user.name" },
                { data: "order_date", name: "order_date" },
                { data: "payment_type", name: "payment_type" },
                { data: "amount", name: "amount" },
                {
                    data: "uuid_transaction",
                    name: "uuid_transaction",
                },
                { data: "status", name: "status" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });
    }
});
