function loaddata() {
    // var thn = $('#tahun').val();
    // var tanggal_awal = $('#tanggal_awal').val();
    // var tanggal_akhir = $('#tanggal_akhir').val();
    var pemeriksa = $('#pemeriksa').val();
    var no_lhp = $('#no_lhp').val();
    var no_temuan = $('#no_temuan').val();
    var no_rekomendasi = $('#no_rekomendasi').val();
    var status_rekomendasi = $('#status_rekomendasi').val();
    // tgl_awal: tanggal_awal, tahun: thn,  tgl_akhir: tanggal_akhir,

    $.ajax({
        url: flagsUrl+'/data-tindaklanjut-unitkerja-list',
        type: 'POST',
        data: { pemeriksa: pemeriksa, no_lhp:no_lhp, rekomid: no_rekomendasi, temuan_id: no_temuan, statusrekom: status_rekomendasi },
        beforeSend: function(){
            // console.log(this.data);
        },
        success: function (res) {
            $('#data').html(res);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('ehehe ' + jqXHR + ' ' + textStatus + ' ' + errorThrown);
        }
    });
    
    // $('#data').load(flagsUrl+'/data-tindaklanjut-list/{{$tahun}}',function(){
    //     $('#table').DataTable();
    // });
}

function getdata(tahun) {
    location.href = flagsUrl + '/data-tindaklanjut-unitkerja/' + tahun;
}

function settglpenyelesaian(temuan_id, rekom_id) {
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menentukan Tanggal Penyelesaian.\nKarena Tidak Akan dapat dirubah Kembali Jika Telah Di SET.",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            var tgl = $('#tanggal_penyelesaian_' + temuan_id + '_' + rekom_id).val();
            $.ajax({
                url: flagsUrl + '/set-tgl-penyelesaian/' + temuan_id + '/' + rekom_id + '/' + tgl,
                success: function (res) {
                    if (res) {
                        $('#tgl_penyelesaian_' + temuan_id + '_' + rekom_id).html(res);
                        $('#aksi_rekomendasi_'+ temuan_id + '_' + rekom_id).attr('style','display:inline-block !important;');
                        $('#jlh_tl_' + temuan_id + '_' + rekom_id).html('<span class="label label-danger fz-sm">0</span>');
                    }
                }
            });
        } else {

        }
    });
}

$('.btn-add').on('click',function(){
    var id = $(this).data('value')
    var d=id.split('__')
    var temuan_id=d[1];
    var rekom_id=d[2];
    var rrk=rekom_id.split('_')
    var lhp_id=d[0];
    closerightdiv();
    $('#konten-add-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-add/' + lhp_id + '/' + temuan_id + '/' + rekom_id + '/' + rrk[0],function(){
        CKEDITOR.replace('action_plan');
    });
});

function othertemuan(id)
{
    closerightdiv()
    var d = id.split('__')
    var temuan_id = d[1];
    var rekom_id = d[2];
    var lhp_id = d[0];
    $('#konten-add-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-add/' + lhp_id + '/' + temuan_id + '/' + rekom_id,function(){
        CKEDITOR.replace('action_plan');
    });
}

function othertemuan_unitkerja(id)
{
    closerightdiv()
    var d = id.split('__')
    var temuan_id = d[1];
    var rekom_id = d[2];
    var lhp_id = d[0];
    $('#konten-add-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-add/' + lhp_id + '/' + temuan_id + '/' + rekom_id,function(){
        CKEDITOR.replace('action_plan');
    });
}

function jenisSetoranSelected(val){
    if(val != 'Bank'){
        document.getElementById('bank_tujuan').disabled = true;
        document.getElementById('no_ref').disabled = true;
        document.getElementById('jenis_rekening').disabled = true;
    }else{
        document.getElementById('bank_tujuan').disabled = false;
        document.getElementById('no_ref').disabled = false;
        document.getElementById('jenis_rekening').disabled = false;
    }
}


function updaterincian(jenis,idtemuan,rekom_id)
{
    gettablerincian(jenis, idtemuan, rekom_id) 
    $('#close-btn').attr('style','display:inline');
    $('#left-div').removeClass('col-md-12');
    $('#left-div').addClass('col-md-6');
    $('#right-div').removeClass('col-md-0');
    $('#right-div').addClass('col-md-6');
    $('#modal-size').attr({ 'style': 'width:90% !important' });
}
function closerightdiv()
{
    $('#right-div').html('');
    $('#close-btn').attr('style','display:none');
    $('#left-div').removeClass('col-md-6');
    $('#left-div').addClass('col-md-12');
    $('#right-div').removeClass('col-md-6');
    $('#right-div').addClass('col-md-0');
    $('#modal-size').attr({ 'style': 'unset' });
}

