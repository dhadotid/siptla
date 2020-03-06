function detailtindaklanjut(idrekomendasi)
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
    $('#modaldetailtindaklanjut').modal('show');
}
