function detailtindaklanjut(idrekomendasi,st_publish=null)
{
    $('#form-detail-tindaklanjut').load(flagsUrl + '/detail-tindaklanjut-junior/'+idrekomendasi, function(){
        // var catatan_monev = document.getElementById('catatan_monev');
        // var review_spi = document.getElementById('review_spi');
        // alert(catatan_monev)
        // CKEDITOR.replace('catatan_monev');
        CKEDITOR.replace('review_spi');
        $('#table-tl-detail').DataTable();
        $('#status_rekomendasi').select2()
    });

    if (st_publish==1)
        {
            $('#btn-publish').hide();
            $('#btn-draft ').hide();
        }
        else
        {
            $('#btn-publish').show();
            $('#btn-draft ').show();
        }

    $('#modaldetailtindaklanjut').modal('show');
}

function publishtljunior()
{
    var idrekom=$('#idrekomendasi').val();
    $('#tahun_junior').val($('#tahun').val());
    $('#publish').val(1);

    swal({
        title: "Apakah Anda Yakin",
        text: "Ingin Mempublish Data Rekomendasi Ini Ke Senior Auditor ?",
        icon: "warning",
        buttons: [
            'Tidak',
            'Ya, Publish'
        ],
    }).then(function (isConfirm) {
        if (isConfirm) {
            $('#tindaklanjut-junior').submit();
        }
    })
    
}
function drafttljunior()
{
    var idrekom=$('#idrekomendasi').val();
    $('#publish').val(0);
    $('#tahun_junior').val($('#tahun').val());
    $('#tindaklanjut-junior').submit();
}

