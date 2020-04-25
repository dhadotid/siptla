<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::group(['middleware'=>['auth','checkstatus']],function(){
    Route::get('/dashboard/{tahun?}', 'DashboardController@index');
    Route::get('/profil', 'UsersController@profil')->name('pengguna.profil');
    Route::post('/simpan-profil/{id}', 'UsersController@simpan_profil')->name('pengguna.simpan-profil');

    Route::resource('pic-unit','PICUnitController');
    Route::resource('level-pic','LevelPicController');
    Route::resource('data-opd','MasterDinasController');
    Route::resource('kepala-opd','MasterKepalaDinasController');
    Route::resource('data-penyebab','MasterSebabController');
    Route::resource('data-rekomendasi','MasterRekomendasiController');
    Route::resource('bidang-pengawasan','MasterBidangPengawasanController');

    Route::get('pengguna/{level?}','UsersController@index');
    Route::resource('pengguna','UsersController');
    Route::resource('pemeriksa','PemeriksaController');
    Route::resource('jenis-audit','JenisAuditController');
    Route::resource('jangka-waktu','JangkaWaktuController');
    Route::resource('status-rekomendasi','StatusRekomendasiController');
    Route::resource('data-temuan','MasterTemuanController');
    Route::resource('jenis-temuan','MasterTemuanController');
    Route::resource('rekanan','RekananController');
    Route::resource('level-resiko','LevelResikoController');
    Route::resource('pejabat-penandatangan','PejabatTandatanganController');
    Route::resource('periode-review','PeriodeReviewController');
    
    Route::get('status-periode/{id}/{st}','PeriodeReviewController@ubah_status');

    Route::get('data-lhp/{tahun?}/{status_rekomendasi?}','DataTemuanController@index')->name('data-lhp.index');
    Route::get('semua-data-lhp/{tahun?}','DataTemuanController@index_semua')->name('data-lhp.index-semua');
    Route::get('data-lhp-data/{tahun?}/{statusrekom?}','DataTemuanController@data_lhp')->name('data-lhp.data');
    Route::get('semua-lhp-data/{tahun?}','DataTemuanController@semua_data_lhp')->name('data-lhp.semua-data');
    Route::get('data-lhp-cek-kode/{pemeriksa?}','DataTemuanController@data_lhp_cek_kode')->name('data-lhp.cek-kode');
    Route::get('data-lhp-detail/{id}/{offset}/{statusrekom?}','DataTemuanController@detail_lhp')->name('data-lhp.detail');
    Route::get('data-lhp-review/{id}','DataTemuanController@review_lhp')->name('data-lhp.review');
    Route::get('form-lhp-review/{id}/{idreview?}','DataTemuanController@form_review_lhp')->name('data-lhp.form-review');
    Route::get('hapus-lhp-review/{id}/{idreview?}','DataTemuanController@hapus_lhp_review')->name('data-lhp.hapus-lhp-review');
    Route::get('publish-lhp/{idlhp}','DataTemuanController@publish_lhp');
    
    Route::post('simpan-lhp-review/{idlhp}','DataTemuanController@simpan_lhp_review')->name('simpan-lhp-review');
    Route::post('data-lhp-store','DataTemuanController@store')->name('data-lhp.store');
    Route::post('data-lhp-update/{idlhp}','DataTemuanController@update')->name('data-lhp.update');

    Route::get('data-temuan-lhp/{idlhp}/{status_rekomendasi?}','DataTemuanController@data_temuan_lhp');
    Route::get('data-temuan-edit/{idlhp}','DataTemuanController@lhp_edit');
    Route::post('data-temuan-delete/{idlhp}','DataTemuanController@lhp_delete');
    Route::get('data-temuan-data/{idlhp}','DataTemuanController@data_temuan_data');
    Route::get('data-temuan-lhp-edit/{id}','DataTemuanController@data_temuan_edit');
    Route::post('data-temuan-lhp-delete/{idlhp}/{id}','DataTemuanController@data_temuan_delete');
    Route::post('data-temuan-lhp-simpan/{idlhp}','DataTemuanController@data_temuan_lhp_simpan')->name('data-temuan-lhp.simpan');
    Route::post('data-temuan-lhp-update/{temuan_id}','DataTemuanController@data_temuan_lhp_update')->name('data-temuan-lhp.update');
    Route::get('temuan-by-lhp/{idlhp}','DataTemuanController@temuan_by_lhp')->name('temuan.by-lhp');
    Route::get('temuan-by-lhp-select/{idlhp}/{user_pid_id?}','DataTemuanController@temuan_by_lhp_select');

    
    Route::get('formupdaterincian/{idtemuan}/{idrekom}','DataRekomendasiController@formupdaterincian');
    Route::get('rekomendasi-edit-data/{idrekom}','DataRekomendasiController@rekomendasi_edit_data')->name('rekomendasi.edit-data');
    Route::get('rekomendasi-edit/{idrekom}','DataRekomendasiController@rekomendasi_edit')->name('rekomendasi.edit');
    Route::get('rekomendasi-data/{idtemuan}','DataRekomendasiController@rekomendasi_data')->name('rekomendasi.data');
    Route::get('rekomendasi-data-new/{idtemuan}/{status_rekom}','DataRekomendasiController@rekomendasi_data_new')->name('rekomendasi.data-new');
    Route::post('rekomendasi-simpan','DataRekomendasiController@rekomendasi_simpan')->name('rekomendasi.simpan');
    Route::post('rekomendasi-update/{idrekom}/{idtemuan}','DataRekomendasiController@rekomendasi_update')->name('rekomendasi.update');
    Route::get('rekomendasi-delete/{idrekom}/{idtemuan}','DataRekomendasiController@rekomendasi_delete')->name('rekomendasi.delete');
    Route::get('update-jlh-rekomendasi/{idtemuan}/{st_rekom?}','DataRekomendasiController@update_jlh_rekomendasi');
    Route::get('rekomendasi-by-temuan/{idtemuan}/{status?}','DataRekomendasiController@rekomendasi_by_temuan')->name('rekomendasi.by-temuan');
    Route::get('rincian-nilai-rekom/{idrekom}','DataRekomendasiController@rincian_nilai');
    Route::get('rekomendasi-by-temuan-select/{idtemuan}/{user_pic_id?}','DataRekomendasiController@rekomendasi_by_temuan_select');
    Route::get('publish-rekomendasi-to-auditor-junior/{idrekomendasi}','DataRekomendasiController@publish_rekomendasi_to_auditor_junior');
    Route::get('publish-rekomendasi-to-auditor-senior/{idrekomendasi}','DataRekomendasiController@publish_rekomendasi_to_auditor_senior');
    Route::get('publish-rekomendasi-to-pic1/{idrekomendasi}','DataRekomendasiController@publish_rekomendasi_to_pic1');
    Route::get('publish-rekomendasi/{idrekomendasi}','DataRekomendasiController@publish_rekomendasi');
    Route::get('list-rangkuman/{idrekomendasi}','DataRekomendasiController@list_rangkuman');
    Route::post('rangkuman-simpan','DataRekomendasiController@rangkuman_simpan');
    Route::get('setujui-rekomendasi/{idrekom}','DataRekomendasiController@setujui_rekomendasi');
    
    
    Route::post('update-rincian','RincianSewaController@update_rincian');
    Route::get('load-table-rincian/{jenis}/{idtemuan?}/{statusrekomendasi?}/{view?}','DataRekomendasiController@load_tabel_rincian');
    Route::get('load-table-rincian-unitkerja/{jenis}/{idtemuan?}/{statusrekomendasi?}/{view?}','DataRekomendasiController@load_tabel_rincian_unitkerja');
    Route::get('form-rincian/{jenis}/{idtemuan?}/{idrekomendasi?}/{id?}/{pic1?}/{pic2?}','RincianSewaController@form_rincian');
    Route::get('form-rincian2/{jenis}/{idtemuan?}/{idrekomendasi?}/{id?}/{pic1?}/{pic2?}','RincianSewaController@form_rincian2');
    Route::post('form-rincian-simpan','RincianSewaController@form_rincian_simpan');
    Route::get('form-rincian-hapus/{id}/{jenis}','RincianSewaController@form_rincian_hapus');
    Route::post('form-rincian-hapus/{id}/{jenis}','RincianSewaController@form_rincian_hapus');

    Route::post('tindak-lanjut-simpan/{idrekom}','TindakLanjutController@simpan');
    Route::get('tindak-lanjut-edit/{id}','TindakLanjutController@edit');
    Route::get('tindak-lanjut-hapus/{id}','TindakLanjutController@destroy');
    Route::get('data-tindak-lanjut/{rekom_id}/{idtemuan}','TindakLanjutController@index');
    Route::get('data-tindak-lanjut-unitkerja/{rekom_id}/{idtemuan}','TindakLanjutController@index_unitkerja');

    Route::get('jumlah-rincian/{temuan_id}/{rekom_id}','TindakLanjutController@jumlah_rincian');
    /* AUDITOR SENIOR */

    Route::get('data-tindaklanjut-senior/{tahun?}/{rekom_id?}/{temuan_id?}','AuditorSeniorController@tindaklanjut_index');
    Route::post('data-tindaklanjut-seniorlist','AuditorSeniorController@tindaklanjut_list');

    Route::post('tindaklanjut-senior-simpan','AuditorSeniorController@tindaklanjut_senior_simpan');

    // END AUDITOR SENIOR
    /* SUPER USER */

    Route::get('data-tindaklanjut-su/{tahun?}/{rekom_id?}/{temuan_id?}','AuditorSeniorController@tindaklanjut_su_index');
    Route::post('data-tindaklanjut-sulist','AuditorSeniorController@tindaklanjut_su_list');

    Route::post('tindaklanjut-su-simpan','AuditorSeniorController@tindaklanjut_su_simpan');

    // END SUPER USER

    Route::get('data-tindaklanjut/{tahun?}/{rekom_id?}/{temuan_id?}','TindakLanjutController@junior_index');
    Route::get('data-tindaklanjut-unitkerja/{tahun?}/{rekom_id?}/{temuan_id?}','TindakLanjutController@unitkerja_index');
    Route::post('data-tindaklanjut-list','TindakLanjutController@junior_list');
    Route::post('data-tindaklanjut-unitkerja-list','TindakLanjutController@unitkerja_list');
    Route::post('tindaklanjut-unitkerja-simpan','TindakLanjutController@unitkerja_tindak_lanjut_simpan');
    Route::post('tindaklanjut-unitkerja-edit-simpan','TindakLanjutController@unitkerja_tindak_lanjut_edit_simpan');
    Route::get('tindak-lanjut-unitkerja-form-add/{idlhp}/{temuan_id_index}/{rekom_id_index}/{rekom_id?}','TindakLanjutController@unitkerja_add_form');
    Route::get('tindak-lanjut-unitkerja-form-edit/{idlhp}/{temuan_id}/{rekom_id}/{idtl}','TindakLanjutController@unitkerja_edit_form');
    Route::get('hapus-tindak-lanjut/{idtl}','TindakLanjutController@hapus_tindak_lanjut');
    Route::post('upload-file-tindaklanjut','TindakLanjutController@ajaxFiles');
    
    Route::get('detail-tindaklanjut-junior/{idrekom}','TindakLanjutController@detail_tindaklanjut_junior');
    Route::get('detail-tindaklanjut-pic1/{idrekom}','TindakLanjutController@detail_tindaklanjut_picunit1');
    Route::post('review-pic1-simpan','TindakLanjutController@review_pic1_simpan');
    
    Route::get('form-tindaklanjut-rincian/{idrincian}/{jenis}','TindakLanjutController@form_tindaklanjut_rincian');
    Route::get('list-tindaklanjut-rincian/{idrincian}/{jenis}/{idtl?}','TindakLanjutController@list_tindaklanjut_rincian');
    Route::get('list-rincian-rekomendasi/{idrekomendasi}/{jenis}','TindakLanjutController@list_rincian_rekomendasi');
    Route::get('hapus-rincian/{idrincian}/{jenis}','TindakLanjutController@hapus_rincian_jenis');
    Route::post('simpan-tindaklanjut-rincian','TindakLanjutController@simpan_tindaklanjut_rincian');
    
    Route::get('list-rincian/{idrekomendasi}/{idunitkerja}/{idtl}','TindakLanjutController@list_rincian');
    

    Route::get('table-data-tindaklanjut/{idrekomendasi}','TindakLanjutController@table_data_tindaklanjut');
    Route::get('div-editor/{idrekom}/{idtl}','TindakLanjutController@div_editor');
    Route::get('data-rekanan','RekananController@data_rekanan')->name('data-rekanan');
    
    Route::get('set-tgl-penyelesaian/{temuan_id}/{rekom_id}/{tgl}/{bln}/{thn}','TindakLanjutController@set_tgl_penyelesaian');
    Route::get('detail-tl-rincian/{rekom_id}','TindakLanjutController@detail_tl_rincian');

    Route::post('tindaklanjut-junior-simpan','TindakLanjutController@tindaklanjut_junior_simpan');
    Route::post('simpan-monev-pic','TindakLanjutController@simpan_monev_pic');
    Route::get('detail-catatan/{id}','TindakLanjutController@detail_catatan');

    Route::get('selectlhpbypemeriksa/{idpemeriksa}/{multiple?}','DaftarTemuanController@selectlhpbypemeriksa');
});

