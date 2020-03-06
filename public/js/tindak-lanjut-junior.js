function detailtindaklanjut(idrekomendasi)
{
    $('#form-detail-tindaklanjut').load(flagsUrl + '/detail-tindaklanjut-junior/'+idrekomendasi,function(){
        $('#table-tl-detail').DataTable();
        CKEDITOR.replace('catatan_monev');
        CKEDITOR.replace('review_spi');
        $('#status_rekomendasi').select2()
    });
    $('#modaldetailtindaklanjut').modal('show');
}