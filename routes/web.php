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
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('pic-unit','PICUnitController');
    Route::resource('level-pic','LevelPicController');
    Route::resource('data-opd','MasterDinasController');
    Route::resource('kepala-opd','MasterKepalaDinasController');
    Route::resource('data-penyebab','MasterSebabController');
    Route::resource('data-rekomendasi','MasterRekomendasiController');
    Route::resource('bidang-pengawasan','MasterBidangPengawasanController');
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
    Route::get('data-lhp-data/{tahun?}','DataTemuanController@data_lhp')->name('data-lhp.data');
    Route::get('data-lhp-cek-kode/{pemeriksa?}','DataTemuanController@data_lhp_cek_kode')->name('data-lhp.cek-kode');
    Route::get('data-lhp-detail/{id}/{offset}','DataTemuanController@detail_lhp')->name('data-lhp.detail');
    Route::get('data-lhp-review/{id}','DataTemuanController@review_lhp')->name('data-lhp.review');
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

    Route::get('data-rekanan','RekananController@data_rekanan')->name('data-rekanan');
});


// DATA LHP

// END DATA LHP

Route::get('tindak-lanjut/{id}', 'TindakLanjutTemuanController@index')->name('tindak-lanjut.index');
Route::post('tindak-lanjut', 'TindakLanjutTemuanController@store')->name('tindak-lanjut.store');
Route::get('tindak-lanjut/{id}/edit', 'TindakLanjutTemuanController@edit')->name('tindak-lanjut.edit');
Route::get('tindak-lanjut/download/{filename}', 'TindakLanjutTemuanController@download')->name('tindak-lanjut.download');
Route::put('tindak-lanjut/{id}/update', 'TindakLanjutTemuanController@update')->name('tindak-lanjut.update');
Route::get('tindak-lanjut/{id}/show', 'TindakLanjutTemuanController@show')->name('tindak-lanjut.show');
Route::get('tindak-lanjut/{id}/selesai', 'TindakLanjutTemuanController@selesai')->name('tindak-lanjut.selesai');

Route::get('rekap-temuan','LaporanTemuanController@index')->middleware('auth');
Route::get('rekap-temuan-detail/{opd}','LaporanTemuanController@rekapdetail')->middleware('auth');

Route::get('rekomendasi-temuan/{tahun}', 'LaporanTemuanController@rekomendasi_temuan')->name('rekomendasi-temuan')->middleware('auth');
Route::get('print-rekomendasi-temuan/{tahun}', 'LaporanTemuanController@print_rekomendasi_temuan')->name('print-rekomendasi-temuan')->middleware('auth');

Route::get('laporan-kelompok-temuan/{tahun}', 'LaporanTemuanController@kelompok_temuan')->name('laporan-kelompok-temuan')->middleware('auth');
Route::get('print-kelompok-temuan/{tahun}', 'LaporanTemuanController@print_kelompok_temuan')->name('print-kelompok-temuan')->middleware('auth');

Route::resource('temuan','DaftarTemuanController')->middleware('auth');
Route::resource('list-temuan','DaftarTemuanController')->middleware('auth');
Route::get('list-temuan-data/{dinas_id?}/{tahun?}/{bidang_id?}','DaftarTemuanController@data')->middleware('auth');
Route::get('detail-form/{daftar_id}/{dinas_id?}/{tahun?}/{bidang_id?}','DaftarTemuanController@form_detail')->middleware('auth');
Route::post('detail-temuan-update/{id}','DaftarTemuanController@update_detail')->middleware('auth');
Route::post('detail-temuan-delete','DaftarTemuanController@detail_destroy')->middleware('auth');
Route::post('detail-temuan-verifikasi','DaftarTemuanController@detail_verifikasi')->middleware('auth');

Auth::routes();
Route::get('logout',function(){
    Auth::logout();
    return redirect('login');
});
Route::get('/home', 'HomeController@index')->name('home');
