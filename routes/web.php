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

Route::get('/dashboard', 'DashboardController@index')->middleware('auth');

Route::resource('data-opd','MasterDinasController')->middleware('auth');
Route::resource('kepala-opd','MasterKepalaDinasController')->middleware('auth');
Route::resource('data-temuan','MasterTemuanController')->middleware('auth');
Route::resource('data-penyebab','MasterSebabController')->middleware('auth');
Route::resource('data-rekomendasi','MasterRekomendasiController')->middleware('auth');
Route::resource('bidang-pengawasan','MasterBidangPengawasanController')->middleware('auth');
Route::resource('users','UsersController')->middleware('auth');

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
