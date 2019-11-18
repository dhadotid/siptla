<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarTemuan;
use App\Models\DetailTemuan;
use App\Models\MasterDinas;
use App\Models\MasterBidangPengawasan;
use App\Models\MasterTemuan;
use App\Models\MasterSebab;
use App\Models\MasterRekomendasi;

use Auth;
use DB;

class LaporanTemuanController extends Controller
{
    public function index()
    {
        $dinas=MasterDinas::all();
        $daftar=DaftarTemuan::all();
        $detail=DetailTemuan::all();
        $det=array();
        foreach($detail as $k=>$v)
        {
            $det[$v->daftar_id][]=$v;
        }
        // dd($det);
        $d=$selesai=$baru=$tujuh=$enampuluh=array();
        foreach($daftar as $k=>$items)
        {
            if(isset($det[$items->id]))
            {
                foreach($det[$items->id] as $idx=>$v)
                {
                    // echo 'aa';
                    $selisih=selisihhari($v->created_at,date('Y-m-d'));
                    echo $selisih.'-';
                    $d[$items->dinas_id][]=$v;
                    if($v->flag==1)
                        $selesai[$items->dinas_id][]=$v;
                    else
                    {
                        if($selisih<=43)
                            $baru[$items->dinas_id][]=$v;
                        
                        if($selisih>43 && $selisih<=60)
                            $tujuh[$items->dinas_id][]=$v;
                        
                        if($selisih>60)
                            $enampuluh[$items->dinas_id][]=$v;
                    }
                }
                
            }
        }
        // dd("");
        return view('backend.pages.laporan.rekap-temuan')->with('dinas',$dinas)
            ->with(['d'=>$d,
                    'selesai'=>$selesai,
                    'baru'=>$baru,
                    'tujuh'=>$tujuh,
                    'enampuluh'=>$enampuluh]);
    }

    public function rekapdetail($opd)
    {
        $dinas=MasterDinas::where('nama_slug',$opd)->first();
        $daftar=DaftarTemuan::where('dinas_id',$dinas->id)->with(['pengawasan','aparat','dinas','daftar'])->get();
        $d=$selesai=$baru=$tujuh=$enampuluh=array();
        $hasil='';
        foreach($daftar as $k=>$v)
        {
            $selisih=selisihhari($v->created_at,date('Y-m-d'));
            $d[$v->dinas_id][]=$v;
            if($v->flag==1)
                $hasil='<button class="btn btn-xs btn-success" style="height:24px !important;">Selesai</button>';
            else
            {
                //$hasil.=$selisih;
                if($selisih<=7)
                    $hasil='<button class="btn btn-xs btn-default" style="height:24px !important;background:#ccc;">Baru</button>';
                else if( $selisih>43 && $selisih <= 60)
                    $hasil='<button class="btn btn-xs btn-warning" style="height:24px !important;">7 Hari Lagi Batas Akhir Tindak Lanjut</button>';
                else if( $selisih > 60)
                    $hasil='<button class="btn btn-xs btn-danger" style="height:24px !important;">Belum Selesai : &gt; 60 Hari</button>';
                else
                {
                    $maxdate=adddate($v->created_at,60);
                    $hasil='<button class="btn btn-xs btn-info" style="height:24px !important;">
                    Sedang Proses
                    </button><br>';
                    $hasil.='Maks Tgl :<br> <i class="fa fa-calendar"></i> '.date('d-m-Y',strtotime($maxdate));
                }
            }
        }
        $detail=DetailTemuan::with(['daftar','temuan','sebab','rekomendasi'])->get();
        $det=array();
        foreach($detail as $k=>$v)
        {
            $det[$v->daftar_id][]=$v;
        }
        return view('backend.pages.laporan.rekap-detail')
                ->with('dinas',$dinas)
                ->with('det',$det)
                ->with('daftar',$daftar)
                ->with('hasil',$hasil)
                ->with(['d'=>$d,
                    'selesai'=>$selesai,
                    'baru'=>$baru,
                    'tujuh'=>$tujuh,
                    'enampuluh'=>$enampuluh]);
    }

    public function rekomendasi_temuan($tahun)
    {
        $nilai = DetailTemuan::select('rekomendasi_id', DB::RAW('SUM(kerugian) as nilai_kerugian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('rekomendasi_id')->get();

        $kejadian = DetailTemuan::select('rekomendasi_id', DB::RAW('COUNT(*) as jumlah_kejadian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('rekomendasi_id')->get();

        $rekom = MasterRekomendasi::all();

        $totalkejadian = 0;
        foreach ($kejadian as $key => $value) {
            $totalkejadian += $value->jumlah_kejadian;
        }

        return view('backend.pages.laporan.rekomendasi-temuan')
            ->with('nilai', $nilai)
            ->with('rekom', $rekom)
            ->with('tahun', $tahun)
            ->with('totalkejadian', $totalkejadian)
            ->with('kejadian', $kejadian);
    }

    public function print_rekomendasi_temuan($tahun)
    {
        $nilai = DetailTemuan::select('rekomendasi_id', DB::RAW('SUM(kerugian) as nilai_kerugian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('rekomendasi_id')->get();

        $kejadian = DetailTemuan::select('rekomendasi_id', DB::RAW('COUNT(*) as jumlah_kejadian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('rekomendasi_id')->get();

        $rekom = MasterRekomendasi::all();

        $totalkejadian = 0;
        foreach ($kejadian as $key => $value) {
            $totalkejadian += $value->jumlah_kejadian;
        }

        return view('backend.pages.laporan.print-rekomendasi-temuan')
            ->with('nilai', $nilai)
            ->with('rekom', $rekom)
            ->with('totalkejadian', $totalkejadian)
            ->with('kejadian', $kejadian);
    }

    public function kelompok_temuan($tahun)
    {
        $nilai = DetailTemuan::select('temuan_id', DB::RAW('SUM(kerugian) as nilai_kerugian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('temuan_id')->get();

        $kejadian = DetailTemuan::select('temuan_id', DB::RAW('COUNT(*) as jumlah_kejadian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('temuan_id')->get();

        $temuan = MasterTemuan::all();

        $totalkejadian = 0;
        foreach ($kejadian as $key => $value) {
            $totalkejadian += $value->jumlah_kejadian;
        }

        return view('backend.pages.laporan.kelompok-temuan')
            ->with('nilai', $nilai)
            ->with('temuan', $temuan)
            ->with('tahun', $tahun)
            ->with('totalkejadian', $totalkejadian)
            ->with('kejadian', $kejadian);
    }

    public function print_kelompok_temuan($tahun)
    {
        $nilai = DetailTemuan::select('temuan_id', DB::RAW('SUM(kerugian) as nilai_kerugian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('temuan_id')->get();

        $kejadian = DetailTemuan::select('temuan_id', DB::RAW('COUNT(*) as jumlah_kejadian'))
            ->join('daftar_temuan', 'detail_temuan.daftar_id', '=', 'daftar_temuan.id')
            ->where('tahun', $tahun)
            ->groupby('temuan_id')->get();

        $temuan = MasterTemuan::all();

        $totalkejadian = 0;
        foreach ($kejadian as $key => $value) {
            $totalkejadian += $value->jumlah_kejadian;
        }

        return view('backend.pages.laporan.print-kelompok-temuan')
            ->with('nilai', $nilai)
            ->with('temuan', $temuan)
            ->with('tahun', $tahun)
            ->with('totalkejadian', $totalkejadian)
            ->with('kejadian', $kejadian);
    }
}