function addtindaklanjut(jenis, idtemuan, idrekom, id) {
    var idlhp = $('#idlhp').val();
    // alert(idlhp)
    // idrekom = idform;
    if (jenis == 'sewa') {
        $('#formrinciansewa').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-sewa').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrinciansewa').modal('show')
    }
    else if (jenis == 'uangmuka') {
        $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-uangmuka').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianuangmuka').modal('show')
    }
    else if (jenis == 'listrik') {
        $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-listrik').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianlistrik').modal('show')
    }
    else if (jenis == 'piutang') {
        $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutang').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutang').modal('show')
    }
    else if (jenis == 'piutangkaryawan') {
        $('#formrincianpiutangkaryawan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-piutangkaryawan').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpiutangkaryawan').modal('show')
    }
    else if (jenis == 'hutangtitipan') {
        $('#formrincianhutangtitipan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-hutangtitipan').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianhutangtitipan').modal('show')
    }
    else if (jenis == 'penutupanrekening') {
        $('#formrincianpenutupanrekening').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-penutupanrekening').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianpenutupanrekening').modal('show')
    }
    else if (jenis == 'umum') {
        $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
        $('#form-rincian-umum').load(flagsUrl + '/form-rincian2/' + jenis + '/' + idtemuan + '/' + idrekom + '/' + id, function () {
            $('#unit_kerja').select2()
            $('.nominal').on('keyup', function (e) {
                $(this).val(format($(this).val()));
            });
        });
        $('#modalrincianumum').modal('show')
    }
}
function validasiformsewa() {
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
    // else if (no_pks.val() == '')
    //     notif('error', 'Nomor PKS Belum Diisi');
    // else if (tgl_pks.val() == '')
    //     notif('error', 'Tanggal PKS Belum Dipilih');
    else if (nilai_perjanjian.val() == '')
        notif('error', 'Nilai Pekerjaan Belum Diisi');
    // else if (masa_berlaku.val() == '')
    //     notif('error', 'Masa Berlaku Belum Dipilih');
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                        setTimeout(function () {
                            $('#modalrinciansewa').modal('show');
                        }, 1500)
                    } else
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
    // else if (no_invoice.val() == '')
    //     notif('error', 'Nomor Invoice Belum Diisi');
    // else if (tgl_pum.val() == '')
    //     notif('error', 'Tanggal PUM Belum Dipilih');
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
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
        notif('error', 'Jumlah Rekomendasi Belum Diisi');
    else {
        var totalnilai = parseFloat($('#total_nilai').val())
        var nilairekom = $('input.nilai_rekomendasi').val()
        nilairekom = parseFloat(nilairekom.replace(/\./g, ""));
        var nil = parseFloat(jumlah_rekomendasi.val().replace(/\./g, ""));
        if (totalnilai != 0) {
            // alert((totalnilai + nil) +'--'+nilairekom)
            if ((totalnilai + nil) > nilairekom) {
                notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());

            } else {
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
                        } else
                            notif('error', 'Data Rincian Gagal Disimpan');
                    }
                });
            }
        } else if (nil > nilairekom) {
            notif('error', 'Nilai Yang Diinput Sudah Melebihi Batas Maksimal yaitu : ' + $('input.nilai_rekomendasi').val());
        } else {
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
                    } else
                        notif('error', 'Data Rincian Gagal Disimpan');
                }
            });
        }
    }
}
// function validasiformsewa() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var mitra = $('#mitra');
//     var no_pks = $('#no_pks');
//     var tgl_pks = $('#tgl_pks');
//     var nilai_perjanjian = $('#nilai_perjanjian');
//     var masa_berlaku = $('#masa_berlaku');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     //     // alert('Tindak Lanjut Belum Diisi')
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (mitra.val() == '')
//         notif('error', 'Nama Mitra Belum Diisi');
//     // else if (no_pks.val() == '')
//     //     notif('error', 'Nomor PKS Belum Diisi');
//     // else if (tgl_pks.val() == '')
//     //     notif('error', 'Tanggal PKS Belum Dipilih');
//     else if (nilai_perjanjian.val() == '')
//         notif('error', 'Nilai Pekerjaan Belum Diisi');
//     // else if (masa_berlaku.val() == '')
//     //     notif('error', 'Masa Berlaku Belum Dipilih');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrinciansewa').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrinciansewa').trigger("reset");
//                     $('#modalrinciansewa').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrinciansewa').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrinciansewa').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasiformuangmuka() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var no_invoice = $('#no_invoice');
//     var tgl_pum = $('#tgl_pum');
//     var jumlah_um = $('#jumlah_um');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     // else if (no_invoice.val() == '')
//     //     notif('error', 'Nomor Invoice Belum Diisi');
//     // else if (tgl_pum.val() == '')
//     //     notif('error', 'Tanggal PUM Belum Dipilih');
//     else if (jumlah_um.val() == '')
//         notif('error', 'Jumlah UM Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianuangmuka').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianuangmuka').trigger("reset");
//                     $('#modalrincianuangmuka').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianuangmuka').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianuangmuka').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasiformlistrik() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var lokasi = $('#lokasi');
//     var tgl_invoice = $('#tgl_invoice');
//     var tagihan = $('#tagihan');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (lokasi.val() == '')
//         notif('error', 'Lokasi Belum Diisi');
//     else if (tgl_invoice.val() == '')
//         notif('error', 'Tanggal Invoice Belum Dipilih');
//     else if (tagihan.val() == '')
//         notif('error', 'Jumlah Tagihan Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianlistrik').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianlistrik').trigger("reset");
//                     $('#modalrincianlistrik').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianlistrik').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianlistrik').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasiformpiutang() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var pelanggan = $('#pelanggan');
//     var tagihan = $('#tagihan');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (pelanggan.val() == '')
//         notif('error', 'Nama Pelanggan Belum Diisi');
//     else if (tagihan.val() == '')
//         notif('error', 'Jumlah Tagihan Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianpiutang').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianpiutang').trigger("reset");
//                     $('#modalrincianpiutang').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianpiutang').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianpiutang').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasiformpiutangkaryawan() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var karyawan = $('#karyawan');
//     var pinjaman = $('#pinjaman');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (karyawan.val() == '')
//         notif('error', 'Nama Karyawan Belum Diisi');
//     else if (pinjaman.val() == '')
//         notif('error', 'Jumlah Pinjaman Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianpiutangkaryawan').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianpiutangkaryawan').trigger("reset");
//                     $('#modalrincianpiutangkaryawan').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianpiutangkaryawan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianpiutangkaryawan').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasihutangtitipan() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var tanggal = $('#tanggal');
//     var sisa_hutang = $('#sisa_hutang');
//     var sisa_setor = $('#sisa_setor');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (tanggal.val() == '')
//         notif('error', 'Tanggal Belum Dipilih');
//     else if (sisa_hutang.val() == '')
//         notif('error', 'Sisa Hutang Belum Diisi');
//     else if (sisa_setor.val() == '')
//         notif('error', 'Jumlah Sisa Setor Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianhutangtitipan').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianhutangtitipan').trigger("reset");
//                     $('#modalrincianhutangtitipan').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianhutangtitipan').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianhutangtitipan').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasipenutupanrekening() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var nama_bank = $('#nama_bank');
//     var nomor_rekening = $('#nomor_rekening');
//     var jenis_rekening = $('#jenis_rekening');
//     var saldo_akhir = $('#saldo_akhir');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (nama_bank.val() == '')
//         notif('error', 'Nama Bank Belum Diisi');
//     else if (nomor_rekening.val() == '')
//         notif('error', 'Nomor Rekening Belum Diisi');
//     else if (jenis_rekening.val() == '')
//         notif('error', 'Jenis Rekening Belum Diisi');
//     else if (saldo_akhir.val() == '')
//         notif('error', 'Saldo Akhir Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianpenutupanrekening').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianpenutupanrekening').trigger("reset");
//                     $('#modalrincianpenutupanrekening').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianpenutupanrekening').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianpenutupanrekening').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function validasiumum() {
//     var idlhp = $('#idlhp').val();
//     var unit_kerja = $('#unit_kerja');
//     var keterangan = $('#keterangan');
//     var jumlah_rekomendasi = $('#jumlah_rekomendasi');
//     // var tindak_lanjut = $('#tindak_lanjuttxt');