function updaterincian_unitkerja(rekom_id,idtemuan,jenis) {
    gettablerincian_unitkerja_lain(jenis, idtemuan, rekom_id)
    $('#modalrincian').modal('show');
    // $('#close-btn').attr('style', 'display:inline');
    // $('#left-div').removeClass('col-md-12');
    // $('#left-div').addClass('col-md-6');
    // $('#right-div').removeClass('col-md-0');
    // $('#right-div').addClass('col-md-6');
    // $('#modal-size').attr({ 'style': 'width:90% !important' });
    
}
function gettablerincian_unitkerja(jenis, idtemuan, idrekom) {
    $('#right-div').load(flagsUrl + '/load-table-rincian-unitkerja/' + jenis + '/' + idtemuan + '/' + idrekom, function(){
        $('#table-tl-rincian-'+idrekom).DataTable({
            responsive: true
        });
    });
}
function gettablerincian_unitkerja_lain(jenis, idtemuan, idrekom) {
    $('#table-rincian').load(flagsUrl + '/load-table-rincian-unitkerja/' + jenis + '/' + idtemuan + '/' + idrekom, function(){
        $('#table-rincian-'+jenis).DataTable({
            responsive: true,
            "bAutoWidth": false
        });
    });
}
function gettablerincian(jenis, idtemuan, idrekom) {
    $('#right-div').load(flagsUrl + '/load-table-rincian/' + jenis + '/' + idtemuan + '/' + idrekom, function () {
        $('#table-tl-rincian-'+idrekom).DataTable({
            responsive: true
        });
    });
}

function rekomaddnew(idtemuan) {
    $('#id_temuan_rekom').val(idtemuan);
}

