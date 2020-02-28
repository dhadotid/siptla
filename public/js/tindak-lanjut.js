function loaddata() {
    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    var pemeriksa = $('#pemeriksa').val();
    var no_lhp = $('#no_lhp').val();
    var no_temuan = $('#no_temuan').val();
    var no_rekomendasi = $('#no_rekomendasi').val();
    var status_rekomendasi = $('#status_rekomendasi').val();

    $.ajax({
        url: flagsUrl+'/data-tindaklanjut-unitkerja-list',
        data: { tahun: '{{$tahun}}', tgl_awal: tanggal_awal, tgl_akhir: tanggal_akhir, rekomid: no_rekomendasi, temuan_id: no_temuan, statusrekom: status_rekomendasi },
        type: 'POST',
        dataType: 'JSON',
        success: function (res) {
            $('#data').html(res, function () {
                $('#table-data').DataTable();
            });
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
                        $('#tgl_penyelesaian_' + temuan_id + '_' + rekom_id).text(res);
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
    var lhp_id=d[0];
    closerightdiv();
    $('#konten-add-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-add/' + lhp_id+'/'+temuan_id+'/'+rekom_id);
});

function othertemuan(id)
{
    closerightdiv()
    var d = id.split('__')
    var temuan_id = d[1];
    var rekom_id = d[2];
    var lhp_id = d[0];
    $('#konten-add-form').load(flagsUrl + '/tindak-lanjut-unitkerja-form-add/' + lhp_id + '/' + temuan_id + '/' + rekom_id);
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

function gettablerincian(jenis, idtemuan, idrekom) {
    $('#right-div').load(flagsUrl + '/load-table-rincian/' + jenis + '/' + idtemuan + '/' + idrekom);
}

function rekomaddnew(idtemuan) {
    $('#id_temuan_rekom').val(idtemuan);
}

function addtindaklanjut(jenis, idtemuan, idrekom, id) {
    var idlhp = $('#idlhp').val();
    // alert(idlhp)
    // idrekom = idform;
    if (jenis == 'sewa') {
        $('#formrinciansewa').attr('action', flagsUrl + '/rincian-simpan/'+idlhp);
        $('#form-rincian-sewa').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciansewa').modal('show')
    }
    else if (jenis == 'uangmuka') {
        $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-uangmuka').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianuangmuka').modal('show')
    }
    else if (jenis == 'listrik') {
        $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-listrik').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianlistrik').modal('show')
    }
    else if (jenis == 'piutang') {
        $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutang').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutang').modal('show')
    }
    else if (jenis == 'piutangkaryawan') {
        $('#formrincianpiutangkaryawan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutangkaryawan').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutangkaryawan').modal('show')
    }
    else if (jenis == 'hutangtitipan') {
        $('#formrincianhutangtitipan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-hutangtitipan').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianhutangtitipan').modal('show')
    }
    else if (jenis == 'penutupanrekening') {
        $('#formrincianpenutupanrekening').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-penutupanrekening').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpenutupanrekening').modal('show')
    }
    else if (jenis == 'umum') {
        $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-umum').load(flagsUrl + '/form-rincian/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianumum').modal('show')
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    //     // alert('Tindak Lanjut Belum Diisi')
    // else 
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
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
                        $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
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
                        $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
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
                        $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (karyawan.val() == '')
        notif('error', 'Nama Karyawan Belum Diisi');
    else if (pinjaman.val() == '')
        notif('error', 'Jumlah Pinjaman Belum Diisi');
    else {
        //formrinciansewa
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
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
    // var tindak_lanjut = $('#tindak_lanjuttxt');

    // if (tindak_lanjut.val() == '')
    //     notif('error', 'Tindak Lanjut Belum Diisi');
    // else 
    if (unit_kerja.val() == '')
        notif('error', 'Unit Kerja Belum Dipilih');
    else if (keterangan.val() == '')
        notif('error', 'Keterangan Belum Diisi');
    else if (jumlah_rekomendasi.val() == '')
        notif('error', 'Jumlah Rekomendasi Belum Diisi');
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
                    gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    setTimeout(function () {
                        $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
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