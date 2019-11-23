
function validasiadd(act) {
    var nomor_temuan = $('#' + act + '_nomor_temuan');
    var temuan = $('#' + act + '_temuan');
    var jenis_temuan = $('#' + act + '_jenis_temuan');
    var pic_temuan = $('#' + act + '_pic_temuan');
    var nominal = $('#' + act + '_nominal');
    var level_resiko = $('#' + act + '_level_resiko');

    if (nomor_temuan.val() == '')
        notif('error', 'Nomor Temuan Belum Diisi');
    else if (temuan.val() == '')
        notif('error', 'Temuan Belum Diisi');
    else if (jenis_temuan.val() == '')
        notif('error', 'Jenis Temuan Belum Dipilih');
    else if (pic_temuan.val() == '')
        notif('error', 'PIC Temuan Belum Dipilih');
    else if (nominal.val() == '')
        notif('error', 'Jumlah Nominal Belum Diisi');
    else if (level_resiko.val() == '')
        notif('error', 'Level Resiko Belum Dipilih');
    else {
        $('#form' + act).submit();
    }
}

function validasirekom(act) {
    var rekomendasi = $('#'+act+'_rekomendasi');
    var nominal = $('#' + act +'_nilai_rekomendasi');
    var pic_1 = $('#'+act+'_pic_1');
    var pic_2 = $('#'+act+'_pic_2');
    var jangka_waktu = $('#'+act+'_jangka_waktu');
    var status_rekomendasi = $('#'+act+'_status_rekomendasi');
    var review_auditor = $('#'+act+'_review_auditor');

    if (rekomendasi.val() == '')
        notif('error', 'Rekomendasi Belum Diisi');
    else if (nominal.val() == '')
        notif('error', 'Jumlah Nilai Rekomendasi Belum Diisi');
    else if (pic_1.val() == '')
        notif('error', 'PIC 1 Belum Dipilih');
    else if (pic_2.val() == '')
        notif('error', 'PIC 2 Belum Dipilih');
    else if (jangka_waktu.val() == '')
        notif('error', 'Jangka Waktu Penyelesaian Belum Dipilih');
    else if (status_rekomendasi.val() == '')
        notif('error', 'Status Rekomendasi Belum Dipilih');
    else if (review_auditor.val() == '')
        notif('error', 'Review Auditor Belum Dipilih');
    else {
        if(act=='add')
        {
            $('#form_rekom_' + act).attr('action', flagsUrl +'/rekomendasi-simpan');
            $('#form_rekom_' + act).submit();
        }
        else if(act=='edit')
        {
            var idtemuan = $('.d_id_temuan').val();
            var idrekom = $('#idrekom').val();
            // $('#form_rekom_' + act).attr('action', flagsUrl + '/rekomendasi-update/'+idtemuan+'/'+idrekom);
            
            $.ajax({
                url: flagsUrl + '/rekomendasi-update/' + idrekom + '/' + idtemuan,
                data: $('#form_rekom_edit').serialize(),
                type: 'POST',
                datatype: 'JSON',
                success:function(res){
                    reloadtable('temuan_'+idtemuan, idtemuan)
                    $('#modalubahrekomendasi').modal('hide');
                    swal("Berhasil", "Data Rekomendasi Berhasil Di Ubah", "success");
                }
            });
        }
        
        
    }
}

function rekomadd(idtemuan)
{
    $('#id_temuan').val(idtemuan);
    $.ajax({
        url: flagsUrl + '/data-temuan-lhp-edit/'+idtemuan,
        success : function(res){
            $('#nomor_temuan').val(res.no_temuan);
            $('#temuan').val(res.temuan);
            $('#jenis_temuan').val(res.jenis_temuan_id);
            
            if (res.jenis_temuan_id==2)
            {
                $('#div_add_rekanan').css('display','block');
                $('#div_edit_rekanan').css('display','block');
            }
            else
            {
                $('#div_add_rekanan').css('display', 'none');
                $('#div_edit_rekanan').css('display', 'none');
            }
        }
    });
}

//------------------------
var table = $('#datatable-temuan').DataTable();

$('#datatable-temuan tbody').on('click', 'span.rekomendasi-detail', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var id = $(this).data('value')
    if (row.child.isShown()) {
        // This row is already open - close it
        // table.ajax.reload();
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row
        row.child(formatdatatable(id)).show();
        tr.addClass('shown').attr('id', 'row_' + id);
    }
});