function addtindaklanjut(jenis, idtemuan, idrekom, id, idPic = -1) {
    var idlhp = $('#idlhp').val();
    // alert(idlhp)
    // idrekom = idform;

    var selectedPIC = $("select[name='pic_1'").find('option:selected').val();
    var selectedPIC2 = [];
    $("select[name='pic_2[]'").find('option:selected').each(function(){
        selectedPIC2.push($(this).val());
    });
    if(selectedPIC == '' && id == -1)
        if($('#modal-update-rincian').hasClass('in')){
            selectedPIC2.push(idPic);
        }else{
            return notif('error', 'Harap pilih PIC terlebih dahulu');
        }
    if(selectedPIC != ''){
        selectedPIC2.push(selectedPIC);
    }
    if(idPic != -1){
        selectedPIC2.push(idPic);
    }
    if (jenis == 'sewa') {
        $('#formrinciansewa').attr('action', flagsUrl + '/rincian-simpan/'+idlhp);
        $('#form-rincian-sewa').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciansewa').modal('show')
    }
    else if (jenis == 'uangmuka') {
        $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-uangmuka').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianuangmuka').modal('show')
    }
    else if (jenis == 'listrik') {
        $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-listrik').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianlistrik').modal('show')
    }
    else if (jenis == 'piutang') {
        $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutang').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutang').modal('show')
    }
    else if (jenis == 'piutangkaryawan') {
        $('#formrincianpiutangkaryawan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutangkaryawan').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutangkaryawan').modal('show')
    }
    else if (jenis == 'hutangtitipan') {
        $('#formrincianhutangtitipan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-hutangtitipan').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianhutangtitipan').modal('show')
    }
    else if (jenis == 'penutupanrekening') {
        $('#formrincianpenutupanrekening').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-penutupanrekening').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpenutupanrekening').modal('show')
    }
    else if (jenis == 'umum') {
        $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-umum').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianumum').modal('show')
    }else if (jenis == 'kontribusi'){
        $('#formrinciankontribusi').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-kontribusi').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciankontribusi').modal('show')
    }
    else if(jenis == 'nonsetoranperjanjiankerjasama'){
        $('#formrinciannonsetoranperjanjiankerjasama').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-nonsetoranperjanjiankerjasama').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoranperjanjiankerjasama').modal('show')
    }
    else if(jenis == 'nonsetoran'){
        $('#formrinciannonsetoran').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-modalrinciannonsetoran').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoran').modal('show')
    }
    else if(jenis == 'nonsetoranumum'){
        $('#formrinciannonsetoranumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-modalrinciannonsetoranumum').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciannonsetoranumum').modal('show')
    }
    else if(jenis == 'nonsetoranpertanggungjawabanuangmuka'){
        $('#formrinciannonsetoranpertanggungjawabanuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-modalrinciannonsetoranpertanggungjawabanuangmuka').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id + '/' + selectedPIC2, function () {
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
                        if(res.id==-1)
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
                        if(res.id==-1)
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
    // else if(no_invoice.val() == '')
    //     notif('error', 'No Invoice belum diisi');
    // else if(keterangan.val() == '')
    //     notif('error', 'Keterangan belum diisi');
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
                        if(res.id==-1)
                            $('#modalrinciannonsetoranpertanggungjawabanuangmuka').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
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
    // else if (tgl_pks.val() == '')
    //     notif('error', 'Tanggal PKS Belum Dipilih');
    else if (masa_berlaku.val() == '')
        notif('error', 'Masa Kontrak Belum Dipilih');
    // else if(keterangan.val() == '')
    //     notif('error', 'Keterangan belum diisi');
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
                        if(res.id==-1)
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

function validasikontribusi() {
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var jumlah_rekomendasi = $('#jumlah_rekomendasi');
    var tahun = $('#tahun');
    var idRekomendasi = $('#idrekomendasi').val();

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (tahun.val() == '')
        notif('error', 'Tahun belum diisi');
    else if (keterangan.val() == '')
        notif('error', 'Keterangan Belum Diisi');
    else if (jumlah_rekomendasi.val() == '')
        notif('error', 'Nilai Rekomendasi (Rp) Belum Diisi');
    else if (! /^\d{4}$/.test(tahun.val()) ) {
        notif('error', 'Format tahun salah');
    }
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(jumlah_rekomendasi.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                                if(res.id==-1)
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
                            if(res.id==-1)
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

function validasiformsewa() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var mitra = $('#mitra');
    var no_pks = $('#no_pks');
    var tgl_pks = $('#tgl_pks');
    var nilai_perjanjian = $('#nilai_perjanjian');
    var masa_berlaku = $('#masa_berlaku');
    var idRekomendasi = $('#idrekomendasi').val();
    
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (mitra.val() == '')
        notif('error', 'Nama Mitra Belum Diisi');
    else if (nilai_perjanjian.val() == '')
        notif('error', 'Nilai Rekomendasi Belum Diisi');
    // else if (masa_berlaku.val() == '')
    //     notif('error', 'Masa Kontrak Belum Dipilih');
    else {
        var nil = parseFloat(nilai_perjanjian.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrinciansewa').attr('action', flagsUrl + '/rincian-simpan/'+idlhp);
                        if(res.id==-1)
                            $('#modalrinciansewa').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiformuangmuka() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var no_invoice = $('#no_invoice');
    var tgl_pum = $('#tgl_pum');
    var jumlah_um = $('#jumlah_um');
    var idRekomendasi = $('#idrekomendasi').val();
    
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    // else if (no_invoice.val() == '')
    //     notif('error', 'Nomor Invoice Belum Diisi');
    // else if (tgl_pum.val() == '')
    //     notif('error', 'Tanggal PUM Belum Dipilih');
    else if (jumlah_um.val() == '')
        notif('error', 'Jumlah UM Belum Diisi');
    else {
        var nil = parseFloat(jumlah_um.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                        $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
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
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var lokasi = $('#lokasi');
    var tgl_invoice = $('#tgl_invoice');
    var tagihan = $('#tagihan');
    var idRekomendasi = $('#idrekomendasi').val();
    
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (lokasi.val() == '')
        notif('error', 'Lokasi Belum Diisi');
    else if (tgl_invoice.val() == '')
        notif('error', 'Tanggal Invoice Belum Dipilih');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        var nil = parseFloat(tagihan.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                        $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
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
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var pelanggan = $('#pelanggan');
    var tagihan = $('#tagihan');
    var idRekomendasi = $('#idrekomendasi').val();

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (pelanggan.val() == '')
        notif('error', 'Nama Pelanggan Belum Diisi');
    else if (tagihan.val() == '')
        notif('error', 'Jumlah Tagihan Belum Diisi');
    else {
        var nil = parseFloat(tagihan.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                        $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
                            $('#modalrincianpiutang').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiformpiutangkaryawan() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var karyawan = $('#karyawan');
    var pinjaman = $('#pinjaman');
    var idRekomendasi = $('#idrekomendasi').val();

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (karyawan.val() == '')
        notif('error', 'Nama Karyawan Belum Diisi');
    else if (pinjaman.val() == '')
        notif('error', 'Jumlah Pinjaman Belum Diisi');
    else {
        var nil = parseFloat(pinjaman.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrincianpiutangkaryawan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
                            $('#modalrincianpiutangkaryawan').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasihutangtitipan() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var tanggal = $('#tanggal');
    var sisa_hutang = $('#sisa_hutang');
    var sisa_setor = $('#sisa_setor');
    var idRekomendasi = $('#idrekomendasi').val();

    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (tanggal.val() == '')
        notif('error', 'Tanggal Belum Dipilih');
    else if (sisa_hutang.val() == '')
        notif('error', 'Sisa Hutang Belum Diisi');
    else if (sisa_setor.val() == '')
        notif('error', 'Jumlah Sisa Setor Belum Diisi');
    else {
        var nil = parseFloat(sisa_hutang.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrincianhutangtitipan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
                            $('#modalrincianhutangtitipan').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasipenutupanrekening() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var nama_bank = $('#nama_bank');
    var nomor_rekening = $('#nomor_rekening');
    var jenis_rekening = $('#jenis_rekening');
    var saldo_akhir = $('#saldo_akhir');

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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrincianpenutupanrekening').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
                            $('#modalrincianpenutupanrekening').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function validasiumum() {
    var idlhp = $('#idlhp').val();
    var unit_kerja = $('#unit_kerja');
    var keterangan = $('#keterangan');
    var jumlah_rekomendasi = $('#jumlah_rekomendasi');
    var idRekomendasi = $('#idrekomendasi').val();
    
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (keterangan.val() == '')
        notif('error', 'Keterangan Belum Diisi');
    else if (jumlah_rekomendasi.val() == '')
        notif('error', 'Nilai Rekomendasi (Rp) Belum Diisi');
    else {
        var nil = parseFloat(jumlah_rekomendasi.val().replace(/\./g, ""));
        if(!isValidNilai(nil) && idRekomendasi != '2')
            return notif('error', 'Nilai melebihi total rekomendasi');
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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
                        if(res.id==-1)
                            $('#modalrincianumum').modal('show');
                    }, 1500)
                }
                else
                    notif('error', 'Data Rincian Gagal Disimpan');
            }
        });
    }
}
function hapusrincian(id, jenis) {
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
                url: flagsUrl + '/form-rincian-hapus/' + id + '/' + jenis,
                dataType: 'JSON',
                success: function (res) {
                    swal("Berhasil", "Data Rincian Berhasil Di Hapus", "success");
                    gettablerincian(jenis, res.idtemuan, res.idrekomendasi);
                }
            })
        }
    })
}

$('.select').select2();
//---------------
function listtindaklanjutrincian(idrincian,jenis, totalNilai = -1){
    var idtl = $('#idformtindaklanjut').val();
    $('#list-tindaklanjut-rincian').load(flagsUrl + '/list-tindaklanjut-rincian/' + idrincian + '/' + jenis+'/'+idtl +'/'+totalNilai, function () {
        $('#table-rincian-dokumen').DataTable({
            responsive: true,
            "bAutoWidth": false,
            "bDestroy": true
        });
    });
    $('#listtindaklanjutrincian').modal('show');
}
function listrincianrekomendasi(idrekomendasi,jenis){
    $('#list-tindaklanjut-rincian').load(flagsUrl + '/list-rincian-rekomendasi/' + idrekomendasi + '/' + jenis, function () {
        $('.table').DataTable({
            responsive: true,
            "bAutoWidth": false,
            "bDestroy": true
        });
    });
    $('#listtindaklanjutrincian').modal('show');
}
function hapusrinciantindaklanjut(id, idrincian, jenis){
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menghapus Data ini",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Hapus'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $('#listtindaklanjutrincian').modal('hide');
            $.ajax({
                url: flagsUrl + '/hapus-rincian-tindaklanjut/' + idrincian,
                success: function (res) {
                    swal({
                        title: 'Berhasil!',
                        text: 'Hapus Data Rincian Berhasil',
                        icon: 'success'
                    }).then(function () {
                        listtindaklanjutrincian(id, jenis) 
                    });
                }
            });
        } else {

        }
    });
}
function addtindaklanjutrincian(idrincian,jenis, totalNilai, title, isUpdate = false, idtindaklanjut = -1){
    var idform = $('#idformtindaklanjut').val();
    document.getElementById('modaltitleaddtindaklanjutrincian').textContent = title;
    $('#form-tindaklanjut-rincian').load(flagsUrl +'/form-tindaklanjut-rincian/'+idrincian+'/'+jenis,function(){
        $('#bank_tujuan').select2({
            dropdownParent: $("#addtindaklanjutrincian .modal-content")
          });
        $('.nominal').on('keyup', function (e) {
            $(this).val(format($(this).val()));
        });
        $('#idform').val(idform);
        $('#isupdate').val('0');
        $('#totalnilai').val(totalNilai);
        $('.validasirp').on('keyup', function() {
            var nilai = parseFloat(this.value.replace(/\./g, ""));
            var totalNilai = parseFloat($('#totalnilai').val().replace(/\./g, ""));
            if (nilai > totalNilai) {
                return notif('error', 'Nilai melebihi total rekomendasi');
            }
        });

        if(isUpdate){
            $.ajax({
                url: flagsUrl + '/get-tindak-lanjut-rincian/' + idtindaklanjut,
                dataType: 'JSON',
                success: function(res){
                    $('#isupdate').val('1');
                    $('#idtindaklanjut').val(res[0].id);
                    $('#tanggal').val(res[0].tanggal);
                    if(jenis=='kontribusi' || jenis=='sewa' || jenis=='listrik' || jenis=='piutang' || jenis=='piutangkaryawan' || jenis=='hutangtitipan'){
                        $('#tindak_lanjut').val(res[0].tindak_lanjut_rincian);
                        $('#nilai').val(res[0].nilai);
                        $('#jenis_setoran').val(res[0].jenis_setoran);
                        $('#bank_tujuan').val(res[0].bank_tujuan).change();
                        $('#jenis_rekening').val(res[0].jenis_rekening);
                        $('#no_ref').val(res[0].no_referensi);
                    }else if(jenis == 'uangmuka'){
                        $('#tindak_lanjut').val(res[0].tindak_lanjut_rincian);
                        $('#nilai').val(res[0].nilai);
                        $('#jenis_setoran').val(res[0].jenis_setoran);
                        $('#bank_tujuan').val(res[0].bank_tujuan).change();
                        $('#jenis_rekening').val(res[0].jenis_rekening);
                        $('#no_ref').val(res[0].no_referensi);
                    }else if(jenis == 'penutupanrekening'){
                        $('#tindak_lanjut').val(res[0].tindak_lanjut_rincian);
                        $('#tanggal_penutupan_rekening').val(res[0].tanggal_penutupan);
                        $('#saldo_akhir').val(res[0].saldo_akhir);
                        $('#no_rekening_pemindahan_saldo').val(res[0].no_rek_pemindah_saldo);
                        $('#nama_rekening_pemindah_saldo').val(res[0].nama_rekening_pemindahan_saldo);
                        $('#no_ref').val(res[0].no_referensi);
                    }else if(jenis == 'umum'){
                        $('#tindak_lanjut').val(res[0].tindak_lanjut_rincian);
                        $('#nilai').val(res[0].nilai);
                        $('#jenis_setoran').val(res[0].jenis_setoran);
                        $('#bank_tujuan').val(res[0].bank_tujuan).change();
                        $('#jenis_rekening').val(res[0].jenis_rekening);
                        $('#no_ref').val(res[0].no_referensi);
                    }else if(jenis == 'nonsetoranperjanjiankerjasama'){
                        $('#no_pks').val(res[0].no_pks);
                        $('#tanggal_pks').val(res[0].tanggal_pks);
                        $('#periode_perpanjangan_pks').val(res[0].periode_pks);
                    }else if(jenis == 'nonsetoran' || jenis == 'nonsetoranpertanggungjawabanuangmuka' || jenis == 'nonsetoranumum'){
                        $('#tindak_lanjut').val(res[0].tindak_lanjut_rincian);
                        $('#nilai').val(res[0].nilai);
                    }
                    
                    var dokPendukung = $.parseJSON(res[0].dokumen_pendukung);
                    for(var i = 0; i < dokPendukung.length; i++){
                        var documentName = dokPendukung[i].file.replace('public/dokumen/','');
                        if(i == 0){
                            $('#nama_file_1').val(documentName.replace(/\.[^/.]+$/, ""));
                        }else {
                            var wrapper = $('.field_wrapper');
                            var fieldHTML = '<div class="form-group" style="margin-bottom:10px;">\
                                    <label for="exampleTextInput1" class="col-sm-3 control-label text-right"></label>\
                                    <div class="col-sm-4">\
                                        <input type="text" class="form-control"  class="form-control" value="'+documentName.replace(/\.[^/.]+$/, "")+'" name="nama_file_'+i+'"  placeholder="Nama File" id="nama_file_'+i+'">\
                                    </div>\
                                    <div class="col-sm-5">\
                                        <input type="file" class="form-control"  class="form-control" onchange="insertFile('+i+')" id="add_dokumen_'+i+'" name="add_dokumen_'+i+'"  placeholder="File Pendukung" accept=".doc,.docx,.pdf,.xls,.xlsx">\
                                    </div>\
                                </div> ';
                            $(wrapper).append(fieldHTML);
                        }
                        
                        $('#total_file').val(i);
                    }
                }
            })
        }
    });
    $('#addtindaklanjutrincian').modal('show');
}

function hapustindaklanjutrincian(idrincian, jenis, idtemuan,rekom_id){
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menghapus Data ini",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Hapus'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $('#modalreview').modal('hide');
            $.ajax({
                url: flagsUrl + '/hapus-rincian/' + idrincian + '/' + jenis,
                success: function (res) {
                    swal({
                        title: 'Berhasil!',
                        text: 'Hapus Data Rincian Berhasil',
                        icon: 'success'
                    }).then(function () {
                        gettablerincian_unitkerja(jenis, res.idtemuan, res.rekom_id) 
                    });
                }
            });
        } else {

        }
    });
}

