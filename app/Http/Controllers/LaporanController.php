<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksa;
use App\Models\DaftarTemuan;
use App\Models\LevelResiko;
use App\Models\Bidang;
use App\Models\PICUnit;
use App\Models\PejabatTandaTangan;
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
    //-------------------------
    public function rekap_jumlah_resiko_bidang()
    {
        return view('backend.pages.laporan.rekap-resiko-per-bidang.index');
    }
    public function rekap_jumlah_resiko_bidang_data()
    {
        return view('backend.pages.laporan.rekap-resiko-per-bidang.data');
    }
    //-------------------------
    public function rekap_perhitungan_tekn_pertanggal()
    {
        return view('backend.pages.laporan.rekap-tekn-per-tanggal.index');
    }
    public function rekap_perhitungan_tekn_pertanggal_data()
    {
        return view('backend.pages.laporan.rekap-tekn-per-tanggal.data');
    }
    //-------------------------
    public function rekap_perhitungan_tekn_status()
    {
        return view('backend.pages.laporan.rekap-tekn-per-status.index');
    }
    public function rekap_perhitungan_tekn_status_data()
    {
        return view('backend.pages.laporan.rekap-tekn-per-status.data');
    }
}
