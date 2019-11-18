<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailTemuan;

use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * Status Temuan:
         * 0 : Menunggu Verifikasi
         * 2 : Belum Tindak Lanjut
         * 3 : Telah Tindak Lanjut
         * 4 : Selesai
         */
        
        $level = Auth::user()->level;
        $mv = $btl = $ttl = $sel = 0;

        if ($level == 1 || $level == 2) { // administrator or operator
            $temuan = DetailTemuan::orderby('created_at', 'desc')->get();

            foreach ($temuan as $value) {
                if ($value->flag==0) {
                    $mv++;
                } else if ($value->flag==2) {
                    $btl++;
                } else if ($value->flag==3) {
                    $ttl++;
                } else if ($value->flag==4) {
                    $sel++;
                }
            }
        } else if ($level == 3) { // admin opd
            $temuan = DetailTemuan::select('*', 'detail_temuan.flag as theflag')
                ->join('daftar_temuan', 'daftar_temuan.id', '=', 'detail_temuan.daftar_id')
                ->where('dinas_id', Auth::user()->user->dinas_id)
                ->where('detail_temuan.flag', '!=',0)
                ->orderby('detail_temuan.created_at', 'desc')
                ->limit(5)
                ->get();
            // $temuan = DetailTemuan::select('*', 'detail_temuan.flag as theflag')
            //     ->join('daftar_temuan', 'daftar_temuan.id', '=', 'detail_temuan.daftar_id')
            //     ->where('dinas_id', Auth::user()->user->dinas_id)
            //     ->get();

            foreach ($temuan as $value) {
                if ($value->theflag==0) {
                    $mv++;
                } else if ($value->theflag==2) {
                    $btl++;
                } else if ($value->theflag==3) {
                    $ttl++;
                } else if ($value->theflag==4) {
                    $sel++;
                }
            }
        }

        return view('backend.pages.dashboard.index')
            ->with('mv', $mv)
            ->with('btl', $btl)
            ->with('ttl', $ttl)
            ->with('sel', $sel)
            ->with('temuan', $temuan);
    }
}