//     // if (tindak_lanjut.val() == '')
//     //     notif('error', 'Tindak Lanjut Belum Diisi');
//     // else 
//     if (unit_kerja.val() == '')
//         notif('error', 'Unit Kerja Belum Dipilih');
//     else if (keterangan.val() == '')
//         notif('error', 'Keterangan Belum Diisi');
//     else if (jumlah_rekomendasi.val() == '')
//         notif('error', 'Jumlah Rekomendasi Belum Diisi');
//     else {
//         //formrinciansewa
//         $.ajax({
//             url: flagsUrl + '/form-rincian-simpan',
//             data: $('#formrincianumum').serialize(),
//             type: 'POST',
//             datatype: 'JSON',
//             success: function (res) {
//                 if (res) {
//                     // $('#formrinciansewa').reset();
//                     $('#formrincianumum').trigger("reset");
//                     $('#modalrincianumum').modal('hide');
//                     // swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
//                     notif('success', 'Data Rincian Berhasil Di Simpan');
//                     gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
//                     setTimeout(function () {
//                         $('#formrincianumum').attr('action', flagsUrl + '/rincian-simpan/' + idlhp);
//                         $('#modalrincianumum').modal('show');
//                     }, 1500)
//                 }
//                 else
//                     notif('error', 'Data Rincian Gagal Disimpan');
//             }
//         });
//     }
// }
// function hapusrincian(id, jenis) {
//     swal({
//         title: "Apakah Anda Yakin",
//         text: "Ingin Menghapus Data Rincian Ini ?",
//         icon: "warning",
//         buttons: [
//             'Tidak',
//             'Ya, Hapus'
//         ],
//         dangerMode: true,
//     }).then(function (isConfirm) {
//         if (isConfirm) {
//             $.ajax({
//                 url: flagsUrl + '/form-rincian-hapus/' + id + '/' + jenis,
//                 dataType: 'JSON',
//                 success: function (res) {
//                     swal("Berhasil", "Data Rincian Berhasil Di Hapus", "success");
//                     gettablerincian(jenis, res.idtemuan, res.idrekomendasi);
//                 }
//             })
//         }
//     })
// }
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
                    gettablerincianold(jenis, res.idtemuan, res.idrekomendasi);
                }
            })
        }
    })
}
function validasiupdaterincian()
{
    var idrekom = $('#idrekom').val()
    swal({
        title: "Apakah Anda Yakin",
        text: "Ingin Menyimpan Update Rincian Ini ?",
        icon: "warning",
        buttons: [
            'Tidak',
            'Ya, Simpan'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/update-rincian/' + idrekom,
                dataType: 'JSON',
                type:'POST',
                data: $('#form-update-rincian').serialize(),
                success: function (res) {
                    if(res.status=='1')
                    {
                        $('#modal-update-rincian').modal('hide');
                        $('#temuan_'+res.idtemuan).load(flagsUrl +'/rekomendasi-data-new/'+res.idtemuan+'/'+res.statusrekom);
                        swal("Berhasil", "Data Rincian Berhasil Di Simpan", "success");
                    }
                    else
                    {
                        swal("Gagal", "Update Data Rincian Gagal Di Simpan", "error");
                    }
                    // gettablerincian(res.jenis, res.idtemuan, res.idrekomendasi);
                    //temuan_4..rekomendasi-data-new
                }
            })
        }
    })
}