function simpanrincianunitkerja()
{
    
    // $.ajax({
    //     data: new FormData($('#form_tindaklanjut_rincian')),
    //     url : flagsUrl + '/simpan-tindaklanjut-rincian',
    //     method : 'POST',
    //     dataType : 'JSON',
    //     contentType: false,
    //     cache: false,
    //     processData: false,
    //     success : function(res){
    //         // gettablerincian_unitkerja(jenis, res.temuan_id, res.rekomendasi_id) 
    //     }
    // });
}

$(document).ready(function(){
    event.preventDefault();
    $('#form_tindaklanjut_rincian').ajaxForm({
        // url: flagsUrl + '/simpan-tindaklanjut-rincian',
        // method: "POST",
        // data: new FormData(this),
        // dataType: 'JSON',
        // contentType: false,
        // cache: false,
        // processData: false,
        beforeSend: function() {
            $('.progress-bar').text('0%');
            $('.progress-bar').css('width', '0%');
        },
        uploadProgress: function(event, position, total, percentComplete){
            $('.progress-bar').text(percentComplete + '%');
            $('.progress-bar').css('width', percentComplete + '%');
        },
        success: function (res) {
            console.log(res);
            if(res.errors){
                notif('error', res.errors);
                $('.progress-bar').text('0%');
                $('.progress-bar').css('width', '0%');
            }else{
                $('#addtindaklanjutrincian').modal('hide');
                swal({
                    title: 'Berhasil!',
                    text: 'Update Data Rincian Tindak Lanjut Berhasil Di Tambah',
                    icon: 'success'
                }).then(function () {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    if($('#isupdate').val() == '1'){
                        listtindaklanjutrincian(res.idrincian, res.jenis) 
                    }else{
                        gettablerincian_unitkerja(res.jenis, res.temuan_id, res.rekomendasi_id) 
                        $('#jlh-rincian-' + res.rekomendasi_id).load(flagsUrl + '/jumlah-rincian/' + res.temuan_id+'/'+res.rekomendasi_id);
                    }
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            notif('error', errorThrown);
            $('.progress-bar').text('0%');
            $('.progress-bar').css('width', '0%');
            console.log(jqXHR + '\n'+textStatus+'\n'+errorThrown);
        }
    })
});
function updatestatusrinciantindaklanjut(idtidaklanjut, status){
    $.ajax({
        url: flagsUrl + '/update-status-rincian/'+idtidaklanjut+'/' + status,
        success: function(res){
            swal("Berhasil", "Status telah dirubah", "success");
        },
        error: function(jqXHR, textStatus, errorThrown){
            notif('error', errorThrown);
            console.log(jqXHR + '\n'+textStatus+'\n'+errorThrown);
        }
    })
}

$('#form_tindaklanjut_edit').on('submit', function (event) {
    $('#modaledittindaklanjut').modal('hide');
    event.preventDefault();
    $.ajax({
        url: flagsUrl + '/tindaklanjut-unitkerja-edit-simpan',
        method: "POST",
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (res) {
            swal({
                title: 'Berhasil!',
                text: 'Data Tindak Lanjut Berhasil Di Edit',
                icon: 'success'
            }).then(function () {
                $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/' + res.idrekomendasi, function () {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                // gettablerincian_unitkerja(res.jenis, res.temuan_id, res.rekomendasi_id) 
            });
            
        }
    })
});

function detailtindaklanjut(idrekomendasi) {

    $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/'+idrekomendasi,function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#lihattindaklanjut').modal('show');
}

function edittl(tl_id,rekomendasi_id,temuan_id,lhp_id)
{

    $('#konten-edit-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-edit/' + lhp_id + '/' + temuan_id + '/' + rekomendasi_id + '/' + tl_id, function () {
        CKEDITOR.replace('action_plan');
    });
    
    $('#modaledittindaklanjut').modal('show');
}
function hapustl(tl_id, idrekomendasi)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menghapus Data Tindak Lanjut ini ?",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Hapus'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/hapus-tindak-lanjut/' + tl_id,
                success: function (res) {
                    if(res==1)
                    {
                        swal({
                            title: 'Berhasil!',
                            text: 'Hapus Data Tindak Lanjut Berhasil',
                            icon: 'success'
                        }).then(function () {
                            $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/' + idrekomendasi, function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        });
                    }
                    else
                    {
                        swal({
                            title: 'Gagal!',
                            text: 'Hapus Data Tindak Lanjut Tidak Berhasil',
                            icon: 'error'
                        })
                    }
                }
            });
        } else {

        }
    });
}

