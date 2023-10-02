/**
 * Created by marverlous on 03/07/2017.
 */
$(document).ready(function () {
   $('.import-file').on('click', function(e){
       $('#importModal').css('display','block');
       e.preventDefault();
   });

   $('.import-btn').on('click', function(e){
       $('#import').click();
       $('#importModal').css('display','');
   });

    $(document).on('click', '.modal .close', function (e) {
        $(this).closest('.modal').css('display', '');
    });

   $('#import').on('click', function(e){
       e.stopImmediatePropagation();
   });

    $('#import').on('change', function(){
        //validate file format
        var val = $(this).val().toLowerCase();
        var regex = new RegExp("(.*?)\.(xls|xlsx)$");
        if(!(regex.test(val))) {
            $(this).val('');
            notify("Sai định dạng file không cho phép",'alert-danger');
            return;
        }

        var file  = $(this).prop('files')[0];
        var url = $(this).closest(".import-file").attr('data-url');

        uploadFile(file, url);
    });
});

/**
 * Upload file to server using ajax
 * @param fileData
 */
function uploadFile(fileData, url){
    var data = new FormData();
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
        success: function(data){
            hide_loading();
            if(data){
                data = JSON.parse(data);
                if (data.state) {
                    notify(data.msg, "alert-success");
                    // console.log(data['redirect']);
                    setTimeout(function(){
                        if(typeof data['redirect'] !== 'undefined'){
                            window.location = data['redirect'];
                        }
                        else{
                            location.reload();
                        }
                    }, 1000)
                }
                else{
                    notify(data.msg, "alert-danger");
                    $('#import').val('');
                }
                console.log("success");
            }
        },
        error: function(){
            console.log("error");
        }
    });
}