Route::group(['prefix'=>'laporan','middleware'=>'auth'],function(){
    Route::get('temuan-per-bidang','LaporanController@temuan_per_bidang');
    Route::post('temuan-per-bidang-data','LaporanController@temuan_per_bidang_data');
    Route::post('temuan-per-bidang-pdf','LaporanController@temuan_per_bidang_pdf');

    Route::get('temuan-per-unitkerja','LaporanController@temuan_per_unitkerja');
    Route::post('temuan-per-unitkerja-data','LaporanController@temuan_per_unitkerja_data');
    Route::post('temuan-per-unitkerja-pdf','LaporanController@temuan_per_unitkerja_pdf');

    Route::get('temuan-per-lhp','LaporanController@temuan_per_lhp');
    Route::post('temuan-per-lhp-data','LaporanController@temuan_per_lhp_data');
    Route::post('temuan-per-lhp-pdf','LaporanController@temuan_per_lhp_pdf');

    Route::get('tindaklanjut-per-lhp','LaporanController@tindaklanjut_per_lhp');
    Route::post('tindaklanjut-per-lhp-data','LaporanController@tindaklanjut_per_lhp_data');
    Route::post('tindaklanjut-per-lhp-pdf','LaporanController@tindaklanjut_per_lhp_pdf');

    Route::get('tindaklanjut-per-bidang','LaporanController@tindaklanjut_per_bidang');
    Route::post('tindaklanjut-per-bidang-data','LaporanController@tindaklanjut_per_bidang_data');
    Route::post('tindaklanjut-per-bidang-pdf','LaporanController@tindaklanjut_per_bidang_pdf');

    Route::get('tindaklanjut-per-unitkerja','LaporanController@tindaklanjut_per_unitkerja');
    Route::post('tindaklanjut-per-unitkerja-data','LaporanController@tindaklanjut_per_unitkerja_data');
    Route::post('tindaklanjut-per-unitkerja-pdf','LaporanController@tindaklanjut_per_unitkerja_pdf');

    Route::get('tindak-lanjut','LaporanController@tindak_lanjut');
    Route::post('tindak-lanjut-data','LaporanController@tindak_lanjut_data');
    Route::post('tindak-lanjut-pdf','LaporanController@tindak_lanjut_pdf');
    
    Route::get('status-penyelesaian-rekomendasi','LaporanController@status_penyelesaian_rekomendasi');
    Route::post('status-penyelesaian-rekomendasi-data','LaporanController@status_penyelesaian_rekomendasi_data');
    Route::post('status-penyelesaian-rekomendasi-pdf','LaporanController@status_penyelesaian_rekomendasi_pdf');
    
    Route::get('status-penyelesaian-rekomendasi-pemeriksa','LaporanController@status_penyelesaian_rekomendasi_pemeriksa');
    Route::post('status-penyelesaian-rekomendasi-pemeriksa-data','LaporanController@status_penyelesaian_rekomendasi_pemeriksa_data');
    Route::post('status-penyelesaian-rekomendasi-pemeriksa-pdf','LaporanController@status_penyelesaian_rekomendasi_pemeriksa_pdf');
    
    Route::get('status-penyelesaian-rekomendasi-bidang','LaporanController@status_penyelesaian_rekomendasi_bidang');
    Route::post('status-penyelesaian-rekomendasi-bidang-data','LaporanController@status_penyelesaian_rekomendasi_bidang_data');
    Route::post('status-penyelesaian-rekomendasi-bidang-pdf','LaporanController@status_penyelesaian_rekomendasi_bidang_pdf');
   
    Route::get('status-penyelesaian-rekomendasi-tahun','LaporanController@status_penyelesaian_rekomendasi_tahun');
    Route::post('status-penyelesaian-rekomendasi-tahun-data','LaporanController@status_penyelesaian_rekomendasi_tahun_data');
    Route::post('status-penyelesaian-rekomendasi-tahun-pdf','LaporanController@status_penyelesaian_rekomendasi_tahun_pdf');
   
    Route::get('status-penyelesaian-rekomendasi-unitkerja','LaporanController@status_penyelesaian_rekomendasi_unitkerja');
    Route::post('status-penyelesaian-rekomendasi-unitkerja-data','LaporanController@status_penyelesaian_rekomendasi_unitkerja_data');
    Route::post('status-penyelesaian-rekomendasi-unitkerja-pdf','LaporanController@status_penyelesaian_rekomendasi_unitkerja_pdf');

    Route::get('rekomendasi-overdue-unitkerja','LaporanController@rekomendasi_overdue_unitkerja');
    Route::post('rekomendasi-overdue-unitkerja-data','LaporanController@rekomendasi_overdue_unitkerja_data');
    Route::post('rekomendasi-overdue-unitkerja-pdf','LaporanController@rekomendasi_overdue_unitkerja_pdf');
    
    Route::get('laporan-rekomendasi-overdue','LaporanController@laporan_rekomendasi_overdue');
    Route::post('laporan-rekomendasi-overdue-data','LaporanController@laporan_rekomendasi_overdue_data');
    Route::post('laporan-rekomendasi-overdue-pdf','LaporanController@laporan_rekomendasi_overdue_pdf');

   
});

// DATA LHP

// END DATA LHP

Auth::routes();
Route::get('logout',function(){
    Auth::logout();
    return redirect('login');
});
Route::get('force-logout',function(){
    Auth::logout();
    return redirect('login')->with('error',Session::get('error'));
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('open-file/{dir1}/{dir2}/{filename}', 'Controller@open_file')->name('home');
Route::get('read-pdf/{dir1}/{dir2}/{filename}', 'Controller@read_pdf')->name('home');
Route::get('read-file/{dir1}/{dir2}/{filename}', 'Controller@read_pdf')->name('home');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::post('/send_email', 'Controller@sendEmail');
Route::get('/reminder_7', 'Controller@reminder_7');
Route::get('/reminder_3', 'Controller@reminder_3');
Route::get('/reminder_overdue', 'Controller@reminder_overdue');
Route::get('/reminder_junior', 'Controller@reminder_junior');
Route::get('/reminder_senior', 'Controller@reminder_senior');
