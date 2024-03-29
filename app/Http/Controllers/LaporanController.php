<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksa;
use App\Models\DaftarTemuan;
use App\Models\LevelResiko;
use App\Models\Bidang;
use App\Models\PICUnit;
use App\Models\PejabatTandaTangan;
use App\Models\StatusRekomendasi;
use App\Models\TindakLanjutTemuan;
use PDF;
class LaporanController extends Controller
{
    public function temuan_per_bidang()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        return view('backend.pages.laporan.temuan-per-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
    public function temuan_per_bidang_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[]=$v->id;
        }

        // return $pic_unit;
        // if(isset($unit[$bidang])) 
        // {
        //     foreach($unit[$bidang] as $k=>$v){
        //         $pic_unit[]=$v;
        //     }
        // }


        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('daftar_lhp.no_lhp')
                                    ->orderBy('data_temuan.no_temuan')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('daftar_lhp.no_lhp')
                                    ->orderBy('data_temuan.no_temuan')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
            }
            else
            {
                $alldatas=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where($wh)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->whereIn('data_rekomendasi.pic_1_temuan_id', $pic_unit)
                                ->orderBy('daftar_lhp.no_lhp')
                                ->orderBy('data_temuan.no_temuan')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
    
                $alldata=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $alldata[]=$v;
                        }
                    }
                    else
                        $alldata[]=$v;
                }
            }
        }

        $lhp=$temuan=$rekomendasi=array();
        foreach($alldata as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.temuan-per-bidang.data')
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('nbidang',$nbidang)
                    ->with('lhp',$lhp)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function temuan_per_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[]=$v->id;
        }


        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('daftar_lhp.no_lhp')
                                    ->orderBy('data_temuan.no_temuan')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('daftar_lhp.no_lhp')
                                    ->orderBy('data_temuan.no_temuan')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
            }
            else
            {
                $alldatas=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where($wh)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->whereIn('data_rekomendasi.pic_1_temuan_id', $pic_unit)
                                ->orderBy('daftar_lhp.no_lhp')
                                ->orderBy('data_temuan.no_temuan')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
    
                $alldata=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $alldata[]=$v;
                        }
                    }
                    else
                        $alldata[]=$v;
                }
            }
        }

        $lhp=$temuan=$rekomendasi=array();
        foreach($alldata as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
        }
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.temuan-per-bidang.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-temuan-perbidang.pdf');
        // return view('backend.pages.laporan.temuan-per-bidang.cetakpdf')
                    
    }
    //----------------------------
    public function temuan_per_unitkerja()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.temuan-per-unitkerja.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
    public function temuan_per_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        if($request->unitkerja2!='')
        {
            if($request->unitkerja2!=0)
                $alldata->where('data_rekomendasi.pic_2_temuan_id','like',"".$request->unitkerja2.",%");
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.temuan-per-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-temuan-unitkerja.pdf');
    }
    public function temuan_per_unitkerja_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        if($request->unitkerja2!='')
        {
            if($request->unitkerja2!=0)
                $alldata->where('data_rekomendasi.pic_2_temuan_id','like',"".$request->unitkerja2.",%");
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.temuan-per-unitkerja.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('nbidang',$nbidang)
                    ->with('lhp',$lhp)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
    //-------------------------
    public function temuan_per_lhp()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.temuan-per-lhp.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
    public function temuan_per_lhp_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        if($request->unitkerja2!='')
        {
            if($request->unitkerja2!=0)
                $alldata->where('data_rekomendasi.pic_2_temuan_id','like',"".$request->unitkerja2.",%");
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.temuan-per-lhp.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('nbidang',$nbidang)
                    ->with('lhp',$lhp)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }

    public function temuan_per_lhp_pdf(Request $request)
    {
         list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        if($request->unitkerja2!='')
        {
            if($request->unitkerja2!=0)
                $alldata->where('data_rekomendasi.pic_2_temuan_id','like',"".$request->unitkerja2.",%");
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.temuan-per-lhp.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-temuan-per-lhp.pdf');

    }
    //-------------------------
    public function tindaklanjut_per_lhp()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();

        return view('backend.pages.laporan.tindak-lanjut-per-lhp.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi);
    }
    public function tindaklanjut_per_lhp_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.tindak-lanjut-per-lhp.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('tindaklanjut',$tindaklanjut)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function tindaklanjut_per_lhp_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['tindaklanjut']=$tindaklanjut;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-lhp.cetakpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->download('laporan-tindaklanjut-per-lhp.pdf');
    }
    //-------------------------
    public function tindaklanjut_per_bidang()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        
        return view('backend.pages.laporan.tindak-lanjut-per-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi);
    }
    public function tindaklanjut_per_bidang_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp='';
        // $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
        if($request->no_lhp!='')
        {
            if(count($request->no_lhp)!=0)
            {
                $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
                $no_lhp = implode(',',$request->no_lhp);
                // return $request->no_lhp;
            }
        }                      
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        if($request->bidang!='')
        {
            foreach($request->bidang as $kb=>$vb)
            {
                if(isset($bidunit[$vb]))
                {
                    foreach($bidunit[$vb] as $kk=>$vv)
                    {
                        $arraybid[]=$vv;
                        $dbid.=$vv.',';
                    }
                }
            }
            
            if(count($arraybid)!=0)
            {
                // return $arraybid;
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        // return $all;
        foreach($all as $k=>$v)
        {
            // $pic2temuanid=explode(',',$v->pic_2_temuan_id);
            // if($v->pic_2_temuan_id!=',' || $v->pic_2_temuan_id!='')
            // {
            //     foreach($pic2temuanid as $kp=>$vp)
            //     {
            //         if(in_array($vp,$arraybid))
            //         {
            //             if(isset($pic_unit[$vp]))
            //             {
            //                 $lhp[$v->id_lhp]=$v;
            //                 $temuan[$v->id_lhp][$v->id_temuan]=$v;
            //                 $rekomendasi[$v->id_rekom]=$v;
            //                 $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            //             }
            //         }
            //     }
            //     // return $pic2temuanid;
            // }
            // else
            // {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.tindak-lanjut-per-bidang.data')
                    ->with('bidang',$dbid)
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('tindaklanjut',$tindaklanjut)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('rekomendasi',$rekomendasi);
    
    }
    public function tindaklanjut_per_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = explode(',',$request->no_lhp);
        $bidang = explode(',',$request->bidang);
        // $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
        if($request->no_lhp!='')
        {
            if(count($no_lhp)!=0)
            {
                $alldata->whereIn('daftar_lhp.id',$no_lhp);
                // return $request->no_lhp;
            }
        }                      

        $arraybid=array();
        // return $request->bidang;
        if($request->bidang!='')
        {
            foreach($bidang as $kb=>$vb)
            {
                if(isset($bidunit[$vb]))
                {
                    foreach($bidunit[$vb] as $kk=>$vv)
                    {
                        $arraybid[]=$vv;
                    }
                }
            }
            
            if(count($arraybid)!=0)
            {
                // return $arraybid;
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['tindaklanjut']=$tindaklanjut;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-bidang.cetakpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->download('laporan-tindaklanjut-per-bidang.pdf');
    }
    //-------------------------
    public function tindaklanjut_per_unitkerja()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $picunit=PICUnit::all();
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$picunit)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi);
    }
    public function tindaklanjut_per_unitkerja_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp='';
        // $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');

        $unit_kerja1=$unit_kerja2='';
        if($request->unit_kerja1!='')
        {
            if(count($request->unit_kerja1)!=0)
            {
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$request->unit_kerja1);
                $unit_kerja1 = implode(',',$request->unit_kerja1);
            }
        }                      
        if($request->unit_kerja2!='')
        {
            if(count($request->unit_kerja2)!=0)
            {
                // $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$request->unit_kerja1);
                $unit_kerja2 = implode(',',$request->unit_kerja2);
            }
        }                      
        
        if($request->no_lhp!='')
        {
            if(count($request->no_lhp)!=0)
            {
                $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
                $no_lhp = implode(',',$request->no_lhp);
                // return $request->no_lhp;
            }
        }                      
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        if($request->bidang!='')
        {
            foreach($request->bidang as $kb=>$vb)
            {
                if(isset($bidunit[$vb]))
                {
                    foreach($bidunit[$vb] as $kk=>$vv)
                    {
                        $arraybid[]=$vv;
                        $dbid.=$vv.',';
                    }
                }
            }
            
            if(count($arraybid)!=0)
            {
                // return $arraybid;
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        // return $all;
        foreach($all as $k=>$v)
        {
            // $pic2temuanid=explode(',',$v->pic_2_temuan_id);
            // if($v->pic_2_temuan_id!=',' || $v->pic_2_temuan_id!='')
            // {
            //     foreach($pic2temuanid as $kp=>$vp)
            //     {
            //         if(in_array($vp,$arraybid))
            //         {
            //             if(isset($pic_unit[$vp]))
            //             {
            //                 $lhp[$v->id_lhp]=$v;
            //                 $temuan[$v->id_lhp][$v->id_temuan]=$v;
            //                 $rekomendasi[$v->id_rekom]=$v;
            //                 $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            //             }
            //         }
            //     }
            //     // return $pic2temuanid;
            // }
            // else
            // {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.data')
                    ->with('unit_kerja1',$unit_kerja1)
                    ->with('unit_kerja2',$unit_kerja2)
                    ->with('bidang',$dbid)
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('tindaklanjut',$tindaklanjut)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function tindaklanjut_per_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = explode(',',$request->no_lhp);
        $unit_kerja1 = explode(',',$request->unit_kerja1);
        $unit_kerja2 = explode(',',$request->unit_kerja2);
        $bidang = explode(',',$request->bidang);
        // $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
        if($request->no_lhp!='')
        {
            if(count($no_lhp)!=0)
            {
                $alldata->whereIn('daftar_lhp.id',$no_lhp);
                // return $request->no_lhp;
            }
        }                      

        $arraybid=array();
        // return $request->bidang;
        if($request->bidang!='')
        {
            foreach($bidang as $kb=>$vb)
            {
                if(isset($bidunit[$vb]))
                {
                    foreach($bidunit[$vb] as $kk=>$vv)
                    {
                        $arraybid[]=$vv;
                    }
                }
            }
            
            if(count($arraybid)!=0)
            {
                // return $arraybid;
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['tindaklanjut']=$tindaklanjut;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-unitkerja.cetakpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->download('laporan-tindaklanjut-per-bidang.pdf');
    }
    //-------------------------
    public function tindak_lanjut()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $picunit=PICUnit::all();
        return view('backend.pages.laporan.tindak-lanjut.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$picunit)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi);
    }
    public function tindak_lanjut_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp='';
        // $no_lhp = $request->no_lhp;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');

                
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='')
        {
            $alldata->where(function($query) use ($unitkerja){
                                    $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                                    $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                                });
            // ->where('data_rekomendasi.pic_1_temuan_id',$request->unitkerja);
        }
        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        // return $all;
        foreach($all as $k=>$v)
        {

                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;

        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $temuan;
        return view('backend.pages.laporan.tindak-lanjut.data')
                    ->with('unit_kerja',$unitkerja)
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('tindaklanjut',$tindaklanjut)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function tindak_lanjut_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $unit_kerja = $request->unit_kerja;
        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        
        

        if($request->statusrekomendasi!='')
        {
            if($request->statusrekomendasi!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekomendasi;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                     

        $arraybid=array();
        // return $request->bidang;
        if($request->unitkerja!='')
        {
            $unitkerja=$request->unitkerja;
            $alldata->where(function($query) use ($unitkerja){
                                    $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                                    $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                                });
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['tindaklanjut']=$tindaklanjut;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut.cetakpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->download('matriks-tindaklanjut.pdf');
    }
    //-------------------------
    public function status_penyelesaian_rekomendasi()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
   
    public function status_penyelesaian_rekomendasi_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $wh=array();
        $statusrekom=StatusRekomendasi::all();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    


        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            
            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        // return $jlh_by_status;
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('nbidang',$nbidang)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
     public function status_penyelesaian_rekomendasi_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        if($request->unitkerja1!='')
        {
            if($request->unitkerja1!=0)
                $wh['data_rekomendasi.pic_1_temuan_id']=$request->unitkerja1;
        }

        $picunit=PICUnit::where('bidang',$bidang)->get();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    


        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        if($bidang==0)
        {
                $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                    $alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
            }
            else
            {
                
                    
                    $alldatas=$alldata->orderBy('daftar_lhp.no_lhp')
                            ->orderBy('data_temuan.no_temuan')
                            ->orderBy('data_rekomendasi.nomor_rekomendasi')
                            ->get();
    
                $all=array();
                foreach($alldatas as $k=>$v)
                {
                    $pic2unit=explode(',',$v->pic_2_temuan_id);
                    if(count($pic2unit)>1)
                    {
                        foreach($pic2unit as $kk=>$vv)
                        {
                            if(in_array($vv,$pic_unit))
                                $all[]=$v;
                        }
                    }
                    else
                        $all[]=$v;
                }
            }
        }
        // return $all;
        $lhp=$temuan=$rekomendasi=array();
        foreach($all as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_lhp][$v->id_temuan]=$v;
            $rekomendasi[$v->id_rekom]=$v;
            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
 
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-status-penyelesaian-rekomendasi.pdf');
    }
     //-------------------------
    public function status_penyelesaian_rekomendasi_pemeriksa()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
   
    public function status_penyelesaian_rekomendasi_pemeriksa_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = $request->unitkerja1;
        $unitkerja2 = $request->unitkerja2;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
        

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='')
        {
            $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
            $no_lhp=implode(',',$request->no_lhp);
        }
        if($request->unitkerja1!='')
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$request->unitkerja1);
            $unitkerja1=implode(',',$request->unitkerja1);
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            $dbidang=implode(',',$bidang);
            $picunit=PICUnit::whereIn('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $jlh_by_status;
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('unitkerja1',$unitkerja1)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
     public function status_penyelesaian_rekomendasi_pemeriksa_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = explode(',',$request->bidang);
        $tampilkannilai = $request->tampilkannilai;
        $unitkerja1 = explode(',',$request->unitkerja1);
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='')
        {
            $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
            $no_lhp=implode(',',$request->no_lhp);
        }
        if($request->unitkerja1!='')
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$request->unitkerja1);
            // $unitkerja1=implode(',',$request->unitkerja1);
        }

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $picunit=PICUnit::whereIn('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-status-penyelesaian-rekomendasi-pemeriksa.pdf');
    }
    //-------------------------
    public function status_penyelesaian_rekomendasi_bidang()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
   
    public function status_penyelesaian_rekomendasi_bidang_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
        

        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='')
        {
            $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
            $no_lhp=implode(',',$request->no_lhp);
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            $dbidang=implode(',',$bidang);
            $picunit=PICUnit::whereIn('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $jlh_by_status;
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function status_penyelesaian_rekomendasi_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = explode(',',$request->bidang);
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
        if($request->level_resiko!='')
        {
            if($request->level_resiko!=0)
                $wh['data_temuan.level_resiko_id']=$request->level_resiko;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        

        

        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='')
        {
            $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
            $no_lhp=implode(',',$request->no_lhp);
        }
    

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $picunit=PICUnit::whereIn('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-status-penyelesaian-rekomendasi-bidang.pdf');
    }
    //-------------------------
    public function status_penyelesaian_rekomendasi_tahun()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
   
    public function status_penyelesaian_rekomendasi_tahun_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $bidang = $request->bidang;
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
       
        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $jlh_by_status;
        $dunitkerja=PICUnit::find($unitkerja);
        // return $unitkerja.'-'.$dunitkerja;
        $nbidang=Bidang::find($bidang);
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.data')
                    ->with('nbidang',$nbidang)
                    ->with('pic_unit',$pic_unit)
                    ->with('dunitkerja',$dunitkerja)
                    ->with('unitkerja',$unitkerja)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function status_penyelesaian_rekomendasi_tahun_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        // $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
       if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $dunitkerja=PICUnit::find($unitkerja);
        $nbidang=Bidang::find($bidang);
        $data['dunitkerja']=$dunitkerja;
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-status-penyelesaian-rekomendasi-tahun.pdf');
    }
    //-------------------------
    public function status_penyelesaian_rekomendasi_unitkerja()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
   
    public function status_penyelesaian_rekomendasi_unitkerja_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $bidang = $request->bidang;
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
       
        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $jlh_by_status;
        $dunitkerja=PICUnit::find($unitkerja);
        // return $unitkerja.'-'.$dunitkerja;
        $nbidang=Bidang::find($bidang);
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.data')
                    ->with('nbidang',$nbidang)
                    ->with('pic_unit',$pic_unit)
                    ->with('dunitkerja',$dunitkerja)
                    ->with('unitkerja',$unitkerja)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function status_penyelesaian_rekomendasi_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        // $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
       if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $dunitkerja=PICUnit::find($unitkerja);
        $nbidang=Bidang::find($bidang);
        $data['dunitkerja']=$dunitkerja;
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-status-penyelesaian-rekomendasi-unitkerja.pdf');
    }

    //--------------------------

    public function rekomendasi_overdue_unitkerja()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.rekomendasi-overdue-unitkerja.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
    public function rekomendasi_overdue_unitkerja_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $bidang = $request->bidang;
        $dbidang=$unitkerja='';
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
       
        $statusrekom=[2,3];
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->whereIn('data_rekomendasi.status_rekomendasi_id',$statusrekom)
                                    ->where('data_rekomendasi.tanggal_penyelesaian','<',date('Y-m-d'));
                                    
        if($request->pemeriksa!='' && $request->pemeriksa!=0)
        {
            $alldata->where('daftar_lhp.pemeriksa_id',$request->pemeriksa);
            // if($request->pemeriksa!=0)
            //     $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        if($request->no_lhp!='' && $request->no_lhp!=0)
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!=0)
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    foreach($picunit2 as $kp=>$vp)
                    {
                        if($vp!='')
                        {
                            // if(array_intersect($picunit2,$pic_unit))
                            if(in_array($vp,$pic_unit))
                            {
                                $lhp[$v->id_lhp]=$v;
                                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                                $rekomendasi[$v->id_rekom]=$v;
                                // $rekomendasi[$v->id_rekom][]=$v;
                                $jlh_by_status[$v->id_rekom][$v->level_resiko_id][]=$v->level_resiko_id;
                            }
                        }
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                // $rekomendasi[$v->id_rekom][]=$v;
                $jlh_by_status[$v->id_rekom][$v->level_resiko_id][]=$v->level_resiko_id;
            }
        }
        $bid=Bidang::all();
        $nbid=array();
        foreach($bid as $k=>$v)
        {
            $nbid[$v->id]=$v;
        }
        $dpicunit=PICUnit::all();
        $dp=array();
        foreach($dpicunit as $kpic=>$vpic)
        {
            $dp[$vpic->id]=$vpic;
        }

        // return $rekomendasi;
        return view('backend.pages.laporan.rekomendasi-overdue-unitkerja.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('nbid',$nbid)
                    ->with('dp',$dp)
                    ->with('unitkerja',$unitkerja)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function rekomendasi_overdue_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        // $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
       if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $bid=Bidang::all();
        $nbid=array();
        foreach($bid as $k=>$v)
        {
            $nbid[$v->id]=$v;
        }
        
        $data['nbid']=$nbid;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.rekomendasi-overdue-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-rekomendasi-overdue-unitkerja.pdf');
    }

    //--------------------------

    public function laporan_rekomendasi_overdue()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        return view('backend.pages.laporan.laporan-rekomendasi-overdue.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko);
    }
    public function laporan_rekomendasi_overdue_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        $bidang = $request->bidang;
        $dbidang=$unitkerja='';
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        $wh=array();
       
        $statusrekom=[2,3];
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->whereIn('data_rekomendasi.status_rekomendasi_id',$statusrekom)
                                    ->where('data_rekomendasi.tanggal_penyelesaian','<',date('Y-m-d'));
                                    
        if($request->pemeriksa!='' && $request->pemeriksa!=0)
        {
            $alldata->where('daftar_lhp.pemeriksa_id',$request->pemeriksa);
            // if($request->pemeriksa!=0)
            //     $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        if($request->no_lhp!='' && $request->no_lhp!=0)
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!=0)
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
        }
        return view('backend.pages.laporan.laporan-rekomendasi-overdue.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('unitkerja',$unitkerja)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('no_lhp',$no_lhp)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$no_lhp)
                    ->with('rekomendasi',$rekomendasi);
    }
    
    public function laporan_rekomendasi_overdue_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $pemeriksa = $request->pemeriksa;
        $no_lhp = $request->no_lhp;
        // $no_lhp = explode(',',$request->no_lhp);
        $level_resiko = $request->level_resiko;
        $bidang = $request->bidang;
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        $statusrekom=StatusRekomendasi::all();
        $wh=array();
        $nbidang=Bidang::find($bidang);
        // return $bidang;
       if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        
        // return $wh;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at');
                                    
        if($request->no_lhp!='' && $request->no_lhp!='0')
        {
            $alldata->where('daftar_lhp.id',$no_lhp);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$request->unitkerja;
        if($request->unitkerja!='' && $request->unitkerja!='0')
        {
            $alldata->where(function($query) use ($unitkerja){
                            $query->where('data_rekomendasi.pic_1_temuan_id', $unitkerja);
                            $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$unitkerja%,");
                        });

            
        }
       

        $all=$alldata->get();
        $npemeriksa=Pemeriksa::find($pemeriksa);
        $unit=array();
        $pic_unit=array();

        if($bidang!='')
        {
            // $dbidang=implode(',',$bidang);
            $dbidang=$bidang;
            $picunit=PICUnit::where('bidang',$bidang)->get();
        
            
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        // return $all;
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        
        foreach($all as $k=>$v)
        {
            if(count($pic_unit)!=0)
            {
                if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
                {
                    $picunit2=explode(',',$v->pic_2_temuan_id);
                    if(array_intersect($picunit2,$pic_unit))
                    {
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }
            // $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $dunitkerja=PICUnit::find($unitkerja);
        $nbidang=Bidang::find($bidang);
        $data['dunitkerja']=$dunitkerja;
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$bidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$no_lhp;
        $data['statusrekom']=$statusrekom;
        $data['rekomendasi']=$rekomendasi;

        $pdf = PDF::loadView('backend.pages.laporan.laporan-rekomendasi-overdue.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-rekomendasi-overdue.pdf');
    }
}