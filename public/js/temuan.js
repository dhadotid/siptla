
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
    // var jangka_waktu = $('#'+act+'_jangka_waktu');
    var status_rekomendasi = $('#'+act+'_status_rekomendasi');
    // var review_auditor = $('#'+act+'_review_auditor');

    if (rekomendasi.val() == '')
        notif('error', 'Rekomendasi Belum Diisi');
    else if (nominal.val() == '')
        notif('error', 'Jumlah Nilai Rekomendasi Belum Diisi');
    else if (pic_1.val() == '')
        notif('error', 'PIC 1 Belum Dipilih');
    else if (pic_2.val() == '')
        notif('error', 'PIC 2 Belum Dipilih');
    // else if (jangka_waktu.val() == '')
    //     notif('error', 'Jangka Waktu Penyelesaian Belum Dipilih');
    else if (status_rekomendasi.val() == '')
        notif('error', 'Status Rekomendasi Belum Dipilih');
    // else if (review_auditor.val() == '')
    //     notif('error', 'Review Auditor Belum Dipilih');
    else {
        if(act=='add')
        {
            var idtemuan = $('#id_temuan').val();
            // $('#form_rekom_' + act).attr('action', flagsUrl +'/rekomendasi-simpan');
            // $('#form_rekom_' + act).submit();
            $.ajax({
                url: flagsUrl + '/rekomendasi-simpan',
                data: $('#form_rekom_add').serialize(),
                type: 'POST',
                datatype: 'JSON',
                success: function (res) {
                    reloadtable('temuan_' + idtemuan, idtemuan)
                    $('#modaltambahrekomendasi').modal('hide');
                    // swal("Berhasil", "Data Rekomendasi Berhasil Di Ubah", "success");
                    resetform(act);
                    setTimeout(function(){
                        // reloadtable('temuan_' + idtemuan, idtemuan)
                        updatejlhrekomendasi(idtemuan);
                        $('#modaltambahrekomendasi').modal('show');
                    },1000);
                }
            });
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
function resetform(jns)
{
    $('#'+jns+'_rekomendasi').val('');
    $('#'+jns+'_nilai_rekomendasi').val('');
    $('#'+jns+'_pic_1').val('');
    $('#'+jns+'_pic_2').val('');
    // $('#'+jns+'_rekanan').val('');
    // $('#'+jns+'_jangka_waktu').val('');
    $('#'+jns+'_status_rekomendasi').val('');
    // $('#'+jns+'_review_auditor').val('');

    $('#'+jns+'_pic_1').select2().trigger('change');
    $('#'+jns+'_pic_2').select2().trigger('change');
    $('#'+jns+'_jangka_waktu').select2().trigger('change');
    $('#'+jns+'_status_rekomendasi').select2().trigger('change');
    $('input[name="butuh_rincian"]').prop('checked', true);
    $('#rincian_tl').val('');
    cekrbutuhrincian();
    $('#right-div').html('');
}
function rekomadd(idtemuan)
{
    $('input[name="butuh_rincian"]').prop('checked', true);
    $('#rincian_tl').val('');
    cekrbutuhrincian();
    $('#right-div').html('');
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
        row.child(formatdatatablenew(id)).show();
        tr.addClass('shown').attr('id', 'row_' + id);
    }
});
// function showchildetable(id)
// {

