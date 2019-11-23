function loaddata(tahun) {
    $('#data').load(flagsUrl + '/data-lhp-data/' + tahun, function () {
        $('#table').dataTable();
        $('#table').on('click', '.btn-edit', function () {
            var id = $(this).data('value')
            // alert(id);
            $('#formubah').attr('action', flagsUrl + "/data-lhp-update/" + id)
            $.ajax({
                url: flagsUrl + "/data-temuan-edit/" + id,
                success: function (res) {
                    
                    
                    var pemeriksa = res.pemeriksa_id + '-' + res.dpemeriksa.code + '-' + res.dpemeriksa.pemeriksa
                    var dt = res.tanggal_lhp.split('-');


                    $('#edit_nomor_lhp').val(res.no_lhp);
                    $('#edit_pemeriksa').val(pemeriksa);
                        $('#edit_pemeriksa').select2().trigger('change');
                    $('#edit_kode_lhp').val(res.kode_lhp);
                    $('#edit_judul_lhp').val(res.judul_lhp);
                    $('#edit_tanggal_lhp').val(dt[2]+'/'+dt[1]+'/'+dt[0]);
                    $('#edit_tahun_pemeriksaan').val(res.tahun_pemeriksa);
                        $('#edit_tahun_pemeriksaan').select2().trigger('change');
                    $('#edit_jenis_audit').val(res.jenis_audit_id);
                        $('#edit_jenis_audit').select2().trigger('change');
                    $('#edit_status_lhp').val(res.status_lhp);
                    $('#edit_flag_status_lhp').val(res.create_flag);
                }
            })
        })

        // delete action
        $('#table').on('click', '.btn-delete', function () {
            var id = $(this).data('value')
            // alert(id)
            $('#form-delete').attr('action', flagsUrl + "/data-temuan-delete/" + id)
        })
    });
}
function generatekodelhp(val) {
    $.ajax({
        url: flagsUrl + '/data-lhp-cek-kode/' + val,
        success: function (res) {
            $('#kode_lhp').val(res);
        }
    })
}
function getdata(tahun) {
    loaddata(tahun);
}
function validasiadd() {
    var nomor_lhp = $('#nomor_lhp');
    var pemeriksa = $('#pemeriksa');
    var judul_lhp = $('#judul_lhp');
    var jenis_audit = $('#jenis_audit');

    if (nomor_lhp.val() == '') {
        notif('error', 'Nomor LHP Belum Diisi');
        nomor_lhp.focus();
    }
    else if (pemeriksa.val() == '') {
        notif('error', 'Pemeriksa Belum Dipilih');
        pemeriksa.focus();
    }
    else if (judul_lhp.val() == '') {
        notif('error', 'Judul LHP Belum Diisi');
        judul_lhp.focus();
    }
    else if (jenis_audit.val() == '') {
        notif('error', 'Jenis Audit Belum Dipilih');
        jenis_audit.focus();
    }
    else {
        $('#formadd').submit()
    }
}
function validasiedit() {
    var nomor_lhp = $('#edit_nomor_lhp');
    var pemeriksa = $('#edit_pemeriksa');
    var judul_lhp = $('#edit_judul_lhp');
    var jenis_audit = $('#edit_jenis_audit');

    if (nomor_lhp.val() == '') {
        notif('error', 'Nomor LHP Belum Diisi');
        nomor_lhp.focus();
    }
    else if (pemeriksa.val() == '') {
        notif('error', 'Pemeriksa Belum Dipilih');
        pemeriksa.focus();
    }
    else if (judul_lhp.val() == '') {
        notif('error', 'Judul LHP Belum Diisi');
        judul_lhp.focus();
    }
    else if (jenis_audit.val() == '') {
        notif('error', 'Jenis Audit Belum Dipilih');
        jenis_audit.focus();
    }
    else {
        $('#formubah').submit()
    }
}