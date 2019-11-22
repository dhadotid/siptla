
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
            $('#form_rekom_' + act).attr('action', flagsUrl +'/rekomendasi-simpan');
        else if(act=='edit')
        {
            var idtemuan = $('#idtemuan').val();
            $('#form_rekom_' + act).attr('action', flagsUrl + '/rekomendasi-update/'+idtemuan);
        }
        
        $('#form_rekom_' + act).submit();
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
var table=null;
function formatdatatable(idtemuan) {

    var div = $('<div/>')
        .addClass('loading')
        .text('Loading...');

    $.ajax({
        url: flagsUrl + '/rekomendasi-data/'+idtemuan,
        success : function(res){
            div.html(res).removeClass('loading');
        }
    });
    return div;
}
//------------------------
var table = $('#datatable-temuan').DataTable();

$('#datatable-temuan tbody').on('click', 'td.rekomendasi-detail', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var id = $(this).data('value')
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row
        row.child(formatdatatable(id)).show();
        tr.addClass('shown');
    }
});
//------------------------


$('[data-toggle="tooltip"]').tooltip();
$('.select').select2();
$('#table').on('click', '.btn-edit', function () {
    var id = $(this).data('value')
    // alert(id);
    $.ajax({
        url: "{{ url('data-temuan-lhp-edit') }}/" + id,
        success: function (res) {
            $('#temuan_id').val(id);
            $('#edit_nomor_temuan').val(res.no_temuan);
            $('#edit_temuan').val(res.temuan);
            $('#edit_jenis_temuan').val(res.jenis_temuan_id);
            $('#edit_jenis_temuan').select2().trigger('change');
            $('#edit_pic_temuan').val(res.pic_temuan_id);
            $('#edit_pic_temuan').select2().trigger('change');
            $('#edit_nominal').val(format(res.nominal));
            $('#edit_level_resiko').val(res.level_resiko_id);
            $('#edit_level_resiko').select2().trigger('change');


        }
    })
})

// delete action
$('#table').on('click', '.btn-delete', function () {
    var id = $(this).data('value')
    $('#form-delete').attr('action', "{{ url('data-temuan-lhp-delete') }}/{{$idlhp}}/" + id)
})


$('#table').on('click', '.btn-edit-rekom', function () {
    var id = $(this).data('value')
    $('#idtemuan').val(id);
    // alert(id);
    $.ajax({
        url: "{{ url('rekomendasi-edit') }}/" + id,
        success: function (res) {
            // $('#edit_level_resiko').select2().trigger('change');
        }
    })
})

// delete action
$('#table').on('click', '.btn-delete-rekom', function () {
    var id = $(this).data('value')
    $('#form-delete').attr('action', "{{ url('rekomendasi-delete') }}/{{$idlhp}}/" + id)
})

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
