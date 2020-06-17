function getdata(tahun) {
    location.href = flagsUrl+'/data-repository/'+tahun;
}

function loaddata(tahun) {
    $('#data').load(flagsUrl + '/data-repository-list/' + tahun, function () {
        $('#table').DataTable();
    });
}

function tindak_lanjut_rincian(rekom_id,idtemuan){
    $('#table-rincian').load(flagsUrl + '/data-repository-tindaklanjut-rincian/' + rekom_id + '/' + idtemuan, function(){
        $('#table-tindaklanjut-rincian'+rekom_id+idtemuan).DataTable({
            responsive: true,
            "bAutoWidth": false
        });
    });

    $('#modalrinciantindaklanjut').modal('show');
}

function tindak_lanjut(rekom_id,idtemuan){
    $('#table-rincian-dokumen').load(flagsUrl + '/data-repository-tindaklanjut-dokumen/' + rekom_id + '/' + idtemuan, function(){
        $('#table-tindaklanjut'+rekom_id+idtemuan).DataTable({
            responsive: true,
            "bAutoWidth": false
        });
    });

    $('#modalrinciantindaklanjutdokumen').modal('show');
}