function listrinciantl(idrekomendasi,idunitkerja,idtl)
{
    $('#list-rincian').load(flagsUrl +'/list-rincian/'+idrekomendasi+'/'+idunitkerja+'/'+idtl,function(){
        
    });
    $('#listrinciantl').modal('show');
}



function publishpic1(idrekomendasi)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Mempublish Rekomendasi Ini Ke Auditor ?",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Publish'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/publish-rekomendasi-to-auditor-junior/' + idrekomendasi,
                success: function (res) {
                    if (res == 1) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Publish Rekomendasi Berhasil',
                            icon: 'success'
                        }).then(function () {
                            // $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/' + idrekomendasi, function () {
                            //     $('[data-toggle="tooltip"]').tooltip();
                            // });
                            location.href = flagsUrl +'/data-tindaklanjut-unitkerja/'+$('#tahun').val()
                        });
                    }
                    else {
                        swal({
                            title: 'Gagal!',
                            text: 'Publish Rekomendasi Tidak Berhasil',
                            icon: 'error'
                        })
                    }
                }
            });
        } else {

        }
    });
}
function publishpic2(idrekomendasi)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Mempublish Rekomendasi Ini Ke PIC 1 ?",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Publish'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/publish-rekomendasi-to-pic1/' + idrekomendasi,
                success: function (res) {
                    if (res == 1) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Publish Rekomendasi Berhasil',
                            icon: 'success'
                        }).then(function () {
                            // $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/' + idrekomendasi, function () {
                            //     $('[data-toggle="tooltip"]').tooltip();
                            // });
                            location.href = flagsUrl + '/data-tindaklanjut-unitkerja/' + $('#tahun').val()
                        });
                    }
                    else {
                        swal({
                            title: 'Gagal!',
                            text: 'Publish Rekomendasi Tidak Berhasil',
                            icon: 'error'
                        })
                    }
                }
            });
        } else {

        }
    });
}