function publishkesenior(idrekomendasi)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Mempublish Rekomendasi Ini Ke Auditor Senior ?",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Publish'
        ],
        dangerMode: true,
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: flagsUrl + '/publish-rekomendasi-to-auditor-senior/' + idrekomendasi,
                success: function (res) {
                    if (res == 1) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Publish Rekomendasi Berhasil',
                            icon: 'success'
                        }).then(function () {
                            location.href = flagsUrl + '/data-tindaklanjut/' + $('#tahun').val()
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

function updaterincian_unitkerja(rekom_id, idtemuan, jenis) {
    gettablerincian_unitkerja_lain(jenis, idtemuan, rekom_id)
    $('#modalrincian').modal('show');
    // $('#close-btn').attr('style', 'display:inline');
    // $('#left-div').removeClass('col-md-12');
    // $('#left-div').addClass('col-md-6');
    // $('#right-div').removeClass('col-md-0');
    // $('#right-div').addClass('col-md-6');
    // $('#modal-size').attr({ 'style': 'width:90% !important' });

}
function gettablerincian_unitkerja_lain(jenis, idtemuan, idrekom) {
    $('#table-rincian').load(flagsUrl + '/load-table-rincian-unitkerja/' + jenis + '/' + idtemuan + '/' + idrekom);
}
//---------------
function listtindaklanjutrincian(idrincian, jenis) {
    var idtl = $('#idformtindaklanjut').val();
    $('#list-tindaklanjut-rincian').load(flagsUrl + '/list-tindaklanjut-rincian/' + idrincian + '/' + jenis + '/' + idtl, function () {
        // $('#table').DataTable();
    });
    $('#listtindaklanjutrincian').modal('show');
}
function addtindaklanjutrincian(idrincian, jenis) {
    var idform = $('#idformtindaklanjut').val();
    // alert(idform)
    $('#form-tindaklanjut-rincian').load(flagsUrl + '/form-tindaklanjut-rincian/' + idrincian + '/' + jenis, function () {
        $('.nominal').on('keyup', function (e) {
            $(this).val(format($(this).val()));
        });
        $('#idform').val(idform);
    });
    $('#addtindaklanjutrincian').modal('show');
}
function hapustindaklanjutrincian(idrincian, jenis, idtemuan, rekom_id) {
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