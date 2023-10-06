$(document).ready(function () {
    $('.import-file').on('click', function (e) {
        $('#importModal').css('display', 'block');
        e.preventDefault();
    });

    $('.import-btn').on('click', function () {
        $('#import').click();
        $('#importModal').css('display', '');
    });

    $(document).on('click', '.modal .close', function () {
        $(this).closest('.modal').css('display', '');
    });
    let importId = $('#import');
    importId.on('click', function (e) {
        e.stopImmediatePropagation();
    });

    importId.on('change', function () {
        //validate file format
        let val = $(this).val().toLowerCase();
        let regex = new RegExp("(.*?)\.(xls|xlsx)$");
        if (!(regex.test(val))) {
            $(this).val('');
            notify("Sai định dạng file không cho phép", 'alert-danger');
            return;
        }

        let file = $(this).prop('files')[0];
        let url = $(this).closest(".import-file").attr('data-url');

        uploadFile(file, url);
    });
});

/**
 * Upload file to server using ajax
 * @param fileData
 * @param url
 */
function uploadFile(fileData, url) {
    let data = new FormData();
    data.append('file', fileData);
    show_loading();
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        dataType: 'text',
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            hide_loading();
            if (data) {
                data = JSON.parse(data);
                if (data.state) {
                    notify(data.msg, "alert-success");
                    // console.log(data['redirect']);
                    setTimeout(function () {
                        if (typeof data['redirect'] !== 'undefined') {
                            window.location = data['redirect'];
                        } else {
                            location.reload();
                        }
                    }, 1000)
                } else {
                    notify(data.msg, "alert-danger");
                    $('#import').val('');
                }
                console.log("success");
            }
        },
        error: function () {
            console.log("error");
        }
    });
}