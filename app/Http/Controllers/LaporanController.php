<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function temuan_per_bidang()
    {
        return view('backend.pages.laporan.temuan-per-bidang.index');
    }
    public function temuan_per_bidang_data()
    {
        return view('backend.pages.laporan.temuan-per-bidang.data');
    }
    //----------------------------
    public function temuan_per_unitkerja()
    {
        return view('backend.pages.laporan.temuan-per-unitkerja.index');
    }
    public function temuan_per_unitkerja_data()
    {
        return view('backend.pages.laporan.temuan-per-unitkerja.data');
    }
    //-------------------------
    public function temuan_per_lhp()
    {
        return view('backend.pages.laporan.temuan-per-lhp.index');
    }
    public function temuan_per_lhp_data()
    {
        return view('backend.pages.laporan.temuan-per-lhp.data');
    }
    //-------------------------
    public function tindaklanjut_per_lhp()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-lhp.index');
    }
    public function tindaklanjut_per_lhp_data()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-lhp.data');
    }
    //-------------------------
    public function tindaklanjut_per_bidang()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-bidang.index');
    }
    public function tindaklanjut_per_bidang_data()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-bidang.data');
    }
    //-------------------------
    public function tindaklanjut_per_unitkerja()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.index');
    }
    public function tindaklanjut_per_unitkerja_data()
    {
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.data');
    }
    //-------------------------
    public function tindak_lanjut()
    {
        return view('backend.pages.laporan.tindak-lanjut.index');
    }
    public function tindak_lanjut_data()
    {
        return view('backend.pages.laporan.tindak-lanjut.data');
    }
    //-------------------------
    public function rekap_lhp()
    {
        return view('backend.pages.laporan.rekap-lhp.index');
    }
    public function rekap_lhp_data()
    {
        return view('backend.pages.laporan.rekap-lhp.data');
    }
    //-------------------------
    public function rekap_status_rekomendasi()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi.index');
    }
    public function rekap_status_rekomendasi_data()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi.data');
    }
    //-------------------------
    public function rekap_status_rekomendasi_bidang()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi-bidang.index');
    }
    public function rekap_status_rekomendasi_bidang_data()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi-bidang.data');
    }
    //-------------------------
    public function rekap_status_rekomendasi_unitkerja()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi-unitkerja.index');
    }
    public function rekap_status_rekomendasi_unitkerja_data()
    {
        return view('backend.pages.laporan.rekap-status-rekomendasi-unitkerja.data');
    }
    //-------------------------
    public function rekap_jumlah_resiko_periode()
    {
        return view('backend.pages.laporan.rekap-jumlah-resiko-periode.index');
    }
    public function rekap_jumlah_resiko_periode_data()
    {
        return view('backend.pages.laporan.rekap-jumlah-resiko-periode.data');
    }
    //-------------------------
    public function rekap_rekomendasi()
    {
        return view('backend.pages.laporan.rekap-rekomendasi.index');
    }
    public function rekap_rekomendasi_data()
    {
        return view('backend.pages.laporan.rekap-rekomendasi.data');
    }
}
