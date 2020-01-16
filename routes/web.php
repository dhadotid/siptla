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

Route::group(['middleware'=>'auth'],function(){
    Route::get('/dashboard/{tahun?}', 'DashboardController@index');

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

    Route::get('data-lhp/{tahun?}','DataTemuanController@index')->name('data-lhp.index');
    Route::get('semua-data-lhp/{tahun?}','DataTemuanController@index_semua')->name('data-lhp.index-semua');
    Route::get('data-lhp-data/{tahun?}','DataTemuanController@data_lhp')->name('data-lhp.data');
    Route::get('semua-lhp-data/{tahun?}','DataTemuanController@semua_data_lhp')->name('data-lhp.semua-data');
    Route::get('data-lhp-cek-kode/{pemeriksa?}','DataTemuanController@data_lhp_cek_kode')->name('data-lhp.cek-kode');
    Route::get('data-lhp-detail/{id}/{offset}','DataTemuanController@detail_lhp')->name('data-lhp.detail');
    Route::get('data-lhp-review/{id}','DataTemuanController@review_lhp')->name('data-lhp.review');
    Route::get('form-lhp-review/{id}/{idreview?}','DataTemuanController@form_review_lhp')->name('data-lhp.form-review');
    Route::get('hapus-lhp-review/{id}/{idreview?}','DataTemuanController@hapus_lhp_review')->name('data-lhp.hapus-lhp-review');
    
    Route::post('simpan-lhp-review/{idlhp}','DataTemuanController@simpan_lhp_review')->name('simpan-lhp-review');
    Route::post('data-lhp-store','DataTemuanController@store')->name('data-lhp.store');
    Route::post('data-lhp-update/{idlhp}','DataTemuanController@update')->name('data-lhp.update');

    Route::get('data-temuan-lhp/{idlhp}','DataTemuanController@data_temuan_lhp');
    Route::get('data-temuan-edit/{idlhp}','DataTemuanController@lhp_edit');
    Route::post('data-temuan-delete/{idlhp}','DataTemuanController@lhp_delete');
    Route::get('data-temuan-data/{idlhp}','DataTemuanController@data_temuan_data');
    Route::get('data-temuan-lhp-edit/{id}','DataTemuanController@data_temuan_edit');
    Route::post('data-temuan-lhp-delete/{idlhp}/{id}','DataTemuanController@data_temuan_delete');
    Route::post('data-temuan-lhp-simpan/{idlhp}','DataTemuanController@data_temuan_lhp_simpan')->name('data-temuan-lhp.simpan');
    Route::post('data-temuan-lhp-update/{temuan_id}','DataTemuanController@data_temuan_lhp_update')->name('data-temuan-lhp.update');

    Route::get('rekomendasi-edit/{idrekom}','DataRekomendasiController@rekomendasi_edit')->name('rekomendasi.edit');
    Route::get('rekomendasi-data/{idtemuan}','DataRekomendasiController@rekomendasi_data')->name('rekomendasi.data');
    Route::post('rekomendasi-simpan','DataRekomendasiController@rekomendasi_simpan')->name('rekomendasi.simpan');
    Route::post('rekomendasi-update/{idrekom}/{idtemuan}','DataRekomendasiController@rekomendasi_update')->name('rekomendasi.update');
    Route::get('rekomendasi-delete/{idrekom}/{idtemuan}','DataRekomendasiController@rekomendasi_delete')->name('rekomendasi.delete');
    Route::get('update-jlh-rekomendasi/{idtemuan}','DataRekomendasiController@update_jlh_rekomendasi');

    Route::post('tindak-lanjut-simpan/{idrekom}','TindakLanjutController@simpan');
    Route::get('tindak-lanjut-edit/{id}','TindakLanjutController@edit');
    Route::get('tindak-lanjut-hapus/{id}','TindakLanjutController@destroy');

    Route::get('data-rekanan','RekananController@data_rekanan')->name('data-rekanan');
});
Route::group(['prefix'=>'laporan','middleware'=>'auth'],function(){
    Route::get('temuan-per-bidang','LaporanController@temuan_per_bidang');
    Route::get('temuan-per-bidang-data','LaporanController@temuan_per_bidang_data');

    Route::get('temuan-per-unitkerja','LaporanController@temuan_per_unitkerja');
    Route::get('temuan-per-unitkerja-data','LaporanController@temuan_per_unitkerja_data');

    Route::get('temuan-per-lhp','LaporanController@temuan_per_lhp');
    Route::get('temuan-per-lhp-data','LaporanController@temuan_per_lhp_data');

    Route::get('tindaklanjut-per-lhp','LaporanController@tindaklanjut_per_lhp');
    Route::get('tindaklanjut-per-lhp-data','LaporanController@tindaklanjut_per_lhp_data');

    Route::get('tindaklanjut-per-bidang','LaporanController@tindaklanjut_per_bidang');
    Route::get('tindaklanjut-per-bidang-data','LaporanController@tindaklanjut_per_bidang_data');

    Route::get('tindaklanjut-per-unitkerja','LaporanController@tindaklanjut_per_unitkerja');
    Route::get('tindaklanjut-per-unitkerja-data','LaporanController@tindaklanjut_per_unitkerja_data');

    Route::get('tindak-lanjut','LaporanController@tindak_lanjut');
    Route::get('tindak-lanjut-data','LaporanController@tindak_lanjut_data');

    Route::get('rekap-lhp','LaporanController@rekap_lhp');
    Route::get('rekap-lhp-data','LaporanController@rekap_lhp_data');

    Route::get('rekap-status-rekomendasi','LaporanController@rekap_status_rekomendasi');
    Route::get('rekap-status-rekomendasi-data','LaporanController@rekap_status_rekomendasi_data');
    
    Route::get('rekap-status-rekomendasi-bidang','LaporanController@rekap_status_rekomendasi_bidang');
    Route::get('rekap-status-rekomendasi-bidang-data','LaporanController@rekap_status_rekomendasi_bidang_data');

    Route::get('rekap-status-rekomendasi-unitkerja','LaporanController@rekap_status_rekomendasi_unitkerja');
    Route::get('rekap-status-rekomendasi-unitkerja-data','LaporanController@rekap_status_rekomendasi_unitkerja_data');

    Route::get('rekap-jumlah-resiko-periode','LaporanController@rekap_jumlah_resiko_periode');
    Route::get('rekap-jumlah-resiko-periode-data','LaporanController@rekap_jumlah_resiko_periode_data');

    Route::get('rekap-rekomendasi','LaporanController@rekap_rekomendasi');
    Route::get('rekap-rekomendasi-data','LaporanController@rekap_rekomendasi_data');
    
    Route::get('rekap-jumlah-resiko-bidang','LaporanController@rekap_jumlah_resiko_bidang');
    Route::get('rekap-jumlah-resiko-bidang-data','LaporanController@rekap_jumlah_resiko_bidang_data');

    Route::get('rekap-perhitungan-tekn-pertanggal','LaporanController@rekap_perhitungan_tekn_pertanggal');
    Route::get('rekap-perhitungan-tekn-pertanggal-data','LaporanController@rekap_perhitungan_tekn_pertanggal_data');

    Route::get('rekap-perhitungan-tekn-status','LaporanController@rekap_perhitungan_tekn_status');
    Route::get('rekap-perhitungan-tekn-status-data','LaporanController@rekap_perhitungan_tekn_status_data');
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
