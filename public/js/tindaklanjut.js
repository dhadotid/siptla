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
            $('.edit-summernote-tindaklanjut').summernote("code",res.tindak_lanjut);
            $('#edit_nilai_tindaklanjut').val(format(res.nilai));
            $('#edit_id_tindaklanjut').val(format(id));
        }
    });
}
function clearform(idform)
{
    $('#'+idform).trigger("reset");
    $('.add-summernote-tindaklanjut').summernote("reset");
    $('.edit-summernote-tindaklanjut').summernote("reset");
}
function hapustindaklanjut(idtemuan,id)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menghapus Data Tindak Lanjut ini",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Hapus'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/tindak-lanjut-hapus/' + id,
                success: function (res) {
                    swal({
                        title: 'Berhasil!',
                        text: 'Hapus Data Tindak Lanjut Berhasil',
                        icon: 'success'
                    }).then(function () {
                        reloadtable('temuan_' + idtemuan, idtemuan)
                    });
                }
            });
        } else {

        }
    });
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
    else
    {
        var form_data = new FormData();

        if(jns=='add')
        {
            if (dokumen.get(0).files.length === 0) {
                notif('error', 'File Dokumen Belum Dipilih')
            }
        }
        else
        {
            idtindaklanjut = $('#edit_id_tindaklanjut').val();
            form_data.append('idtindaklanjut', idtindaklanjut);
        }
              
        var konten = summernote_tindaklanjut.summernote('code');
        var file_data = dokumen.prop('files')[0];
        // if (dokumen.get(0).files.length !== 0) {
        // }
        
        form_data.append('tindaklanjut', konten);
        form_data.append('file', file_data);
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
function opentl(rekomid)
{
    var css = $("tr#tl_rekom_" + rekomid).hasClass("kolom-hide");
    // alert(css);
    if(css)
        $("tr#tl_rekom_" + rekomid).removeClass("kolom-hide");
    else
        $("tr#tl_rekom_" + rekomid).addClass("kolom-hide");
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

function update_rincian(rekom_id,temuan_id)
{
    $('#form-rincian').load(flagsUrl +'/formupdaterincian/'+temuan_id+'/'+rekom_id,function(){
        $('#rincian_tl').select2();
        $('#table-tl-rincian-'+rekom_id).DataTable();
    });
    $('#modal-update-rincian').modal('show')
}
function getrincainTables(title, jenis, idtemuan, idrekom, level, seniorPublish){
    //disini cek dia pic atau bukan kalo iya, di hide button addnya idnya -> tombol-add-rincian
    document.getElementById('modaltitle').textContent = title;
    $('#form-rincian').load(flagsUrl + '/load-table-rincian/' + jenis + '/' + idtemuan + '/' + idrekom,  function () {
        $('#rincian_tl').select2();
        if(level == 'pic-unit' || seniorPublish == 1)
            $("#tombol-add-rincian").hide();
        $('#table-tl-rincian-'+idrekom).DataTable({
            "bAutoWidth": false,
            "bDestroy": true
        });
    });
    $('#modal-update-rincian').modal('show')
}
function pilihrincian(val,idtemuan,idrekom) {
    // console.log('diididid   ' + '/load-table-rincian/' + jenis + '/' + idtemuan + '/' + idrekom );
    // $('#det-update-rincian').load(flagsUrl + '/load-table-rincian/' + val + '/' + idtemuan + '/' + idrekom);
    gettablerincian(val, idtemuan, idrekom)
}

function gettablerincian(jenis, idtemuan, idrekom)
{
    $('#det-update-rincian').load(flagsUrl + '/load-table-rincian/' + jenis + '/' + idtemuan + '/' + idrekom, function () {
        $('#table-tl-rincian-'+idrekom).DataTable({
            responsive: true
        });
    });
}