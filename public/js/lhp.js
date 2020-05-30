function loaddata(tahun,statusrekom=null, key = '', priority = '') {
    var urlTable = flagsUrl + '/data-lhp-data/' + tahun + '/' + statusrekom;
    if(key != '' && priority != ''){
        urlTable = flagsUrl + '/data-lhp-data/' + tahun + '/' + statusrekom + key +'&'+priority;
    }
    $('#data').load(urlTable, function () {
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
                    $('.edit_status_lhp').val(res.status_lhp);
                        // $('.edit_status_lhp').select2().trigger('change');
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
        
        $('#table').on('click', '.btn-review', function () {
            var id = $(this).data('value')
            // alert(id)
            reviewlhp(id);
        })

        $('#table').on('click', '.btn-add-review', function () {
            var id = $(this).data('value')
            // alert(id)
            $('#id-review-lhp').val(id);
            formreviewlhp(id);
        })
        
    });
}
function loaddatasemua(tahun, statusrekom=null) {
    $('#data').load(flagsUrl + '/semua-lhp-data/' + tahun + '/' + statusrekom, function () {
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
                    $('.edit_status_lhp').val(res.status_lhp);
                    $('.edit_status_lhp_select').val(res.status_lhp);
                        $('.edit_status_lhp_select').select2().trigger('change');
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
        
        $('#table').on('click', '.btn-review', function () {
            var id = $(this).data('value')
            // alert(id)
            reviewlhp(id);
        })
        $('#table').on('click', '.btn-add-review', function () {
            var id = $(this).data('value')
            // alert(id)
            formreviewlhp(id);
        })
        
    });
}
function generatekodelhp(val) {
    $.ajax({
        url: flagsUrl + '/data-lhp-cek-kode/' + val,
        success: function (res) {
            $('#kode_lhp').val(res);
            $('#nomor_lhp').val(res);
        }
    })
}

function getdata(tahun) {
    // loaddata(tahun);
    // loaddatasemua(tahun);
    location.href = flagsUrl+'/data-lhp/'+tahun;
}

function detaillhp(id,offset,statusrekom=null)
{
    $('#detail').load(flagsUrl+'/data-lhp-detail/'+id+'/'+offset+'/'+statusrekom,function(){
        $('#detail-rekomendasi').DataTable();
    });
    $('#modaldetail').modal('show');
}

function reviewlhp(id)
{
    $('#review').load(flagsUrl + '/data-lhp-review/'+id,function(){
        $('#review-lhp').DataTable({
            columnDefs: [
                { width: 40, targets: 0 }
            ],
            fixedColumns: true
        });
    });
}

function editformreviewlhp(id,idreview)
{
    $('#modalreview').modal('hide');
    formreviewlhp(id, idreview);
    $('#modaladdreview').modal('show');
}
function hapusrekomendasi(id,idreview)
{
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Menghapus Data Review ini",
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
                url : flagsUrl + '/hapus-lhp-review/'+idreview,
                success : function(){
                    swal({
                        title: 'Berhasil!',
                        text: 'Hapus Data Review Berhasil',
                        icon: 'success'
                    }).then(function () {
                        reviewlhp(id)
                        $('#modalreview').modal('show');
                    });
                }
            });
        } else {
            
        }
    });
        
    // $('#modalreview').modal('hide');
    // formreviewlhp(id, idreview);
    // $('#modaladdreview').modal('show');
}

function formreviewlhp(id, idreview=0)
{
    // alert(idreview)
    $('#form-review').load(flagsUrl + '/form-lhp-review/'+id+'/'+idreview,function(){
        $('#summernote-review').summernote({
            height:300
        });
    });
}

function validasireview()
{
    var id = $('#id-review-lhp').val();
    var tahun = $('#tahun-review-lhp').val();
    var id_review = $('#idreview').val();
    var review_status_lhp = $('#review_status_lhp').val();
    var messageData = $('#summernote-review');
    if (messageData.summernote('isEmpty'))
    {
        notif('error', 'Data Review Belum Di Isi')
    }
    else
    {
        var konten = messageData.summernote('code');
        // alert(konten)
        $.ajax({
            url : flagsUrl + '/simpan-lhp-review/'+id,
            type : 'POST',
            data: { review: konten, idreview: id_review, status_lhp: review_status_lhp},
            success : function(res){

                if(res==1)
                {
                    $('#modaladdreview').modal('hide');
                    reviewlhp(id);
                    $('#modalreview').modal('show');
                    notif('success','Data Review Berhasil Di Simpan');
                    getdata(tahun) 
                    // swal("Berhasil", "Data Review Berhasil Di Simpan", "success");
                }
                else
                    notif('error','Data Review Tidak Berhasil Di Simpan');
                    // swal("Gagal", "Data Review Tidak Berhasil Di Simpan", "error");
            }
        });
        
    }
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

function detailrekom(id)
{
    $('#detail-rekom').load(flagsUrl +'/rincian-nilai-rekom/'+id,function(){
        $('#table').dataTable();
    });
    $('#modal-detail-rekom').modal('show');
}

function publishlhp(idlhp)
{
    var tahun = $('#tahun').val();
    swal({
        title: "Apakah Anda Yakin ?",
        text: "Ingin Mempublish Data LHP ini",
        icon: "warning",
        buttons: [
            'Tidak!',
            'Ya, Publish'
        ],
        dangerMode: true,
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
                        location.href = flagsUrl +'/data-lhp/'+tahun;
                    });
                }
            });
        }
    }); 
}

function detailtljunior(idrekom)
{
    $('#detailtljunior').modal('show')
}