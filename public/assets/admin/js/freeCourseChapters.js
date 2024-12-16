import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    initImageChange,
    setActiveSubmitButton,
} from "../../common/js/utils.js";

$(() => {
    const FILEPOND_CONFIG_FILES = {
        allowMultiple: false,
        name: "file",
        dropValidation: true,
        storeAsFile: true,
        labelIdle:
            '<i class="fa-solid fa-paste me-2"></i> \
                            Arrastra y suelta un vídeo \
                            <span class="filepond--label-action"> Explora </span>',
        checkValidity: true,
        maxFileSize: "350MB",
        maxTotalFileSize: "350MB",
        allowFileSizeValidation: true,
        acceptedFileTypes: ["video/*"],
        labelMaxTotalFileSizeExceeded:
            "El tamaño total de los archivos es demasiado grande",
        labelMaxTotalFileSize: "El tamaño total es {filesize}",
        labelMaxFileSize: "Tamaño máximo del archivo es {filesize}",
        labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
        labelMaxFileSizeExceeded: "El archivo es demasiado grande",
        labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
        fileValidateTypeLabelExpectedTypes: "Se espera {lastType}",
        credits: false,
    };

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    $(".input-chapter-video-container-edit").filepond(FILEPOND_CONFIG_FILES);

    /* ---------- CHAPTERS -----------*/

    /* ------- CHAPTERS TABLE ---------*/

    function chapterTable(ele, lang, url) {
        var chaptersTable = ele.DataTable({
            responsive: true,
            language: lang,
            serverSide: true,
            processing: true,
            ajax: {
                url: url,
                data: {
                    type: "table",
                },
            },
            order: [[3, "asc"]],
            columns: [
                { data: "title", name: "title", className: "text-bold" },
                { data: "description", name: "description" },
                { data: "duration", name: "duration" },
                { data: "chapter_order", name: "chapter_order" },
                {
                    data: "view",
                    name: "view",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
                {
                    data: "content",
                    name: "content",
                    orderable: false,
                    searchable: false,
                    className: "action-with text-center",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "action-with",
                },
            ],
            dom: "rtip",
        });
    }

    /* ----- SET ACTIVE -----*/

    $("html").on("click", ".course-section-box .title-container", function () {
        var sectionBox = $(this).closest(".course-section-box");

        if (!sectionBox.hasClass("active")) {
            sectionBox.addClass("active").attr("data-active", "active");
            sectionBox.siblings().removeClass("active").attr("data-active", "");

            var url = sectionBox.data("table");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: "html",
                },
                dataType: "JSON",
                success: function (data) {
                    var chaptersBox = $("#chapters-list-container");
                    var topTableInfo = $("#top-chapter-table-title-info");

                    topTableInfo.html(
                        '<span class="text-bold"> de: </span> \
                                    <span class="title-chapter-top-table">' +
                            data.title +
                            "</span>"
                    );
                    chaptersBox.html(data.html);

                    var chaptersTableEle = $("#freeCourses-chapters-table");
                    chapterTable(chaptersTableEle, DataTableEs, url);
                },
                error: function (result) {
                    // console.log(result);
                    Toast.fire({
                        icon: "error",
                        title: "¡Ocurrió un error inesperado!",
                    });
                },
            });
        }
    });

    /*----- STORE DATA -------*/

    $(".store-free-courses-chapter-input").filepond(FILEPOND_CONFIG_FILES);

    /*-------  REGISTER  ------*/

    document
        .getElementById("input-file-chapter")
        .addEventListener("change", function () {
            const fileName = this.files[0]
                ? this.files[0].name
                : "Ningún archivo seleccionado";
            document.getElementById("label-input-file-chapter").textContent =
                fileName;
        });

    var registerChapterForm = $("#registerChapterForm").validate({
        rules: {
            title: { required: true, maxlength: 100 },
            description: { required: true, maxlength: 500 },
            file: { required: true },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $(form);
            var button = $("#btn-register-chapter-modal");
            var url = button.data("url");
            var loadSpinner = form.find(".loadSpinner");
            var modal = $("#registerChapterModal");

            loadSpinner.toggleClass("active");
            form.find(".btn-save").attr("disabled", "disabled");

            var fileInputStore = $("#input-file-chapter")[0];
            var title = form.find("#title").val();
            var description = form
                .find("#description-text-area-register")
                .val();
            var file = fileInputStore.files[0];
            var formData = new FormData();
            formData.append("file", file);
            formData.append("title", title);
            formData.append("description", description);

            $.ajax({
                method: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new XMLHttpRequest();

                    $("#progress-bar-container").show();

                    xhr.upload.addEventListener(
                        "progress",
                        function (e) {
                            if (e.lengthComputable) {
                                var percentComplete = Math.round(
                                    (e.loaded / e.total) * 100
                                );
                                $("#progress-bar").css(
                                    "width",
                                    percentComplete + "%"
                                );

                                $("#progress-bar").text(percentComplete + "%");

                                $("#progress-bar").attr(
                                    "aria-valuenow",
                                    percentComplete
                                );
                            }
                        },
                        false
                    );

                    return xhr;
                },
                success: function (data) {
                    if (data.success) {
                        $("#progress-bar").css("width", "0%");
                        $("#progress-bar").text("0%");
                        $("#progress-bar-container").hide();

                        registerChapterForm.resetForm();
                        form.trigger("reset");
                        $("#chapters-list-container").html(data.htmlChapter);
                        $("#sections-list-container").html(data.htmlSection);
                        $("#course-box-container").html(data.htmlCourse);

                        let urlTable = $("#section-box-" + data.id).data(
                            "table"
                        );

                        var chaptersTableEle = $("#freeCourses-chapters-table");

                        chapterTable(chaptersTableEle, DataTableEs, urlTable);

                        $(".order-section-select").select2({
                            minimumResultsForSearch: -1,
                        });

                        document.getElementById(
                            "label-input-file-chapter"
                        ).textContent = "Ningún archivo seleccionado";

                        Toast.fire({
                            icon: "success",
                            text: data.message,
                        });

                        modal.modal("hide");
                    } else {
                        $("#progress-bar").css("width", "0%");
                        $("#progress-bar").text("0%");
                        $("#progress-bar-container").hide();
                        Toast.fire({
                            icon: "error",
                            text: data.message,
                        });
                    }
                },
                complete: function () {
                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").removeAttr("disabled");
                },
                error: function () {
                    ToastError.fire();
                },
            });
        },
    });

    /*--------- EDIT ............*/

    $("#editOrderSelectChapter").select2({
        dropdownParent: $("#editChapterModal"),
        minimumResultsForSearch: -1,
    });

    // (Update) Post data

    document
        .getElementById("input-file-chapter-edit")
        .addEventListener("change", function () {
            const fileName = this.files[0]
                ? this.files[0].name
                : "Ningún archivo seleccionado";
            document.getElementById(
                "label-input-file-chapter-edit"
            ).textContent = fileName;
        });

    var updateChapterForm = $("#editChapterForm").validate({
        rules: {
            title: {
                required: true,
                maxlength: 100,
            },
            description: {
                required: true,
                maxlength: 500,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var formEdit = $(form);
            let loadSpinner = formEdit.find(".loadSpinner");
            loadSpinner.toggleClass("active");
            formEdit.find(".btn-save").attr("disabled", "disabled");
            let urlChanged = formEdit.attr("action");

            var fileInputStore = $("#input-file-chapter-edit")[0];
            var file = fileInputStore.files[0];
            var title = formEdit.find("#title").val();
            var description = formEdit
                .find("#description-text-area-edit")
                .val();
            var chapter_order = formEdit.find("#editOrderSelectChapter").val();
            var formData = new FormData();
            formData.append("file", file);
            formData.append("title", title);
            formData.append("description", description);
            formData.append("chapter_order", chapter_order);

            $.ajax({
                method: formEdit.attr("method"),
                url: urlChanged,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "JSON",
                xhr: function () {
                    var xhr = new XMLHttpRequest();

                    $("#progress-bar-container-edit").show();

                    xhr.upload.addEventListener(
                        "progress",
                        function (e) {
                            if (e.lengthComputable) {
                                var percentComplete = Math.round(
                                    (e.loaded / e.total) * 100
                                );
                                $("#progress-bar-edit").css(
                                    "width",
                                    percentComplete + "%"
                                );

                                $("#progress-bar-edit").text(
                                    percentComplete + "%"
                                );

                                $("#progress-bar-edit").attr(
                                    "aria-valuenow",
                                    percentComplete
                                );
                            }
                        },
                        false
                    );

                    return xhr;
                },
                success: function (data) {
                    if (data.success) {
                        $("#progress-bar-edit").css("width", "0%");
                        $("#progress-bar-edit").text("0%");
                        $("#progress-bar-container-edit").hide();

                        let urlTable = $("#section-box-" + data.id).data(
                            "table"
                        );

                        let chaptersBox = $("#chapters-list-container");
                        let courseBox = $("#course-box-container");
                        chaptersBox.html(data.htmlChapter);
                        courseBox.html(data.htmlCourse);

                        let chaptersTableEle = $("#freeCourses-chapters-table");

                        chapterTable(chaptersTableEle, DataTableEs, urlTable);

                        updateChapterForm.resetForm();
                        formEdit.trigger("reset");

                        document.getElementById(
                            "label-input-file-chapter-edit"
                        ).textContent = "Ningún archivo seleccionado";

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
                    loadSpinner.toggleClass("active");
                    $("#editChapterModal").modal("hide");
                    formEdit.find(".btn-save").removeAttr("disabled");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        },
    });

    $("html").on("click", ".editChapter", function () {
        var button = $(this);
        var modal = $("#editChapterModal");
        var getDataUrl = button.data("send");
        var url = button.data("url");
        var form = $("#editChapterForm");
        var loadSpinner = form.find(".loadSpinner");

        $("#editOrderSelectChapter").html("");

        button
            .closest("tr")
            .siblings()
            .find(".editChapter")
            .removeClass("active");
        button.addClass("active");

        // (Edit) Get data

        $.ajax({
            type: "GET",
            url: getDataUrl,
            dataType: "JSON",
            success: function (data) {
                var chapter = data.chapter;
                form.find("input[name=title]").val(chapter.title);
                form.find("#description-text-area-edit").val(
                    chapter.description
                );

                var select = $("#editOrderSelectChapter");

                select.select2({
                    dropdownParent: $("#editChapterModal"),
                    minimumResultsForSearch: -1,
                });

                $.each(data.chapters_list, function (key, values) {
                    select.append(
                        '<option value="' +
                            values.chapter_order +
                            '">' +
                            values.chapter_order +
                            "</option>"
                    );
                });

                select.val(chapter.chapter_order).change();
            },
            complete: function (data) {
                let urlChanged = $(".editChapter.active").data("url");
                $("#editChapterForm").attr("action", urlChanged);
                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    /* -------- PREVIEW VIDEO ---------*/

    $("html").on("click", ".preview-chapter-video-button", function (e) {
        e.preventDefault();

        var modal = $("#previewChapterModal");
        var url = $(this).data("url");
        var video_container = $("#video-chapter-container");
        video_container.html(
            '<video id="chapter-video" class="video-js chapter-video"></video>'
        );

        $.ajax({
            type: "GET",
            url: url,
            dataType: "JSON",
            success: function (data) {
                modal.find(".title-preview-section").html(data.section);
                modal.find(".title-preview-chapter").html(data.chapter);

                let urlDelete = data.url_delete;
                let btnDeleteVideo = modal.find(".btn-delete-video");

                btnDeleteVideo.attr("data-url", urlDelete);

                var playerChapter = videojs("chapter-video", {
                    controls: true,
                    fluid: true,
                    playbackRates: [0.5, 1, 1.5, 2],
                    autoplay: false,
                    preload: "auto",
                });

                playerChapter.src(data.url_video);

                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    $("#previewChapterModal").on("hidden.bs.modal", function () {
        videojs("chapter-video").dispose();
    });

    /* -------- DELETE ----------*/

    $("html").on("click", ".deleteChapter", function () {
        var button = $(this);
        var url = button.data("url");

        var sectionActive = $("#sections-list-container")
            .find(".course-section-box.active")
            .data("id");

        Swal.fire({
            title: "¡Cuidado!",
            text: "¡Esto también eliminará el progreso de los usuarios!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Continuar y eliminar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            id: sectionActive,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                var courseBox = $("#course-box-container");
                                var sectionBox = $("#sections-list-container");
                                var chaptersBox = $("#chapters-list-container");

                                courseBox.html(data.htmlCourse);
                                sectionBox.html(data.htmlSection);
                                chaptersBox.html(data.htmlChapter);

                                let urlTable = $(
                                    "#section-box-" + data.id
                                ).data("table");

                                var chaptersTableEle = $(
                                    "#freeCourses-chapters-table"
                                );

                                chapterTable(
                                    chaptersTableEle,
                                    DataTableEs,
                                    urlTable
                                );

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
                        error: function (result) {
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

    // * ----------- DELETE VIDEO -------------

    $("html").on("click", ".btn-delete-video", function () {
        var button = $(this);
        let url = button.data("url");
        var modal = $("#previewChapterModal");

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
                            if (result.success) {
                                var chaptersBox = $("#chapters-list-container");

                                chaptersBox.html(result.htmlChapter);

                                let urlTable = $(
                                    "#section-box-" + result.id
                                ).data("table");

                                var chaptersTableEle = $(
                                    "#freeCourses-chapters-table"
                                );

                                chapterTable(
                                    chaptersTableEle,
                                    DataTableEs,
                                    urlTable
                                );
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
                modal.modal("hide");
                Toast.fire({
                    icon: "success",
                    text: "¡Registro actualizado!",
                });
            }
        });
    });

    // * -------------- LOAD CONTENT MODAL -------------------

    if ($("#viewContentChapterModal").length) {
        function getSummernoteConfig(summernoteElement, cardForm) {
            return {
                dialogsInBody: true,
                minHeight: 500,
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

        $("html").on("click", ".showContentChapter-btn", function () {
            let button = $(this);
            let getDataUrl = button.data("send");
            let url = button.data("url");

            let modal = $("#viewContentChapterModal");

            $("#updateContentChapterForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let chapter = data.chapter;
                    let chapterTitle = chapter.title;
                    let html = data.html;

                    let chapterTitleCont = modal.find(
                        ".modal_chapter_title_content"
                    );
                    chapterTitleCont.html(chapterTitle);

                    let bodyContentChapter = modal.find(
                        ".modal-body-content-chapter"
                    );

                    bodyContentChapter.html(html);

                    modal.find("textarea[name=content]").val(chapter.content);

                    $("#updateContentChapterForm").attr("action", url);

                    var updateContentChapterForm = $(
                        "#updateContentChapterForm"
                    ).validate({
                        rules: {
                            content: {
                                maxlength: 3000,
                            },
                        },
                        errorElement: "label",
                        errorClass: "is-invalid",
                        validClass: "is-valid",
                        ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
                        errorPlacement: function (error, element) {
                            error.addClass("error");
                            if (element.prop("type") === "checkbox") {
                                error.insertAfter(element.siblings("label"));
                            } else if (element.hasClass("summernote")) {
                                error.insertAfter(
                                    element.siblings(".note-editor")
                                );
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        submitHandler: function (form, event) {
                            event.preventDefault();

                            var form = $(form);
                            var loadSpinner = form.find(".loadSpinner");
                            var modal = $("#viewContentChapterModal");

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
                                        // destroy summerNote

                                        $(
                                            "#card-content-chapter-register"
                                        ).summernote("destroy");
                                        modal
                                            .find("textarea[name=content]")
                                            .empty();

                                        let html = data.html;

                                        modal
                                            .find(".modal-body-content-chapter")
                                            .html(html);

                                        modal
                                            .find("textarea[name=content]")
                                            .val(data.content);

                                        // create summerNote
                                        $(
                                            "#card-content-chapter-register"
                                        ).summernote(
                                            getSummernoteConfig(
                                                $(
                                                    "#card-content-chapter-register"
                                                ),
                                                updateContentChapterForm
                                            )
                                        );

                                        modal
                                            .find("textarea[name=content]")
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

                    // * ------------ CREATE - DESTROY SUMMERNOTE ---------------- *

                    $("#viewContentChapterModal").on(
                        "show.bs.modal",
                        function () {
                            $("#card-content-chapter-register").summernote(
                                getSummernoteConfig(
                                    $("#card-content-chapter-register"),
                                    updateContentChapterForm
                                )
                            );
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                    $("#viewContentChapterModal").on(
                        "hidden.bs.modal",
                        function () {
                            $("#card-content-chapter-register").summernote(
                                "destroy"
                            );
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
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

    // * ------------- FILES -------------------

    if ($("#viewFilesChapterModal").length) {
        $("html").on("click", ".showDocsChapter", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#viewFilesChapterModal");

            $("#storeChapterFileForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    // * dibujar datos en el modal

                    let chapterTitle = data.title;
                    let html = data.html;
                    let chapterTitleCont = modal.find(
                        ".modal_files_chapter_title"
                    );

                    let modalDocsBody = modal.find(
                        "#table_chapters_files_container"
                    );
                    chapterTitleCont.html(chapterTitle + ": ");
                    modalDocsBody.html(html);
                    $("#storeChapterFileForm").attr("action", url);

                    // * validar la subida del archivo

                    var storeFileForm = $("#storeChapterFileForm").validate({
                        rules: {
                            "files[]": {
                                required: true,
                            },
                        },
                        submitHandler: function (form, event) {
                            event.preventDefault();
                            var form = $(form);
                            var loadSpinner = form.find(".loadSpinner");
                            // var modal = $("#storeChapterFileForm");

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
                                        Toast.fire({
                                            icon: "success",
                                            text: data.message,
                                        });

                                        form.trigger("reset");
                                        storeFileForm.resetForm();
                                        modalDocsBody.html(data.html);

                                        var chaptersBox = $(
                                            "#chapters-list-container"
                                        );

                                        chaptersBox.html(data.htmlChapter);

                                        let urlTable = $(
                                            "#section-box-" + data.id
                                        ).data("table");

                                        var chaptersTableEle = $(
                                            "#freeCourses-chapters-table"
                                        );

                                        chapterTable(
                                            chaptersTableEle,
                                            DataTableEs,
                                            urlTable
                                        );

                                        // modal.modal("hide");
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

                    // * delete

                    $("#table_chapters_files_container").on(
                        "click",
                        ".deleteChapterFile",
                        function () {
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
                                    return new Promise(function (
                                        resolve,
                                        reject
                                    ) {
                                        $.ajax({
                                            type: "DELETE",
                                            url: url,
                                            dataType: "JSON",
                                            success: function (result) {
                                                if (result.success) {
                                                    let html = result.html;
                                                    let modalDocsBody =
                                                        modal.find(
                                                            "#table_chapters_files_container"
                                                        );
                                                    modalDocsBody.html(html);

                                                    var chaptersBox = $(
                                                        "#chapters-list-container"
                                                    );

                                                    chaptersBox.html(
                                                        result.htmlChapter
                                                    );

                                                    let urlTable = $(
                                                        "#section-box-" +
                                                            result.id
                                                    ).data("table");

                                                    var chaptersTableEle = $(
                                                        "#freeCourses-chapters-table"
                                                    );

                                                    chapterTable(
                                                        chaptersTableEle,
                                                        DataTableEs,
                                                        urlTable
                                                    );
                                                } else {
                                                    Toast.fire({
                                                        icon: "error",
                                                        text: result.message,
                                                    });
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

                            // SwalDelete.fire().then(
                            //     function (e) {
                            //         if (e.value === true) {
                            //             $.ajax({
                            //                 type: "DELETE",
                            //                 url: url,
                            //                 dataType: "JSON",
                            //                 success: function (result) {
                            //                     if (result.success) {
                            //                         let html = result.html;
                            //                         let modalDocsBody =
                            //                             modal.find("#table_chapters_files_container");
                            //                             modalDocsBody.html(html);

                            //                         Toast.fire({
                            //                             icon: "success",
                            //                             text: result.message,
                            //                         });
                            //                     } else {
                            //                         Toast.fire({
                            //                             icon: "error",
                            //                             text: result.message,
                            //                         });
                            //                     }
                            //                 },
                            //                 complete: function () {
                            //                     $(".btn-save").removeAttr(
                            //                         "disabled"
                            //                     );
                            //                     // loadSpinner.toggleClass("active");
                            //                 },
                            //                 error: function (result) {
                            //                     ToastError.fire();
                            //                 },
                            //             });
                            //         } else {
                            //             e.dismiss;
                            //         }
                            //     },
                            //     function (dismiss) {
                            //         return false;
                            //     }
                            // );
                        }
                    );
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    // console.log(data)
                    ToastError.fire();
                },
            });
        });
    }
});
