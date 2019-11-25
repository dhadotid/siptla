function formtindaklanjut(rekom_id,id)
{

    if(id==-1)
    {
        clearform('form_tindaklanjut_add')
        $('#add_rekom_id').val(rekom_id);
        $('#modaltambahtindaklanjut').modal('show');
    }
    else
    {
        clearform('form_tindaklanjut_edit')
        form('edit', id);
        $('#edit_rekom_id').val(rekom_id);
        $('#modalubahtindaklanjut').modal('show');
    }
}

function form(jenis,id)
{
    $.ajax({
        url : flagsUrl + '/tindak-lanjut-edit/'+id,
        success : function(res)
        {

        }
    });
}
function clearform(idform)
{
    $('#'+idform).trigger("reset");
    $('.add-summernote-tindaklanjut').summernote("reset");
    $('.edit-summernote-tindaklanjut').summernote("reset");
}
function validasitindaklanjut(jns)
{
    var summernote_tindaklanjut = $('.'+jns+'-summernote-tindaklanjut');
    var nilai_tindaklanjut = $('#'+jns+'_nilai_tindaklanjut');
    var dokumen = $('#'+jns+'-dokumen');
    var idrekom = $('#' + jns +'_rekom_id');

    if (summernote_tindaklanjut.summernote('isEmpty'))
    {
        notif('error', 'Data Tindak Lanjut Belum Di Isi')
    }
    else if (nilai_tindaklanjut.val()=='')
    {
        notif('error', 'Nilai Tindak Lanjut Belum Di Isi')
    }
    else if (dokumen.get(0).files.length === 0){
        notif('error', 'File Dokumen Belum Dipilih')
    }
    else
    {
        var konten = summernote_tindaklanjut.summernote('code');
        var file_data=dokumen.prop('files')[0];

        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('tindaklanjut', konten);
        form_data.append('nilai_tindaklanjut', nilai_tindaklanjut.val());
        $.ajax({
            url: flagsUrl + '/tindak-lanjut-simpan/' + idrekom.val(),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (res) {
                // alert(res); // display response from the PHP script, if any
                $('#modaltambahtindaklanjut').modal('hide');
                $('#modalubahtindaklanjut').modal('hide');
                swal("Berhasil", "Data Tindak Lanjut Berhasil Di Simpan", "success");
                reloadtable('temuan_' + res, res)
            }
        });
    }
        
}

$('.add-summernote-tindaklanjut').summernote({
    height:250
});
$('.edit-summernote-tindaklanjut').summernote({
    height:250
});
$('#nominal').on('keyup', function (e) {
    $(this).val(format($(this).val()));
});