function reviewtindaklanjut(idrekomendasi) {
    $('#form-detail-tindaklanjut').load(flagsUrl + '/detail-tindaklanjut-pic1/' + idrekomendasi, function () {
        // var catatan_monev = document.getElementById('catatan_monev');
        // var review_spi = document.getElementById('review_spi');
        // alert(catatan_monev)
        CKEDITOR.replace('catatan_monev');
        CKEDITOR.replace('txt_rangkuman_rekomendasi');
        $('#tahun_thn').val($('#tahun').val());
        // CKEDITOR.replace('review_spi');
        // $('#table-tl-detail').DataTable();
    });
    $('#modaldetailtindaklanjut').modal('show');
}

function validasireview()
{
    // var catatan_monev=$('#catatan_monev').val();
    var catatan_monev = CKEDITOR.instances.catatan_monev.getData();
    var idrekomendasi = $('#idrekomendasi').val();
    var tgl_penyelesaian = $('#tgl_selesai').val();
    // alert(tgl_penyelesaian);
    var tahun = $('#tahun').val();
    if(catatan_monev=='')
        notif('error', 'Catatan Monev Belum Diisi');
    else 
    {
        swal({
            title: "Apakah Anda Yakin ?",
            text: "Ingin Menyimpan Review Ini?",
            icon: "warning",
            buttons: [
                'Tidak!',
                'Ya, Simpan'
            ],
            dangerMode: true,
        }).then(function (isConfirm) {
            
            if (isConfirm) {
                $('#tindaklanjut-pic1').submit();
                // $.ajax({
                //     url: flagsUrl + '/review-pic1-simpan',
                //     method:'POST',
                //     data: {review:catatan_monev,idrekom:idrekomendasi,tgl:tgl_penyelesaian},
                //     success: function (res) {
                //         $('#modaldetailtindaklanjut').modal('hide');
                //         if (res == 1) {
                //             swal({
                //                 title: 'Berhasil!',
                //                 text: 'Review Tindak Lanjut Berhasil Disimpan',
                //                 icon: 'success'
                //             }).then(function () {
                //                 // $('#table-data-tindaklanjut').load(flagsUrl + '/table-data-tindaklanjut/' + idrekomendasi, function () {
                //                 //     $('[data-toggle="tooltip"]').tooltip();
                //                 // });
                //                 location.href = flagsUrl + '/data-tindaklanjut-unitkerja/' + tahun
                //             });
                //         }
                //         else {
                //             swal({
                //                 title: 'Gagal!',
                //                 text: 'Review Tindak Lanjut Gagal Disimpan',
                //                 icon: 'error'
                //             })
                //         }
                //     }
                // });
            }
        });
        /*
        
        
        
        */
    }
}