// }
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
function formatdatatablenew(idtemuan_statusrekom) {

    var txt = idtemuan_statusrekom.split('_');
    var div = $('<div/>')
        .addClass('loading')
        .attr('id','temuan_'+txt[0])
        .text('Loading...');

    $.ajax({
        url: flagsUrl + '/rekomendasi-data-new/' + txt[0]+'/'+txt[1],
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

            // var picunit2 = res.picunit2.nama_pic.split(',');
            if (res.picunit2)
                $('#detail_pic_2').val(res.picunit2.nama_pic);
            else
                $('#detail_pic_2').val(res.picunit_2);

            // $('#detail_pic_2').val(picunit2);
            
            if(res.drekanan)
                $('#detail_rekanan').val(res.drekanan.nama);
                
            // $('#detail_jangka_waktu').val(res.jangkawaktu.jangka_waktu);
            $('#detail_status_rekomendasi').val(res.statusrekomendasi.rekomendasi);
            // $('#detail_review_auditor').val(res.review_auditor);
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
            // var x='8,7';

            // var picunit2 = x.split(',');
            
            
            // $('#edit_pic_2').val(res.pic_2_temuan_id);
            var picunit2 = res.pic_2_temuan_id.split(',');
            $('#edit_pic_2').val(picunit2);
                $('#edit_pic_2').select2().trigger('change');

            if (res.drekanan)
                $('#edit_rekanan').val(res.drekanan.nama);

            // $('#edit_jangka_waktu').val(res.jangka_waktu_id);
            //     $('#edit_jangka_waktu').select2().trigger('change');

            $('#edit_status_rekomendasi').val(res.status_rekomendasi_id);
                $('#edit_status_rekomendasi').select2().trigger('change');

            // $('#edit_review_auditor').val(res.review_auditor);

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

function updatejlhrekomendasi(idtemuan)
{
    $('#div-jlh-rekom-' + idtemuan).load(flagsUrl+'/update-jlh-rekomendasi/'+idtemuan);
}

function cekrbutuhrincian()
{
    if ($('#butuh_rincian').is(':checked')) 
    { 
        $('#rincian_tl').prop('disabled',false);
    }
    else
    {
        $('#rincian_tl').prop('disabled', true);
        $('#left-div').removeClass('col-md-6');
        $('#left-div').addClass('col-md-12');
        $('#right-div').removeClass('col-md-6');
        $('#right-div').addClass('col-md-0');
        $('#modal-size').attr({ 'style': 'width:60% !important' });
    }
}

function pilihrincian(val)
{
    if(val!='')
    {
        $('#left-div').removeClass('col-md-12');
        $('#left-div').addClass('col-md-6');
        $('#right-div').removeClass('col-md-0');
        $('#right-div').addClass('col-md-6');
        $('#modal-size').attr({'style':'width:95% !important'});
        var idtemuan = $('#id_temuan').val();
        var idrekom = $('.status_rekom').val();
        gettablerincian(val, idtemuan, idrekom)
    }
    else
    {
        $('#left-div').removeClass('col-md-6');
        $('#left-div').addClass('col-md-12');
        $('#right-div').removeClass('col-md-6');
        $('#right-div').addClass('col-md-0');
        $('#modal-size').attr({ 'style': 'width:60% !important' });       
    }
}

function gettablerincian(jenis, idtemuan, idrekom)
{
    $('#right-div').load(flagsUrl+'/load-table-rincian/'+jenis+'/'+idtemuan+'/'+idrekom);
}

function rekomaddnew(idtemuan) {
    $('#id_temuan_rekom').val(idtemuan);
}

function addtindaklanjut(jenis,idtemuan,idrekom,id)
{
    if(jenis=='sewa')
    {
        $('#form-rincian-sewa').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciansewa').modal('show')
    }
    else if(jenis=='uangmuka')
    {
        $('#form-rincian-uangmuka').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianuangmuka').modal('show')
    }
    else if(jenis=='listrik')
    {
        $('#form-rincian-listrik').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianlistrik').modal('show')
    }
    else if(jenis=='piutang')
    {
        $('#form-rincian-piutang').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutang').modal('show')
    }
}

function validasiformsewa()
{
    var unit_kerja = $('#unit_kerja');
    var mitra = $('#mitra');
    var no_pks = $('#no_pks');
    var tgl_pks = $('#tgl_pks');
    var nilai_perjanjian = $('#nilai_perjanjian');
    var masa_berlaku = $('#masa_berlaku');

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (mitra.val() == '')
        notif('error', 'Nama Mitra Belum Diisi');
    else if (no_pks.val() == '')
        notif('error', 'Nomor PKS Belum Diisi');
    else if (tgl_pks.val() == '')
        notif('error', 'Tanggal PKS Belum Dipilih');
    else if (nilai_perjanjian.val() == '')
        notif('error', 'Nilai Pekerjaan Belum Diisi');
    else if (masa_berlaku.val() == '')
        notif('error', 'Masa Berlaku Belum Dipilih');
    else {
        //formrinciansewa
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrinciansewa').serialize(),
            type: 'POST',
            datatype:'JSON',
            success: function (res) {
                if(res)
                {
                    // $('#formrinciansewa').reset();
                    $('#formrinciansewa').trigger("reset");
                    $('#modalrinciansewa').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () { 
                        $('#modalrinciansewa').modal('show');
                     },1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiformuangmuka() {
    var unit_kerja = $('#unit_kerja');
    var no_invoice = $('#no_invoice');
    var tgl_pum = $('#tgl_pum');
    var jumlah_um = $('#jumlah_um');


    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (no_invoice.val() == '')
        notif('error', 'Nomor Invoice Belum Diisi');
    else if (tgl_pum.val() == '')
        notif('error', 'Tanggal PUM Belum Dipilih');
    else if (jumlah_um.val() == '')
        notif('error', 'Jumlah UM Belum Diisi');
    else {
        //formrinciansewa
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrincianuangmuka').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrincianuangmuka').trigger("reset");
                    $('#modalrincianuangmuka').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#modalrincianuangmuka').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiformlistrik() {
    var unit_kerja = $('#unit_kerja');
    var lokasi = $('#lokasi');
    var tgl_invoice = $('#tgl_invoice');
    var tagihan = $('#tagihan');


    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (lokasi.val() == '')
        notif('error', 'Lokasi Belum Diisi');
    else if (tgl_invoice.val() == '')
        notif('error', 'Tanggal Invoice Belum Dipilih');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        //formrinciansewa
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrincianlistrik').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrincianlistrik').trigger("reset");
                    $('#modalrincianlistrik').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#modalrincianlistrik').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiformpiutang() {
    var unit_kerja = $('#unit_kerja');
    var pelanggan = $('#pelanggan');
    var tagihan = $('#tagihan');


    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (pelanggan.val() == '')
        notif('error', 'Nama Pelanggan Belum Diisi');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        //formrinciansewa
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrincianpiutang').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrincianpiutang').trigger("reset");
                    $('#modalrincianpiutang').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#modalrincianpiutang').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function hapusrincian(id,jenis)
{
    swal({
        title: "Apakah Anda Yakin",
        text: "Ingin Menghapus Data Rincian Ini ?",
        icon: "warning",
        buttons: [
            'Tidak',
            'Ya, Hapus'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url : flagsUrl + '/form-rincian-hapus/'+id+'/'+jenis,
                dataType:'JSON',
                success:function(res){
                    swal("Berhasil", "Data Rincian Berhasil Di Hapus", "success");
                    gettablerincian(jenis, res.idtemuan, res.idrekomendasi);
                }
            })
        } 
    })
}