function formatdatatable(idtemuan) {

    var div = $('<div/>')
        .addClass('loading')
        .attr('id','temuan_'+idtemuan)
        .text('Loading...');

    $.ajax({
        url: flagsUrl + '/rekomendasi-data/' + idtemuan,
        success: function (res) {
            div.html(res).removeClass('loading');
        }
    });
    return div;
}

function reloadtable(idtable,idtemuan)
{
    $('#' + idtable).load(flagsUrl + '/rekomendasi-data/' + idtemuan);
}
//------------------------
function detailrekomendasi(idrekom)
{
    $.ajax({
        url: flagsUrl +'/rekomendasi-edit/'+idrekom,
        success:function(res){
            $('#detail_rekomendasi').val(res.rekomendasi);
            $('#detail_nilai_rekomendasi').val(format(res.nominal_rekomendasi));
            $('#detail_pic_1').val(res.picunit1.nama_pic);
            $('#detail_pic_2').val(res.picunit2.nama_pic);
            $('#detail_rekanan').val(res.drekanan.nama);
            $('#detail_jangka_waktu').val(res.jangkawaktu.jangka_waktu);
            $('#detail_status_rekomendasi').val(res.statusrekomendasi.rekomendasi);
            $('#detail_review_auditor').val(res.review_auditor);
            $('.d_nomor_temuan').val(res.dtemuan.no_temuan);
            $('.d_temuan').val(res.dtemuan.temuan);
        }
    });
    $('#modaldetailrekomendasi').modal('show');
}
function editrekomendasi(idrekom)
{
    $.ajax({
        url: flagsUrl +'/rekomendasi-edit/'+idrekom,
        success:function(res){
            $('#edit_rekomendasi').val(res.rekomendasi);
            $('#edit_nilai_rekomendasi').val(format(res.nominal_rekomendasi));
            $('#edit_pic_1').val(res.pic_1_temuan_id);
                $('#edit_pic_1').select2().trigger('change');

            $('#edit_pic_2').val(res.pic_2_temuan_id);
                $('#edit_pic_2').select2().trigger('change');

            $('#edit_rekanan').val(res.drekanan.nama);
            $('#edit_jangka_waktu').val(res.jangka_waktu_id);
                $('#edit_jangka_waktu').select2().trigger('change');

            $('#edit_status_rekomendasi').val(res.status_rekomendasi_id);
                $('#edit_status_rekomendasi').select2().trigger('change');

            $('#edit_review_auditor').val(res.review_auditor);

            $('.d_nomor_temuan').val(res.dtemuan.no_temuan);
            $('.d_id_temuan').val(res.id_temuan);
            $('.d_jenis_temuan').val(res.dtemuan.jenis_temuan_id);
            $('.d_temuan').val(res.dtemuan.temuan);
        }
    });
    $('#idrekom').val(idrekom);
    $('#modalubahrekomendasi').modal('show');
    // $('#form_rekom_edit').attr('action',flagsUrl+'/rekomendasi-update/'+idrekom);
}
function hapusrekomendasi(idrekom,idtemuan)
{
    $('#modalhapusrekomendasi').modal('show');
    $('#hapusrekom').one('click',function(){
        $.ajax({
            url: flagsUrl + '/rekomendasi-delete/' + idrekom + '/' + idtemuan,
            success:function(res){
                $('#data_rekom_' + idrekom).remove();
                $('#div-jlh-rekom-'+idtemuan).html(res.jlh);
                $('#modalhapusrekomendasi').modal('hide');

                swal("Berhasil", "Data Rekomendasi Berhasil Di Hapus", "success");

                // $('.alert').fadeOut();
            }
        });
    });
}




$('[data-toggle="tooltip"]').tooltip();


$('.select').select2();


$('#add_nominal').on('keyup', function (e) {
    $(this).val(format($(this).val()));
});
$('#edit_nominal').on('keyup', function (e) {
    $(this).val(format($(this).val()));
});
$('.nominal').on('keyup', function (e) {
    $(this).val(format($(this).val()));
});
$("#add_rekanan").autocomplete({
    source: function (request, response) {
        jQuery.get(flagsUrl + "/data-rekanan", {
            query: request.term
        }, function (data) {
            response(data);
        });
    }
});
$("#edit_rekanan").autocomplete({
    source: function (request, response) {
        jQuery.get(flagsUrl + "/data-rekanan", {
            query: request.term
        }, function (data) {
            response(data);
        });
    }
});
