
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
    var norekomendasi = $('#'+act+'_no_rekomendasi');
    var rekomendasi = $('#'+act+'_rekomendasi');
    var nominal = parseFloat($('#' + act +'_nilai_rekomendasi').val().replace(/\./g, ""));
    var pic_1 = $('#'+act+'_pic_1');

    // var jangka_waktu = $('#'+act+'_jangka_waktu');
    var status_rekomendasi = $('#'+act+'_status_rekomendasi');
    var senior_auditor = $('#' + act +'_senior_auditor');
    // var review_auditor = $('#'+act+'_review_auditor');

    if ($('#butuh_rincian').is(':checked')){ 
        var total_nilai = parseFloat(getCookie('total_nilai').replace(/\./g, ""));
        if(nominal >= total_nilai)
            return notif('error', 'Nilai rekomendasi melebihi total rincian');
    }else{
        eraseCookie('total_nilai');
    }
    return;
    if (norekomendasi.val() == '')
        notif('error', 'Nomor Rekomendasi Belum Diisi');
    else if (rekomendasi.val() == '')
        notif('error', 'Rekomendasi Belum Diisi');
    else if (nominal == null)
        notif('error', 'Jumlah Nilai Rekomendasi Belum Diisi');
    else if (senior_auditor.val() == '')
        notif('error', 'Senior Auditor Belum Dipilih');
    else if (pic_1.val() == '')
        notif('error', 'PIC 1 Belum Dipilih');
    else if (status_rekomendasi.val() == '')
        notif('error', 'Status Rekomendasi Belum Dipilih');


    // else if (jangka_waktu.val() == '')
    //     notif('error', 'Jangka Waktu Penyelesaian Belum Dipilih');
    
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
                    console.log(totalnilai.val() + 'didide: '+ nominal.val());
                    // reloadtable('temuan_' + idtemuan, idtemuan)
                    $('#temuan_' + idtemuan).load(flagsUrl + '/rekomendasi-data-new/' + idtemuan + '/' + res.status_rekomendasi_id);
                    $('#modaltambahrekomendasi').modal('hide');
                    // swal("Berhasil", "Data Rekomendasi Berhasil Di Ubah", "success");
                    resetform(act);
                    updatejlhrekomendasi(idtemuan, res.status_rekomendasi_id);
                    setTimeout(function(){
                        // reloadtable('temuan_' + idtemuan, idtemuan)
                        // $('#modaltambahrekomendasi').modal('show');
                        swal("Berhasil", "Data Rekomendasi Berhasil Di Tambah", "success").then(
                            function() {
                                location.reload();
                            }
                        );
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
                    // reloadtable('temuan_'+idtemuan, idtemuan)
                    $('#temuan_' + idtemuan).load(flagsUrl + '/rekomendasi-data-new/' + idtemuan + '/' + res.status_rekomendasi_id);
                    $('#modalubahrekomendasi').modal('hide');
                    swal("Berhasil", "Data Rekomendasi Berhasil Di Ubah", "success");
                }
            });
        }
        
        
    }
}
function resetform(jns)
{
    $('#' + jns +'_no_rekomendasi').val('');
    $('#'+jns+'_rekomendasi').val('');
    $('#'+jns+'_nilai_rekomendasi').val('');
    $('#'+jns+'_senior_auditor').val('');
    $('#'+jns+'_senior_auditor').select2().trigger('change');
    
    $('#'+jns+'_pic_1').val('');
    $('#'+jns+'_pic_2').val('');
    // $('#'+jns+'_rekanan').val('');
    // $('#'+jns+'_jangka_waktu').val('');
    $('#'+jns+'_status_rekomendasi').val('');
    // $('#'+jns+'_review_auditor').val('');

    $('#'+jns+'_pic_1').select2().trigger('change');
    $('#' + jns + '_pic_2').val([]).trigger('change');
    // $('#'+jns+'_pic_2').select2().trigger('change');


    $('#'+jns+'_jangka_waktu').select2().trigger('change');
    $('#'+jns+'_status_rekomendasi').select2().trigger('change');

    $('input[name="butuh_rincian"]').prop('checked', true);
    $('#rincian_tl').val('');
    cekrbutuhrincian();
    $('#right-div').html('');
}
function rekomadd(idtemuan)
{
    resetform('add');
    // if()
    // $('input[name="butuh_rincian"]').prop('checked', true);
    $('#butuh_rincian_false').prop('checked', true);
    // butuh_rincian
    // butuh_rincian_false
    $('#rincian_tl').val('');
    cekrbutuhrincian();
    $('#right-div').html('');
    // var idform = $('#idform').val();
    $('#id_temuan').val(idtemuan);
    $.ajax({
        url: flagsUrl + '/data-temuan-lhp-edit/'+idtemuan,
        success : function(res){
            $('#idform').val(res.id_lhp+''+idtemuan);
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
function rekomedit(idtemuan,idrekom)
{
    $('input[name="butuh_rincian"]').prop('checked', true);
    $('#rincian_tl').val('');
    cekrbutuhrincian();
    $('#right-div').html('');
    // var idform = $('#idform').val();
    $('#id_temuan').val(idtemuan);
    $.ajax({
        url: flagsUrl + '/rekomendasi-edit-data/' + idrekom,
        success : function(res){
            $('#idform').val(res.id_lhp+''+idtemuan);
            $('#nomor_temuan').val(res.no_temuan);
            $('#temuan').val(res.dtemuan.temuan);
            $('#jenis_temuan').val(res.jenis_temuan);
            $('#add_no_rekomendasi').val(res.nomor_rekomendasi);
            $('#add_rekomendasi').val(res.rekomendasi);
            $('#add_senior_auditor').val(res.senior_user_id).trigger('change');
            $('#add_nilai_rekomendasi').val(format(res.nominal_rekomendasi));
            $('#add_pic_1').val(res.pic_1_temuan_id).trigger('change');
            

            if(res.pic_2_temuan_id)
                $('#add_pic_2').val(res.pic_2_temuan_id).trigger('change');

            $('#add_status_rekomendasi').val(res.status_rekomendasi_id).trigger('change');

            $('#form_rekom_add').append('<input type="hidden" name="idrekom" value="' + res.rekom_id+'" />');

            // alert(res.rincian)
            if (res.rincian)
            {
                $('#butuh_rincian').prop('checked',true);
                $('#butuh_rincian_false').prop('checked',false);

                // $("#rincian_tl").select2("disabled", false);
                // $("#rincian_tl").attr('disabled', false);
                $('#rincian_tl').val(res.rincian).trigger('change');
                gettablerincianold(res.rincian, idtemuan, idrekom)
            }
            else
            {
                $('#butuh_rincian').prop('checked', false);
                $('#butuh_rincian_false').prop('checked', true);
                $('#rincian_tl').val('').trigger('change');
            }


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

    $('#modaltambahrekomendasi').modal('show')
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
function rekomsetujui(idtemuan, idrekom, status_rekom) {
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menyetujui dengan benar Data Rekomendasi yang telah diinput ?",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Setujui'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/setujui-rekomendasi/' + idrekom,
                success: function () {
                    swal({
                        title: 'Berhasil!',
                        text: 'Data Rekomendasi Telah Disetujui dan Akan segera di Publish ke PIC Unit',
                        icon: 'success'
                    }).then(function () {
                        $('#temuan_' + idtemuan).load(flagsUrl + '/rekomendasi-data-new/' + idtemuan + '/' + status_rekom);
                    });

                }
            });
        }
    });
}
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
        // $.ajax({
        //     url: flagsUrl + '/rekomendasi-delete/' + idtemuan + '/' + idrekom,
        //     success:function(res){
        //         // $('#data_rekom_' + idrekom).remove();
        //         // $('#div-jlh-rekom-'+idtemuan).html(res.jlh);
        //         // updatejlhrekomendasi(res.id_temuan, res.status_rekomendasi_id);
        //         $('#modalhapusrekomendasi').modal('hide');

        //         swal("Berhasil", "Data Rekomendasi Berhasil Di Hapus", "success");
                
        //         // $('.alert').fadeOut();
        //     }
        // });
        // swal("Berhasil", "Data Rekomendasi Berhasil Di Hapus", "success");
        $('#modalhapusrekomendasi').modal('hide');

        swal({
            title: 'Berhasil!',
            text: 'Hapus Data Rekomendasi Berhasil',
            icon: 'success'
        }).then(function () {
            location.href = flagsUrl + '/rekomendasi-delete/' + idtemuan + '/' + idrekom;
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

function updatejlhrekomendasi(idtemuan,st_rekom)
{
    // $('#div-jlh-rekom-' + idtemuan).load(flagsUrl+'/update-jlh-rekomendasi/'+idtemuan);
    $('#count_temuan_' + idtemuan + '_' + st_rekom).load(flagsUrl + '/update-jlh-rekomendasi/' + idtemuan+'/'+st_rekom);
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

function pilihrincianold(val)
{
    var idtemuan = $('#id_temuan').val();
    var idrekom = $('.status_rekom').val();
    if(val!='')
    {
        // alert(val+'-'+ idtemuan+'-'+ idrekom);
        $('#left-div').removeClass('col-md-12');
        $('#left-div').addClass('col-md-6');
        $('#right-div').removeClass('col-md-0');
        $('#right-div').addClass('col-md-6');
        $('#modal-size').attr({'style':'width:95% !important'});
        
        gettablerincianold(val, idtemuan, idrekom)
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

function gettablerincianold(jenis, idtemuan, idrekom)
{
    $('#right-div').load(flagsUrl+'/load-table-rincian/'+jenis+'/'+idtemuan+'/'+idrekom,function(){
        $('#table-tl-rincian-'+idrekom).DataTable( {
            responsive: true
        } );
    });
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}

function rekomaddnew(idtemuan) {
    $('#id_temuan_rekom').val(idtemuan);
}

function addtindaklanjut(jenis,idtemuan,idrekom,id)
{
    var idform=$('#idform').val();
    var pic1 = $('select.pic1').val();
    var pic2 = $('select.pic2').val();
    // alert(pic2)
    // $("#unit_kerja").append('<option value="'+pic1+'">option5</option>');
    idrekom = idform;
    if(jenis=='sewa')
    {
        $('#form-rincian-sewa').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
                
            });
        });
        $('#modalrinciansewa').modal('show')
    }
    else if(jenis=='uangmuka')
    {
        $('#form-rincian-uangmuka').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianuangmuka').modal('show')
    }
    else if(jenis=='listrik')
    {
        $('#form-rincian-listrik').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianlistrik').modal('show')
    }
    else if(jenis=='piutang')
    {
        $('#form-rincian-piutang').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutang').modal('show')
    }
    else if (jenis =='piutangkaryawan')
    {
        $('#form-rincian-piutangkaryawan').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutangkaryawan').modal('show')
    }
    else if (jenis =='hutangtitipan')
    {
        $('#form-rincian-hutangtitipan').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianhutangtitipan').modal('show')
    }
    else if (jenis =='penutupanrekening')
    {
        $('#form-rincian-penutupanrekening').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpenutupanrekening').modal('show')
    }
    else if (jenis =='umum')
    {
        $('#form-rincian-umum').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianumum').modal('show')
    }
    else if (jenis == 'kontribusi'){
        $('#form-rincian-kontribusi').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciankontribusi').modal('show')
    }
    else if(jenis == 'nonsetoranperjanjiankerjasama'){
        $('#form-rincian-nonsetoranperjanjiankerjasama').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoranperjanjiankerjasama').modal('show')
    }
    else if(jenis == 'nonsetoran'){
        $('#form-rincian-modalrinciannonsetoran').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoran').modal('show')
    }
    else if(jenis == 'nonsetoranumum'){
        $('#form-rincian-modalrinciannonsetoranumum').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoranumum').modal('show')
    }
    else if(jenis == 'nonsetoranpertanggungjawabanuangmuka'){
        $('#form-rincian-modalrinciannonsetoranpertanggungjawabanuangmuka').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom+'/'+id+'/'+pic1+'/'+pic2,function(){
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoranpertanggungjawabanuangmuka').modal('show')
    }
}

function validasinonsetoran(){
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var nilai_perjanjian = $('#jumlah_rekomendasi');

    if(unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if(keterangan.val() == '')
        notif('error', 'Keterangan belum diisi');
    else if(nilai_perjanjian.val() == '')
        notif('error', 'Nilai Rekomendasi Belum Diisi');
    else {
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrinciannonsetoran').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrinciannonsetoran').trigger("reset");
                    $('#modalrinciannonsetoran').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                    $('#tombol-add-rincian').attr('style', 'display:inline !important');
                    setTimeout(function () {
                        $('#modalrinciannonsetoran').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}

function validasinonsetoranumum(){
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');

    if(unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if(keterangan.val() == '')
        notif('error', 'Keterangan belum diisi');
    else {
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrinciannonsetoranumum').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrinciannonsetoranumum').trigger("reset");
                    $('#modalrinciannonsetoranumum').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                    $('#tombol-add-rincian').attr('style', 'display:inline !important');
                    setTimeout(function () {
                        $('#modalrinciannonsetoranumum').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}

function validasinonsetoranpertanggungjawabanuangmuka(){
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var no_invoice = $('#no_invoice');
    var tgl_um = $('#tgl_um');
    var jumlah_um = $('#jumlah_um');

    if(unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if(no_invoice.val() == '')
        notif('error', 'No Invoice belum diisi');
    else if(keterangan.val() == '')
        notif('error', 'Keterangan belum diisi');
    else if(tgl_um.val()=='')
        notif('error', 'Tanggal uang muka belum diisi');
    else if(jumlah_um.val() =='')
        notif('error', 'Jumlah Uang Muka belum diisi')
    else{
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrinciannonsetoranpertanggungjawabanuangmuka').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrinciannonsetoranpertanggungjawabanuangmuka').trigger("reset");
                    $('#modalrinciannonsetoranpertanggungjawabanuangmuka').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                    $('#tombol-add-rincian').attr('style', 'display:inline !important');
                    setTimeout(function () {
                        $('#modalrinciannonsetoranpertanggungjawabanuangmuka').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
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
    
    // $('#nilai_perjanjian').on('blur',function(){
    // alert(totalnilai+'-'+nilairekom)
    // alert(nilai_perjanjian.val() + '..' + totalnilai + '--' + nilairekom);
    


    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (mitra.val() == '')
        notif('error', 'Nama Mitra Belum Diisi');
    else if (no_pks.val() == '')
        notif('error', 'Nomor PKS Belum Diisi');
    else if (tgl_pks.val() == '')
        notif('error', 'Tanggal PKS Belum Dipilih');
    else if (nilai_perjanjian.val() == '')
        notif('error', 'Nilai Rekomendasi Belum Diisi');
    else if (masa_berlaku.val() == '')
        notif('error', 'Masa Kontrak Belum Dipilih');
    else {
        //formrinciansewa
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(nilai_perjanjian.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
                
            }
            else
            {
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrinciansewa').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrinciansewa').trigger("reset");
                            $('#modalrinciansewa').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            $('#tombol-add-rincian').attr('style', 'display:inline !important');
                            setTimeout(function () {
                                $('#modalrinciansewa').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else
        {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasiformuangmuka() {
    var unit_kerja = $('#unit_kerja');
    var no_invoice = $('#no_invoice');
    var tgl_pum = $('#tgl_pum');
    var jumlah_um = $('#jumlah_um');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

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
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(jumlah_um.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
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
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasiformlistrik() {
    var unit_kerja = $('#unit_kerja');
    var lokasi = $('#lokasi');
    var tgl_invoice = $('#tgl_invoice');
    var tagihan = $('#tagihan');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (lokasi.val() == '')
        notif('error', 'Lokasi Belum Diisi');
    else if (tgl_invoice.val() == '')
        notif('error', 'Tanggal Invoice Belum Dipilih');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(tagihan.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
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
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasiformpiutang() {
    var unit_kerja = $('#unit_kerja');
    var pelanggan = $('#pelanggan');
    var tagihan = $('#tagihan');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (pelanggan.val() == '')
        notif('error', 'Nama Pelanggan Belum Diisi');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(tagihan.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
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
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasiformpiutangkaryawan() {
    var unit_kerja = $('#unit_kerja');
    var karyawan = $('#karyawan');
    var pinjaman = $('#pinjaman');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (karyawan.val() == '')
        notif('error', 'Nama Karyawan Belum Diisi');
    else if (pinjaman.val() == '')
        notif('error', 'Jumlah Pinjaman Belum Diisi');
    else {
        //formrinciansewa
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(pinjaman.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrincianpiutangkaryawan').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrincianpiutangkaryawan').trigger("reset");
                            $('#modalrincianpiutangkaryawan').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            setTimeout(function () {
                                $('#modalrincianpiutangkaryawan').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
    }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasihutangtitipan() {
    var unit_kerja = $('#unit_kerja');
    var tanggal = $('#tanggal');
    var sisa_hutang = $('#sisa_hutang');
    var sisa_setor = $('#sisa_setor');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (tanggal.val() == '')
        notif('error', 'Tanggal Belum Dipilih');
    else if (sisa_hutang.val() == '')
        notif('error', 'Sisa Hutang Belum Diisi');
    else if (sisa_setor.val() == '')
        notif('error', 'Jumlah Sisa Setor Belum Diisi');
    else {
        //formrinciansewa
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(sisa_setor.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrincianhutangtitipan').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrincianhutangtitipan').trigger("reset");
                            $('#modalrincianhutangtitipan').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            setTimeout(function () {
                                $('#modalrincianhutangtitipan').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
    }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasipenutupanrekening() {
    var unit_kerja = $('#unit_kerja');
    var nama_bank = $('#nama_bank');
    var nomor_rekening = $('#nomor_rekening');
    var jenis_rekening = $('#jenis_rekening');
    var saldo_akhir = $('#saldo_akhir');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (nama_bank.val() == '')
        notif('error', 'Nama Bank Belum Diisi');
    else if (nomor_rekening.val() == '')
        notif('error', 'Nomor Rekening Belum Diisi');
    else if (jenis_rekening.val() == '')
        notif('error', 'Jenis Rekening Belum Diisi');
    else if (saldo_akhir.val() == '')
        notif('error', 'Saldo Akhir Belum Diisi');
    else {
        //formrinciansewa
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(saldo_akhir.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrincianpenutupanrekening').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrincianpenutupanrekening').trigger("reset");
                            $('#modalrincianpenutupanrekening').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            setTimeout(function () {
                                $('#modalrincianpenutupanrekening').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}
function validasiumum() {
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var jumlah_rekomendasi = $('#jumlah_rekomendasi');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (keterangan.val() == '')
        notif('error', 'Keterangan Belum Diisi');
    else if (jumlah_rekomendasi.val() == '')
        notif('error', 'Nilai Rekomendasi (Rp) Belum Diisi');
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(jumlah_rekomendasi.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
        //formrinciansewa
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrincianumum').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrincianumum').trigger("reset");
                            $('#modalrincianumum').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            setTimeout(function () {
                                $('#modalrincianumum').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}

function validasikontribusi() {
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var jumlah_rekomendasi = $('#jumlah_rekomendasi');
    var tahun = $('#tahun');
    // var totalnilai = parseFloat(($('#total_nilai').val()).replace(/./g,''));
    // var nilairekom = parseFloat(($('input.nilai_rekomendasi').val()).replace(/./g,''));

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (keterangan.val() == '')
        notif('error', 'Keterangan Belum Diisi');
    else if (jumlah_rekomendasi.val() == '')
        notif('error', 'Nilai Rekomendasi (Rp) Belum Diisi');
    else if (tahun.val() == '')
        notif('error', 'Tahun belum diisi');
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(jumlah_rekomendasi.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            }
            else {
        //formrinciansewa
                $.ajax({
                    url: flagsUrl + '/form-rincian-simpan',
                    data: $('#formrinciankontribusi').serialize(),
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (res) {
                        if (res) {
                            // $('#formrinciansewa').reset();
                            $('#formrinciankontribusi').trigger("reset");
                            $('#modalrinciankontribusi').modal('hide');
                            // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                            notif('success', 'Data Rincian Berhasil Di Simpan');
                            gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                            setTimeout(function () {
                                $('#modalrinciankontribusi').modal('show');
                            }, 1500)
                        }
                        else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        }
        else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        }
        else {
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
                        gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
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
}

function validasinonsetoranperjanjiankerjasama(){
    var unit_kerja = $('#unit_kerja');
    var no_pks = $('#no_pks');
    var tgl_pks = $('#tgl_pks');
    var masa_berlaku = $('#masa_berlaku');
    var keterangan = $('#keterangan');
    
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (no_pks.val() == '')
        notif('error', 'Nomor PKS Belum Diisi');
    else if (tgl_pks.val() == '')
        notif('error', 'Tanggal PKS Belum Dipilih');
    else if (masa_berlaku.val() == '')
        notif('error', 'Masa Kontrak Belum Dipilih');
    else if(keterangan.val() == '')
        notif('error', 'Keterangan belum diisi');
    else {
        $.ajax({
            url: flagsUrl + '/form-rincian-simpan',
            data: $('#formrinciannonsetoranperjanjiankerjasama').serialize(),
            type: 'POST',
            datatype: 'JSON',
            success: function (res) {
                if (res) {
                    // $('#formrinciansewa').reset();
                    $('#formrinciannonsetoranperjanjiankerjasama').trigger("reset");
                    $('#modalrinciannonsetoranperjanjiankerjasama').modal('hide');
                    // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    notif('success', 'Data Rincian Berhasil Di Simpan');
                    gettablerincianold(res.jenis, res.idtemuan, res.idrekomendasi);
                    $('#tombol-add-rincian').attr('style', 'display:inline !important');
                    setTimeout(function () {
                        $('#modalrinciannonsetoranperjanjiankerjasama').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
                    console.log(res);
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
                    gettablerincianold(jenis, res.idtemuan, res.idrekomendasi);
                }
            })
        } 
    })
}

function aktifinrincian(val)
{
    // alert(val)
    if(val!='')
    {
        $('#tombol-add-rincian').attr('style','display:inline');
    }
    else
    {
        $('#tombol-add-rincian').attr('style','display:none');
    }
}

function publishlhp(idlhp) {
    var tahun = $('#tahun').val();
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Mempublish Data LHP ini",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Publish'
        ],
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/publish-lhp/' + idlhp,
                success: function () {
                    swal({
                        title: 'Berhasil!',
                        text: 'Publish Data LHP Berhasil',
                        icon: 'success'
                    }).then(function () {
                        location.href = flagsUrl + '/data-lhp/' + tahun;
                    });
                }
            });
        }
    });
}

function detailtljunior(idrekom) {
    $('#detail-tl-rincian').load(flagsUrl +'/detail-tl-rincian/'+idrekom,function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#detailtljunior').modal('show')
}