function rangkumantindaklanjut(idrekomendasi) {
    $('#list-rangkuman').load(flagsUrl + '/list-rangkuman/' + idrekomendasi), function () {
        CKEDITOR.replace('txt_rangkuman_rekomendasi');
        $('#table-tl-detail').DataTable();
    };
    $('#rangkuman-tindaklanjut-rekomendasi').modal('show');
}

$('#form_list_rangkuman').on('submit', function (event) {
    event.preventDefault();
    var rangkuman_rekomendasi = $('#txt_rangkuman_rekomendasi').val();
    // var rangkuman_rekomendasi = CKEDITOR.instances.rangkuman_rekomendasi.getData();
    var tahun = $('#tahun').val();
    if (rangkuman_rekomendasi == '')
        notif('error', 'Catatan Monev Belum Diisi');
    else {
        
        $.ajax({
            url: flagsUrl + '/rangkuman-simpan',
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res == 1) {
                    swal({
                        title: 'Berhasil!',
                        text: 'Rangkumamn Berhasil Disimpan',
                        icon: 'success'
                    }).then(function () {
                        location.href = flagsUrl + '/data-tindaklanjut-unitkerja/' + tahun
                    });
                }
                else {
                    swal({
                        title: 'Gagal!',
                        text: 'Rangkumamn Gagal Disimpan',
                        icon: 'error'
                    })
                }
            }
        })
    }
});
//form_list_rangkuman
x=1;
function add_kolom()
{
    x++;
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="col-sm-6">\
                    <input type="text" name="nama_file_'+x+'" id="nama_file_'+x+'" class="form-control" placeholder="Nama File">\
                </div>\
                <div class="col-sm-5">\
                    <input type="file" class="form-control" onchange="uploadfile(this,'+x+')" id="add_dokumen_'+ x + '" name="add_dokumen_'+ x + '" placeholder="Dokumen Pendukung" accept=".doc,.docx,.pdf,.xls,.xlsx">\
                    <span style="text-style:italic;font-weight:bold;"></span>\
                </div>\
                <div class="col-sm-1">\
                    <div class="btn btn-success" id="loading-'+ x + '" style="display:none"><i class="fa fa-check"></i></div>\
                    <div class="btn btn-success" id="ok-'+ x + '" style="display:none"><i class="fa fa-check"></i></div>\
                    <div class="btn btn-danger" id="fail-'+ x + '" style="display:none"><i class="fa fa-trash"></i></div>\
                </div>'; //New input field html 
    $(wrapper).append(fieldHTML); //Add field html
}
rincianFileQty=1;
function rincianAddColumn(){
    rincianFileQty++;
    var wrapper = $('.field_wrapper');
    var fieldHTML = '<div class="form-group" style="margin-bottom:10px;">\
            <label for="exampleTextInput1" class="col-sm-3 control-label text-right"></label>\
            <div class="col-sm-4">\
                <input type="text" class="form-control"  class="form-control"  name="nama_file_'+rincianFileQty+'"  placeholder="Nama File" id="nama_file_'+rincianFileQty+'">\
            </div>\
            <div class="col-sm-5">\
                <input type="file" class="form-control"  class="form-control" onchange="insertFile('+rincianFileQty+')" id="add_dokumen_'+rincianFileQty+'" name="add_dokumen_'+rincianFileQty+'"  placeholder="File Pendukung" accept=".doc,.docx,.pdf,.xls,.xlsx">\
            </div>\
        </div> ';
    $(wrapper).append(fieldHTML);
}

function insertFile(val){
    $("#total_file").val(val);
}

function uploadfile(val, id) {

    var idlhp = $('#idlhp').val();
    var temuan_id = $('#temuan_id').val();
    var rekomendasi_id = $('#rekomendasi_id').val();
    var idformtl = $('#form_tl').val();
    var namafile = $('#nama-file-'+id).val();
    var file_data = val.files[0];
    var csrf_token = $('#csrf_token').val();
    if(namafile=='')
    {
        notif('error', 'Nama File Tindak Lanjut Belum Diisi');
        $('#nama-file-' + id).focus();
        $('#add-dokumen-'+id).val('');
    }
    else
    {
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('idformtl', idformtl);
        form_data.append('idlhp', idlhp);
        form_data.append('temuan_id', temuan_id);
        form_data.append('rekomendasi_id', rekomendasi_id);
        form_data.append('namafile', namafile);
        form_data.append('_token', csrf_token);

        if (val != '') {
            $('#loading_' + id).attr('style', 'display:block');
            $.ajax({
                url: flagsUrl + '/upload-file-tindaklanjut',
                type: 'POST',
                data: form_data,
                dataType: 'html',
                cache: false,
                contentType: false,
                processData: false,
                enctype: "multipart/form-data",
                success: function (res) {
                    if (res.fail) {
                        $('#fail-' + id).attr('style', 'display:block');
                        $('#ok-' + id).attr('style', 'display:none');
                        notif('error', 'Upload File Tindak Lanjut Error');
                    }
                    else {
                        $('#ok-' + id).attr('style', 'display:block');
                        $('#fail-' + id).attr('style', 'display:none');
                        notif('success', 'Upload File Tindak Lanjut Berhasil');
                    }
                    $('#loading-' + id).attr('style', 'display:none');
                },
                error: function (xhr, status, error) {
                    notif('error', 'Upload File Tindak Lanjut Error');
                }
            });
        }
    }
}

function editormonev(idrekom,idtl)
{
    $('#div-editor').load(flagsUrl+'/div-editor/'+idrekom+'/'+idtl,function(){
        $('#idrekom_catatan_monev').val(idrekom)
        $('#idtl_catatan_monev').val(idtl)
        CKEDITOR.replace('catatan_monev_pic',{
            height:350
        });
    });
    $('#modaleditormonev').modal('show');
}

function simpanmonev()
{
    CKEDITOR.instances.catatan_monev_pic.updateElement();
    $.ajax({
        url: flagsUrl +'/simpan-monev-pic',
        type : 'POST',
        data: $('#monev-pic1').serialize(),
        success : function(res)
        {
            if(res==1)
                notif('success', 'Cacatan Monev untuk PIC 2 Berhasil Disimpan');
            else
                notif('error', 'Catatan Monev Gagal Disimpan');

            $('#modaleditormonev').modal('hide')
        }
    });
}

function detailcatatan(id)
{
    $('#detailcatatan').load(flagsUrl + '/detail-catatan/' + id);
    $('#modaldetailcatatan').modal('show');
}