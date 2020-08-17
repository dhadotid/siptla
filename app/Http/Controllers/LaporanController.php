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
use App\Models\LevelPIC;
use App\Models\DataTemuan;
use App\Models\MasterTemuan;
use App\Models\JenisAudit;
use App\Models\DataRekomendasi;
use Auth;
use PDF;
use Excel;
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
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }

        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);

        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[]=$v->id;
        }
        
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
            $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')
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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')
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
                                ->where('daftar_lhp.status_lhp','Publish LHP');
                                if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                    $alldatas = $alldatas->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                }
                                if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                    $alldatas = $alldatas->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                }
                                if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                    $alldatas = $alldatas->whereIn('daftar_lhp.id', $arrayLHP);
                                }
                $alldatas = $alldatas->whereNull('data_rekomendasi.deleted_at')
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
                    ->with('no_lhp',$arrayLHP)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi)
                    //arrayData
                    ->with('bidangTitle',$bidangTitle);
                    // ->with('arrayPemeriksa', $arrayPemeriksa)
                    // ->with('arrayLHP', $arrayLHP)
                    // ->with('arrayLevelResiko', $arrayLevelResiko)
                    // ->with('arrayBidang', $arrayBidang);
    }
    public function temuan_per_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }

        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);

        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

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
        
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
            $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')
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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')
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
                                ->where('daftar_lhp.status_lhp','Publish LHP');
                                if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                    $alldatas = $alldatas->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                }
                                if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                    $alldatas = $alldatas->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                }
                                if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                    $alldatas = $alldatas->whereIn('daftar_lhp.id', $arrayLHP);
                                }
                $alldatas = $alldatas->whereNull('data_rekomendasi.deleted_at')
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
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['bidangTitle']=$bidangTitle;

        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.temuan-per-bidang.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-temuan-perbidang.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.temuan-per-bidang.cetakpdf', $data), 'laporan-temuan-perbidang.xlsx');
        }
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

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unitkerja1 != '' && $request->unitkerja1!=0){
            $unitkerja1 = $request->unitkerja1;
            foreach (explode(',', $unitkerja1) as $v ) {
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unitkerja2 != '' && $request->unitkerja2!=0){
            $unitkerja2 = $request->unitkerja2;
            foreach (explode(',', $unitkerja2) as $v ) {
                $arrayUnitKerja2[] = $v;
            }
        }

        $picData = PICUnit::whereIn('id', $arrayUnitKerja1)->get();
        
        $picTitle='';
        if(count($picData)>0){
            foreach($picData as $k=>$v){
                $picTitle.=$v->nama_pic.', ';
            }
        }else{
            $picTitle.='Semua';
        }

        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
        }
        
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->rightjoin('pic_unit', 'data_rekomendasi.pic_1_temuan_id', '=', 'pic_unit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0&& !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    $alldata=$alldata->whereNull('data_rekomendasi.deleted_at');
                                    

        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2)){
            $alldata = $alldata->whereIn('data_rekomendasi.pic_2_temuan_id', 'like',"".$arrayUnitKerja2.",%");
        }

        $all=$alldata->get();
        // $npemeriksa=Pemeriksa::find($pemeriksa);
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
                $alldata = $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata = $alldata->orderBy('daftar_lhp.no_lhp')
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

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        // $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['picTitle']=$picTitle;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.temuan-per-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-temuan-unitkerja.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.temuan-per-unitkerja.cetakpdf', $data), 'laporan-temuan-unitkerja.xlsx');
        }
    }
    public function temuan_per_unitkerja_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unitkerja1 != '' && $request->unitkerja1!=0){
            $unitkerja1 = $request->unitkerja1;
            $unitkerja1 = implode(',', $unitkerja1);
            foreach (explode(',', $unitkerja1) as $v ) {
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unitkerja2 != '' && $request->unitkerja2!=0){
            $unitkerja2 = $request->unitkerja2;
            $unitkerja2 = implode(',', $unitkerja2);
            foreach (explode(',', $unitkerja2) as $v ) {
                $arrayUnitKerja2[] = $v;
            }
        }

        $picData = PICUnit::whereIn('id', $arrayUnitKerja1)->get();
        
        $picTitle='';
        if(count($picData)>0){
            foreach($picData as $k=>$v){
                $picTitle.=$v->nama_pic.', ';
            }
        }else{
            $picTitle.='Semua';
        }

        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
        }
        
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->rightjoin('pic_unit', 'data_rekomendasi.pic_1_temuan_id', '=', 'pic_unit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0&& !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    $alldata=$alldata->whereNull('data_rekomendasi.deleted_at');
                                    

        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2)){
            $alldata = $alldata->whereIn('data_rekomendasi.pic_2_temuan_id', 'like',"".$arrayUnitKerja2.",%");
        }

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
                $alldata = $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata = $alldata->orderBy('daftar_lhp.no_lhp')
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
        // return json_encode($alldata);
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
                    // ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('picTitle', $picTitle);
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

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unitkerja1 != '' && $request->unitkerja1!=0){
            $unitkerja1 = $request->unitkerja1;
            $unitkerja1 = implode(',', $unitkerja1);
            foreach (explode(',', $unitkerja1) as $v ) {
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unitkerja2 != '' && $request->unitkerja2!=0){
            $unitkerja2 = $request->unitkerja2;
            $unitkerja2 = implode(',', $unitkerja2);
            foreach (explode(',', $unitkerja2) as $v ) {
                $arrayUnitKerja2[] = $v;
            }
        }

        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
        }

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
                                    ->rightjoin('pic_unit', 'data_rekomendasi.pic_1_temuan_id', '=', 'pic_unit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0&& !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    $alldata=$alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2)){
            $alldata = $alldata->whereIn('data_rekomendasi.pic_2_temuan_id', 'like',"".$arrayUnitKerja2.",%");
        }

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
                $alldata= $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata= $alldata->orderBy('daftar_lhp.no_lhp')
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
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
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
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unitkerja1 != '' && $request->unitkerja1!=0){
            $unitkerja1 = $request->unitkerja1;
            foreach (explode(',', $unitkerja1) as $v ) {
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unitkerja2 != '' && $request->unitkerja2!=0){
            $unitkerja2 = $request->unitkerja2;
            foreach (explode(',', $unitkerja2) as $v ) {
                $arrayUnitKerja2[] = $v;
            }
        }

        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
        }

        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->rightjoin('pic_unit', 'data_rekomendasi.pic_1_temuan_id', '=', 'pic_unit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0&& !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    $alldata=$alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2)){
            $alldata = $alldata->whereIn('data_rekomendasi.pic_2_temuan_id', 'like',"".$arrayUnitKerja2.",%");
        }

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
        {
                $alldata= $alldata->orderBy('daftar_lhp.no_lhp')
                        ->orderBy('data_temuan.no_temuan')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        }
        else
        {

            if($pic_unit==0)
            {
                $alldata= $alldata->orderBy('daftar_lhp.no_lhp')
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
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.temuan-per-lhp.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-temuan-per-lhp.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.temuan-per-lhp.cetakpdf', $data), 'laporan-temuan-per-lhp.xlsx');
        }

    }
    //-------------------------
    public function tindaklanjut_per_lhp()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();

        return view('backend.pages.laporan.tindak-lanjut-per-lhp.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('levelresiko',$levelresiko);
    }
    public function tindaklanjut_per_lhp_data(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            $statusrekomendasi = implode(',', $statusrekomendasi);
            foreach (explode(',', $statusrekomendasi) as $v ) {
                $arrayStatusRekom[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }

        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayStatusRekom)>0&& !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    

        $all=$alldata->get();
        $titlePemeriksa = '';
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
            $titlePemeriksa.='Semua';
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
            foreach($npemeriksa as $k=>$v){
                $titlePemeriksa.=$v->pemeriksa;
            }
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        
        return view('backend.pages.laporan.tindak-lanjut-per-lhp.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('alldata',$alldata)
                    ->with('tindaklanjut',$tindaklanjut)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('titlePemeriksa',$titlePemeriksa);
    }
    public function tindaklanjut_per_lhp_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            foreach (explode(',', $statusrekomendasi) as $v ) {
                $arrayStatusRekom[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }

        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayStatusRekom)>0&& !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayLHP)>0&& !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    

        $all=$alldata->get();
        $titlePemeriksa = '';
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
            $titlePemeriksa.='Semua';
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
            foreach($npemeriksa as $k=>$v){
                $titlePemeriksa.=$v->pemeriksa;
            }
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
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
        $data['no_lhp']=$arrayLHP;
        $data['rekomendasi']=$rekomendasi;
        $data['titlePemeriksa']=$titlePemeriksa;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-lhp.cetakpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->download('laporan-tindaklanjut-per-lhp.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.tindak-lanjut-per-lhp.cetakpdf', $data), 'laporan-tindaklanjut-per-lhp.xlsx');
        }
    }
    //-------------------------
    public function tindaklanjut_per_bidang()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        
        return view('backend.pages.laporan.tindak-lanjut-per-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('levelresiko', $levelresiko);
    }

    public function tindaklanjut_per_bidang_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $statusrekomendasi = $request->statusrekomendasi;
        $overdue = $request->overdue;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            $statusrekomendasi = implode(',', $statusrekomendasi);
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata=$alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp = implode(',',$arrayLHP);
        }                   
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            foreach($arrayBidang as $kb=>$vb)
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
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        
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
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('bidangTitle', $bidangTitle);
    
    }

    public function tindaklanjut_per_bidang_pimpinan(Request $request)
    {
        $title_form = '';
        $bidang_filter = $pemeriksa_filter = 'Total';
        $category_filter = 'Semua';
        $tahun = '2020';
        $statusoverdue = '';
        $rekomstatus = '';
        if($request->has('tahun')){
            $tahun = $request->query('tahun');
        }
        if($request->has('category')){
            $category_filter = $request->query('category');
        }
        if($request->has('bidang')){
            $bidang_filter = $request->query('bidang');
        }
        if($request->has('title')){
            $title_form = $request->query('title');
        }
        if($request->has('pemeriksa')){
            $pemeriksa_filter = $request->query('pemeriksa');
        }
        if(Auth::user()->level=='pimpinan-kepala-bidang'){
            list($idbidang,$category,$namabidang)=explode('__',Auth::user()->bidang);
            $bidang_filter = $namabidang;
        }
        if($request->has('overduestatus')){
            $statusoverdue = $request->query('overduestatus');
        }
        if($request->has('rekomstatus')){
            $rekomstatus = $request->query('rekomstatus');
        }
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        
        return view('backend.pages.laporan.pimpinan.tindak-lanjut-per-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('levelresiko', $levelresiko)
                ->with('bidang_filter', $bidang_filter)
                ->with('category_filter', $category_filter)
                ->with('tahun', $tahun)
                ->with('title_form', $title_form)
                ->with('pemeriksa_filter', $pemeriksa_filter)
                ->with('overduestatus', $statusoverdue)
                ->with('rekomstatus',$rekomstatus);
    }

    public function tindaklanjut_per_bidang_pimpinan_data(Request $request)
    {
        $now=date('Y-m-d');
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $statusrekomendasi = $request->statusrekomendasi;
        $overdue = $request->overdue;
        $rekomstatus = $request->rekomstatus;
        $overduestatus = $request->overduestatus;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            $statusrekomendasi = implode(',', $statusrekomendasi);
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        if(Auth::user()->level=='pimpinan-kepala-bidang'){
            list($idbidang,$category,$namabidang)=explode('__',Auth::user()->bidang);
            if($category == 'Bidang'){
                $bidang = Bidang::where('id',$idbidang)->first();
                $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                        ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                        ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                        ->leftjoin('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')

                        ->leftjoin('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                        ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.fakultas')
                        ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')

                        ->where('bidang.id', $bidang->id)
                        ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                        ->whereNull('data_rekomendasi.deleted_at');
                        // ->where('daftar_lhp.status_lhp','Publish LHP');
                        if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                            $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                        }
                        if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                            $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                        }
            }else{
                $bidang = LevelPIC::where('id',$idbidang)->first();
                $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                        ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                        ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                        ->leftjoin('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')

                        ->leftjoin('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                        ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.fakultas')
                        ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')

                        ->where('level_pic.id', $bidang->id)
                        ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                        ->whereNull('data_rekomendasi.deleted_at');
                        // ->where('daftar_lhp.status_lhp','Publish LHP');
                        if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                            $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                        }
                        if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                            $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                        }
            }
        }else{
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                    
                    ->leftjoin('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                    ->leftjoin('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                    ->leftjoin('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')

                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                    ->whereNull('data_rekomendasi.deleted_at');
                    if($rekomstatus==''){
                        $alldata = $alldata->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id');
                    }else{
                        $alldata = $alldata->leftjoin('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id');
                    }
                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                    }
                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                    }
        }
        
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata=$alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp = implode(',',$arrayLHP);
        }                   
        $dbid='';
        $arraybid=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            foreach($arrayBidang as $kb=>$vb)
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
                $pics=PICUnit::whereIn('bidang',$arrayBidang)->get();
                $pics_id = array();
                foreach($pics as $pvc){
                    $pics_id[] = $pvc->id;
                }
                // $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid)
                //     ->orWhereIn('data_rekomendasi.pic_2_temuan_id',$arraybid);
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pics_id)
                    ->orWhereIn('data_rekomendasi.pic_2_temuan_id',$pics_id);
            }
        }
        $all=$alldata->get();
        // return json_encode($all);
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=$rekomsementara=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        
        if($rekomstatus!=''){
            foreach($rekomendasi as $k=>$v){
                if($rekomstatus=='Create oleh Unit Kerja' && !isset($tindaklanjut[$v->id_rekom])){
                    array_push($rekomsementara,$v);
                }elseif($rekomstatus=='Belum direview SPI' && isset($tindaklanjut[$v->id_rekom]) && $v->review_spi =='' && $v->published!=1){
                    array_push($rekomsementara,$v);
                }elseif($rekomstatus=='Sedang direview SPI' && isset($tindaklanjut[$v->id_rekom]) && $v->review_spi !='' && $v->published==0){
                    array_push($rekomsementara,$v);
                }elseif($rekomstatus=='Sudah direview SPI' && isset($tindaklanjut[$v->id_rekom]) && $v->review_spi =='' && $v->published==0){
                    array_push($rekomsementara,$v);
                }elseif($rekomstatus=='Sudah dipublish oleh SPI' && isset($tindaklanjut[$v->id_rekom]) && $v->review_spi !='' && $v->published==1){
                    array_push($rekomsementara,$v);
                }   
            }
            $rekomendasi = $rekomsementara;
        }

        if($overduestatus!=''){
            foreach($rekomendasi as $k=>$v){
                if($v->status_rekomendasi_id == 2){
                    if($now > $v->tanggal_penyelesaian){
                        if($overduestatus == 'Overdue 1 - Rendah' && $v->level_resiko_id == 2){
                            array_push($rekomsementara,$v);
                        }elseif($overduestatus == 'Overdue 2 - Menengah' && $v->level_resiko_id == 3){
                            array_push($rekomsementara,$v);
                        }elseif($overduestatus == 'Overdue 3 - Tinggi' && $v->level_resiko_id == 4){
                            array_push($rekomsementara,$v);
                        }
                    }
                }
            }
            $rekomendasi = $rekomsementara;
        }

        return view('backend.pages.laporan.pimpinan.tindak-lanjut-per-bidang.data')
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
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('bidangTitle', $bidangTitle);
    
    }

    public function tindaklanjut_per_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $statusrekomendasi = $request->statusrekomendasi;
        $overdue = $request->overdue;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            // $nbidang=Bidang::all();
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata=$alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp = implode(',',$arrayLHP);
        }                   
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            foreach($arrayBidang as $kb=>$vb)
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
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
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
        $data['no_lhp']=$arrayLHP;
        $data['rekomendasi']=$rekomendasi;
        $data['bidangTitle']=$bidangTitle;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-bidang.cetakpdf', $data)->setPaper('legal', 'landscape');
            return $pdf->download('laporan-tindaklanjut-per-bidang.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.tindak-lanjut-per-bidang.cetakpdf', $data), 'laporan-tindaklanjut-per-bidang.xlsx');
        }
    }
    //-------------------------
    public function tindaklanjut_per_unitkerja()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $picunit=PICUnit::all();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$picunit)
                ->with('pejabat',$pejabat)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('levelresiko',$levelresiko);
    }
    public function tindaklanjut_per_unitkerja_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;

        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            $statusrekomendasi = implode(',', $statusrekomendasi);
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unit_kerja2 != '' && $request->unit_kerja2!=0){
            $unit_kerja2 = $request->unit_kerja2;
            $unit_kerja2 = implode(',', $unit_kerja2);
            foreach(explode(',',$unit_kerja2) as $v){
                $arrayUnitKerja2[] = $v;
            }
        }
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');

        $unit_kerja1=$unit_kerja2='';
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arrayUnitKerja1);
            $unit_kerja1 = implode(',',$request->unit_kerja1);
        }                      
        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2))
        {
            $unit_kerja2 = implode(',',$arrayUnitKerja2);
        }                      
        
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$request->no_lhp);
            $no_lhp = implode(',',$request->no_lhp);
        }                      
        $dbid='';
        $arraybid=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            foreach($arrayBidang as $kb=>$vb)
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
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }

        $selectedPic = '';
        if(count($arrayUnitKerja1)==0 || in_array(0, $arrayUnitKerja1)){
            $selectedPic .= 'Semua';
        }else{
            $picQuery = PICUnit::whereIn('id', $arrayUnitKerja1)->get();
            foreach($picQuery as $k=>$v){
                $selectedPic.=$v->nama_pic.', ';
            }
        }
        // return json_encode($rekomendasi);
        return view('backend.pages.laporan.tindak-lanjut-per-unitkerja.data')
                    ->with('unit_kerja1',$arrayUnitKerja1)
                    ->with('unit_kerja2',$arrayUnitKerja2)
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
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('selectedPic', $selectedPic);
    }
    public function tindaklanjut_per_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach (explode(',', $pemeriksa) as $v ) {
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach (explode(',', $no_lhp) as $v ) {
                $arrayLHP[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach (explode(',', $level_resiko) as $v ) {
                $arrayLevelResiko[] = $v;
            }
        }
        
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach (explode(',', $bidang) as $v ) {
                $arrayBidang[] = $v;
            }
        }
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unit_kerja2 != '' && $request->unit_kerja2!=0){
            $unit_kerja2 = $request->unit_kerja2;
            foreach(explode(',',$unit_kerja2) as $v){
                $arrayUnitKerja2[] = $v;
            }
        }
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $picunit=PICUnit::all();
        $unit=array();
        $pic_unit=$bidunit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v;
            $bidunit[$v->bidang][$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom,status_rekomendasi.rekomendasi as st_rekom,data_rekomendasi.rekomendasi as rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('status_rekomendasi','status_rekomendasi.id','=','data_rekomendasi.status_rekomendasi_id')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');

        $unit_kerja1=$unit_kerja2='';
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arrayUnitKerja1);
            // $unit_kerja1 = implode(',',$request->unit_kerja1);
            $unit_kerja1 = $arrayUnitKerja1;
        }                      
        if(count($arrayUnitKerja2)>0 && !in_array(0, $arrayUnitKerja2))
        {
            $unit_kerja2 = $arrayUnitKerja2;
            // $unit_kerja2 = implode(',',$arrayUnitKerja2);
        }                      
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp = implode(',',$request->no_lhp);
            $no_lhp = $arrayLHP;
        }        
        
        $dbid='';
        $arraybid=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            foreach($arrayBidang as $kb=>$vb)
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
                $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arraybid);
            }
        }
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }

        $selectedPic = '';
        if(count($arrayUnitKerja1)==0 || in_array(0, $arrayUnitKerja1)){
            $selectedPic .= 'Semua';
        }else{
            $picQuery = PICUnit::whereIn('id', $arrayUnitKerja1)->get();
            foreach($picQuery as $k=>$v){
                $selectedPic.=$v->nama_pic.', ';
            }
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
        $data['no_lhp']=$arrayLHP;
        $data['rekomendasi']=$rekomendasi;
        $data['unit_kerja1']=$arrayUnitKerja1;
        $data['unit_kerja2']=$arrayUnitKerja2;
        $data['selectedPic']=$selectedPic;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut-per-unitkerja.cetakpdf', $data)->setPaper('legal', 'landscape');
            return $pdf->download('laporan-tindaklanjut-per-unitkerja.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.tindak-lanjut-per-unitkerja.cetakpdf', $data), 'laporan-tindaklanjut-per-unitkerja.xlsx');
        }
    }
    //-------------------------
    public function tindak_lanjut()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $pejabat=PejabatTandaTangan::all();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $picunit=PICUnit::all();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        return view('backend.pages.laporan.tindak-lanjut.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$picunit)
                ->with('pejabat',$pejabat)
                ->with('levelresiko',$levelresiko)
                ->with('statusrekomendasi',$statusrekomendasi);
    }
    public function tindak_lanjut_data(Request $request)
    {
        // return $request->all();
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            $statusrekomendasi = implode(',', $statusrekomendasi);
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unit_kerja2 != '' && $request->unit_kerja2!=0){
            $unit_kerja2 = $request->unit_kerja2;
            $unit_kerja2 = implode(',', $unit_kerja2);
            foreach(explode(',',$unit_kerja2) as $v){
                $arrayUnitKerja2[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }

        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);

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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');

                
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        // $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata=$alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja2%,");
        }
        $all=$alldata->get();
        // $npemeriksa=Pemeriksa::find($pemeriksa);
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
        }

        $tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return json_encode($rekomendasi);
        return view('backend.pages.laporan.tindak-lanjut.data')
                    ->with('unit_kerja1',$arrayUnitKerja1)
                    ->with('unit_kerja2',$arrayUnitKerja2)
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
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->statusrekomendasi != '' && $request->statusrekomendasi!=0){
            $statusrekomendasi = $request->statusrekomendasi;
            foreach(explode(',',$statusrekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayUnitKerja2 = array();
        if($request->unit_kerja2 != '' && $request->unit_kerja2!=0){
            $unit_kerja2 = $request->unit_kerja2;
            foreach(explode(',',$unit_kerja2) as $v){
                $arrayUnitKerja2[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }

        $statusrekomendasi = $request->statusrekomendasi;
        $pejabat=PejabatTandaTangan::find($request->pejabat);

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
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');

                
        $dbid='';
        $arraybid=array();
        // return $request->bidang;
        // $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata=$alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja2%,");
        }
        $all=$alldata->get();
        // $npemeriksa=Pemeriksa::find($pemeriksa);
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        $now=date('Y-m-d');
        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($all as $k=>$v)
        {
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                }
            }
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
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.tindak-lanjut.cetakpdf', $data)->setPaper('legal', 'landscape');
            return $pdf->download('matriks-tindaklanjut.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.tindak-lanjut.cetakpdf', $data), 'matriks-tindaklanjut.xlsx');
        }
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
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $statusrekom=StatusRekomendasi::all();
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::where('bidang',$arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    if(count($arrayLHP)>0 && !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    


        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
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
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
        }
        // return json_encode($rekomendasi);
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
                    // ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
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
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        
        $statusrekom=StatusRekomendasi::all();
        $bidangTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::where('bidang',$arrayBidang)->get();
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang.' ';
            }
        }

        $unit=array();
        $pic_unit=array();
        foreach($picunit as $k=>$v)
        {
            $pic_unit[$v->id]=$v->id;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1);
                                    }
                                    if(count($arrayLHP)>0 && !in_array(0, $arrayLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.id', $arrayLHP);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    


        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang))
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
            if($overdue==2){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
            }elseif($overdue==0){
                if($v->tanggal_penyelesaian <= $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }elseif($overdue==1){
                if($v->tanggal_penyelesaian > $now){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }
            }
        }

        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-status-penyelesaian-rekomendasi.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.status-penyelesaian-rekomendasi.cetakpdf', $data), 'laporan-status-penyelesaian-rekomendasi.xlsx');
        }
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

        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                      
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp=implode(',',$request->no_lhp);
        }
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arrayUnitKerja1);
            $unitkerja1=implode(',',$request->unit_kerja1);
        }

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    if($overdue==2){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }elseif($overdue==0){
                        if($v->tanggal_penyelesaian <= $now){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }
                    }elseif($overdue==1){
                        if($v->tanggal_penyelesaian > $now){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }
                    }
                }
            }
            else
            {
                if($overdue==2){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }elseif($overdue==0){
                    if($v->tanggal_penyelesaian <= $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }elseif($overdue==1){
                    if($v->tanggal_penyelesaian > $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
        }
        $statusrekom=StatusRekomendasi::all();
        // return json_encode($bidang);
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    // ->with('no_lhp',$arr)
                    ->with('unitkerja1',$arrayUnitKerja1)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    // ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('pemeriksaTitle',$pemeriksaTitle);
    }
     public function status_penyelesaian_rekomendasi_pemeriksa_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                      
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp=implode(',',$request->no_lhp);
        }
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$arrayUnitKerja1);
            $unitkerja1=implode(',',$request->unit_kerja1);
        }

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
            // return $pic_unit;
        }
        
        $all=$alldata->get();
        $lhp=$temuan=$rekomendasi=$jlh_by_status=array();
        foreach($all as $k=>$v)
        {
            if($v->pic_2_temuan_id!='' && $v->pic_2_temuan_id!=',')
            {
                $picunit2=explode(',',$v->pic_2_temuan_id);
                if(array_intersect($picunit2,$pic_unit))
                {
                    if($overdue==2){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }elseif($overdue==0){
                        if($v->tanggal_penyelesaian <= $now){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }
                    }elseif($overdue==1){
                        if($v->tanggal_penyelesaian > $now){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }
                    }
                }
            }
            else
            {
                if($overdue==2){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }elseif($overdue==0){
                    if($v->tanggal_penyelesaian <= $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }elseif($overdue==1){
                    if($v->tanggal_penyelesaian > $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
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
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['pemeriksaTitle']=$pemeriksaTitle;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-status-penyelesaian-rekomendasi-pemeriksa.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.status-penyelesaian-rekomendasi-pemeriksa.cetakpdf', $data), 'laporan-status-penyelesaian-rekomendasi-pemeriksa.xlsx');
        }
    }
    //-------------------------
    public function status_penyelesaian_rekomendasi_bidang()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        // $bidang=Bidang::orderBy('nama_bidang')->get();
        $pejabat=PejabatTandaTangan::all();
        $unitkerja=PICUnit::all();
        // $lvresiko=LevelPIC::all();
        
        $bidang = Bidang::where('flag',1)->get();
        $level_pic = LevelPIC::all();
        $bidangArray = array();
        foreach($bidang as $k=>$v){
            $bidangArray[] = [
                'id' => $v->id,
                'category' => 'Bidang',
                'name' => $v->nama_bidang
            ];
        }

        foreach($level_pic as $r=>$s){
            if($s->id != 1 && $s->flag == 1){
                $bidangArray[] = [
                    'id' => $s->id,
                    'category' => 'Level',
                    'name' => $s->nama_level
                ];
            }
        }
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidangArray)
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
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp=implode(',',$request->no_lhp);
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $lvresiko=LevelPIC::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            $lvresiko=LevelPIC::whereIn('id', $arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        $bidangArray = array();
        foreach($nbidang as $k=>$v){
            $bidangArray[] = [
                'id' => $v->id,
                'category' => 'Bidang',
                'name' => $v->nama_bidang
            ];
        }

        foreach($lvresiko as $r=>$s){
            if($s->id != 1 && $s->flag == 1){
                $bidangArray[] = [
                    'id' => $s->id,
                    'category' => 'Level',
                    'name' => $s->nama_level
                ];
            }
        }
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
        
        $lhp=$temuan=$rekomendasi=$jlh_by_status=$jlh_by_bidang=array();
        
        foreach($all as $k=>$v)
        {
            if($v->bidang!=''){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;

                $jlh_by_bidang[$v->id_lhp][$v->bidang][]=$v->bidang;
            }else{
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;

                $jlh_by_bidang[$v->id_lhp][$v->level_pic][]=$v->level_pic;
            }
        }
        $statusrekom=StatusRekomendasi::all();

        $finalData = array();
        foreach($bidangArray as $idx=>$bd){
            $finalData[$idx]['name'] = $bd['name'];
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd['id'] == $v->bidang && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }elseif($bd['id'] == $v->level_pic && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic){
                        $jmlh++;
                    }
                    if(($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        // return json_encode($finalData);
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.data')
                ->with('pic_unit',$pic_unit)
                ->with('dbidang',$dbidang)
                ->with('alldata',$alldata)
                ->with('bidangArray',$bidangArray)
                ->with('unitkerja1',$arrayUnitKerja1)
                ->with('npemeriksa',$npemeriksa)
                ->with('request',$request)
                ->with('statusrekom',$statusrekom)
                ->with('finalData', $finalData)
                ->with('lhp',$lhp)
                ->with('jlh_by_status',$jlh_by_status)
                ->with('jlh_by_bidang',$jlh_by_bidang)
                ->with('unit',$unit)
                ->with('bidang',$bidang)
                ->with('tgl_awal',$tgl_awal)
                ->with('tgl_akhir',$tgl_akhir)
                ->with('temuan',$temuan)
                ->with('no_lhp',$arrayLHP)
                ->with('tampilkannilai',$tampilkannilai)
                ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksaTitle', $pemeriksaTitle);
    }
    public function status_penyelesaian_rekomendasi_bidang_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        
        $dbidang=$unitkerja1='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            $no_lhp=implode(',',$request->no_lhp);
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $lvresiko=LevelPIC::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            $lvresiko=LevelPIC::whereIn('id', $arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        $bidangArray = array();
        foreach($nbidang as $k=>$v){
            $bidangArray[] = [
                'id' => $v->id,
                'category' => 'Bidang',
                'name' => $v->nama_bidang
            ];
        }

        foreach($lvresiko as $r=>$s){
            if($s->id != 1 && $s->flag == 1){
                $bidangArray[] = [
                    'id' => $s->id,
                    'category' => 'Level',
                    'name' => $s->nama_level
                ];
            }
        }
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
        
        $lhp=$temuan=$rekomendasi=$jlh_by_status=$jlh_by_bidang=array();
        
        foreach($all as $k=>$v)
        {
            if($v->bidang!=''){
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;

                $jlh_by_bidang[$v->id_lhp][$v->bidang][]=$v->bidang;
            }else{
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                $rekomendasi[$v->id_rekom]=$v;
                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;

                $jlh_by_bidang[$v->id_lhp][$v->level_pic][]=$v->level_pic;
            }
        }
        $statusrekom=StatusRekomendasi::all();

        $finalData = array();
        foreach($bidangArray as $idx=>$bd){
            $finalData[$idx]['name'] = $bd['name'];
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd['id'] == $v->bidang && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }elseif($bd['id'] == $v->level_pic && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic){
                        $jmlh++;
                    }
                    if(($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd['id'] == $v->bidang || $bd['id'] == $v->level_pic) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['nbidang']=$nbidang;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['finalData']=$finalData;
        $data['pemeriksaTitle']=$pemeriksaTitle;

        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-status-penyelesaian-rekomendasi-bidang.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.status-penyelesaian-rekomendasi-bidang.cetakpdf', $data), 'laporan-status-penyelesaian-rekomendasi-bidang.xlsx');
        }
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
        
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$arrayUnitKerja1;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        $bidangTitle = '';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang;
            }
        }
        $unitTitle='';
        if(count($arrayUnitKerja1)==0 || in_array(0, $arrayUnitKerja1)){
            $dunitkerja=PICUnit::all();
            $unitTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            foreach($dunitkerja as $k=>$v){
                $unitTitle.=$v->nama_pic;
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
                        if($overdue==2){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }elseif($overdue==0){
                            if($v->tanggal_penyelesaian <= $now){
                                $lhp[$v->id_lhp]=$v;
                                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                                $rekomendasi[$v->id_rekom]=$v;
                                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                            }
                        }elseif($overdue==1){
                            if($v->tanggal_penyelesaian > $now){
                                $lhp[$v->id_lhp]=$v;
                                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                                $rekomendasi[$v->id_rekom]=$v;
                                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                            }
                        }
                    }
                }
            }
            else
            {
                if($overdue==2){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }elseif($overdue==0){
                    if($v->tanggal_penyelesaian <= $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }elseif($overdue==1){
                    if($v->tanggal_penyelesaian > $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
        }
        $statusrekom=StatusRekomendasi::all();
        $tahunPeriode = DaftarTemuan::select('tahun_pemeriksa as tahun')
                        ->whereNull('deleted_at')
                        ->groupBy('tahun_pemeriksa')
                        ->orderBy('tahun_pemeriksa', 'desc')
                        ->get();
                        
        $finalData = array();
        foreach($tahunPeriode as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->tahun;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($overdue==2){
                        if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                            $totalData++;
                        }
                        if($bd->tahun == $v->tahun_pemeriksa){
                            $jmlh++;
                        }
                        if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                            $selesai++;
                        }
                        if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                            $tdd++;
                        }
                    }elseif($overdue==0){
                        if($v->tanggal_penyelesaian <= $now){
                            if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                                $totalData++;
                            }
                            if($bd->tahun == $v->tahun_pemeriksa){
                                $jmlh++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                                $selesai++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                                $tdd++;
                            }
                        }
                    }elseif($overdue==1){
                        if($v->tanggal_penyelesaian > $now){
                            if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                                $totalData++;
                            }
                            if($bd->tahun == $v->tahun_pemeriksa){
                                $jmlh++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                                $selesai++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                                $tdd++;
                            }
                        }
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.data')
                    ->with('finalData', $finalData)
                    ->with('nbidang',$nbidang)
                    ->with('pic_unit',$pic_unit)
                    ->with('dunitkerja',$dunitkerja)
                    ->with('unitkerja',$unitkerja)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('pemeriksaTitle',$pemeriksaTitle)
                    ->with('bidangTitle',$bidangTitle)
                    ->with('unitTitle',$unitTitle);
    }
    public function status_penyelesaian_rekomendasi_tahun_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$arrayUnitKerja1;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        $bidangTitle = '';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
            $bidangTitle.='Semua';
        }else{
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
            foreach($nbidang as $k=>$v){
                $bidangTitle.=$v->nama_bidang;
            }
        }
        $unitTitle='';
        if(count($arrayUnitKerja1)==0 || in_array(0, $arrayUnitKerja1)){
            $dunitkerja=PICUnit::all();
            $unitTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            foreach($dunitkerja as $k=>$v){
                $unitTitle.=$v->nama_pic;
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
                        if($overdue==2){
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_lhp][$v->id_temuan]=$v;
                            $rekomendasi[$v->id_rekom]=$v;
                            $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                        }elseif($overdue==0){
                            if($v->tanggal_penyelesaian <= $now){
                                $lhp[$v->id_lhp]=$v;
                                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                                $rekomendasi[$v->id_rekom]=$v;
                                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                            }
                        }elseif($overdue==1){
                            if($v->tanggal_penyelesaian > $now){
                                $lhp[$v->id_lhp]=$v;
                                $temuan[$v->id_lhp][$v->id_temuan]=$v;
                                $rekomendasi[$v->id_rekom]=$v;
                                $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                            }
                        }
                    }
                }
            }
            else
            {
                if($overdue==2){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_lhp][$v->id_temuan]=$v;
                    $rekomendasi[$v->id_rekom]=$v;
                    $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                }elseif($overdue==0){
                    if($v->tanggal_penyelesaian <= $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }elseif($overdue==1){
                    if($v->tanggal_penyelesaian > $now){
                        $lhp[$v->id_lhp]=$v;
                        $temuan[$v->id_lhp][$v->id_temuan]=$v;
                        $rekomendasi[$v->id_rekom]=$v;
                        $jlh_by_status[$v->id_lhp][$v->status_rekomendasi_id][]=$v->status_rekomendasi_id;
                    }
                }
            }
        }
        $statusrekom=StatusRekomendasi::all();
        $tahunPeriode = DaftarTemuan::select('tahun_pemeriksa as tahun')
                        ->whereNull('deleted_at')
                        ->groupBy('tahun_pemeriksa')
                        ->orderBy('tahun_pemeriksa', 'desc')
                        ->get();
                        
        $finalData = array();
        foreach($tahunPeriode as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->tahun;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($overdue==2){
                        if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                            $totalData++;
                        }
                        if($bd->tahun == $v->tahun_pemeriksa){
                            $jmlh++;
                        }
                        if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                            $selesai++;
                        }
                        if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                            $tdd++;
                        }
                    }elseif($overdue==0){
                        if($v->tanggal_penyelesaian <= $now){
                            if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                                $totalData++;
                            }
                            if($bd->tahun == $v->tahun_pemeriksa){
                                $jmlh++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                                $selesai++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                                $tdd++;
                            }
                        }
                    }elseif($overdue==1){
                        if($v->tanggal_penyelesaian > $now){
                            if($bd->tahun == $v->tahun_pemeriksa && $r->id == $v->status_rekomendasi_id){
                                $totalData++;
                            }
                            if($bd->tahun == $v->tahun_pemeriksa){
                                $jmlh++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==1){
                                $selesai++;
                            }
                            if(($bd->tahun == $v->tahun_pemeriksa) && $v->status_rekomendasi_id==4){
                                $tdd++;
                            }
                        }
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        $data['finalData']=$finalData;
        $data['dunitkerja']=$dunitkerja;
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['pemeriksaTitle']=$pemeriksaTitle;
        $data['bidangTitle']=$bidangTitle;
        $data['unitTitle']=$unitTitle;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-status-penyelesaian-rekomendasi-tahun.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.status-penyelesaian-rekomendasi-tahun.cetakpdf', $data), 'laporan-status-penyelesaian-rekomendasi-tahun.xlsx');
        }
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
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        // $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $dunitkerja=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
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

        $finalData = array();
        foreach($dunitkerja as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->nama_pic;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd->id == $v->pic_temuan_id && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd->id == $v->pic_temuan_id){
                        $jmlh++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        // return json_encode($finalData);
        return view('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.data')
                    ->with('nbidang',$nbidang)
                    ->with('pic_unit',$pic_unit)
                    ->with('dunitkerja',$dunitkerja)
                    ->with('unitkerja',$arrayUnitKerja1)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('finalData',$finalData)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('tampilkannilai',$tampilkannilai)
                    ->with('tampilkanwaktupenyelesaian',$tampilkanwaktupenyelesaian)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('pemeriksaTitle',$pemeriksaTitle);
    }
    public function status_penyelesaian_rekomendasi_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $overdue = $request->overdue;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        $tampilkannilai = $request->tampilkannilai;
        $tampilkanwaktupenyelesaian = $request->tampilkanwaktupenyelesaian;
        $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
                                    
        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        // $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       

        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $dunitkerja=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
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

        $finalData = array();
        foreach($dunitkerja as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->nama_pic;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd->id == $v->pic_temuan_id && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd->id == $v->pic_temuan_id){
                        $jmlh++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }

        $data['finalData']=$finalData;
        $data['dunitkerja']=$dunitkerja;
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['tampilkannilai']=$tampilkannilai;
        $data['tampilkanwaktupenyelesaian']=$tampilkanwaktupenyelesaian;
        $data['rekomendasi']=$rekomendasi;
        $data['pemeriksaTitle']=$pemeriksaTitle;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-status-penyelesaian-rekomendasi-unitkerja.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.status-penyelesaian-rekomendasi-unitkerja.cetakpdf', $data), 'laporan-status-penyelesaian-rekomendasi-unitkerja.xlsx');
        }
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
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
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
                                    
        if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa))
        {
            $alldata->whereIn('daftar_lhp.pemeriksa_id',$arrayPemeriksa);
            // if($request->pemeriksa!=0)
            //     $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$arrayUnitKerja1;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");

        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            $dbidang=$arrayBidang;
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
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

        // return json_encode($bidang);
        return view('backend.pages.laporan.rekomendasi-overdue-unitkerja.data')
                    ->with('pic_unit',$pic_unit)
                    ->with('nbid',$nbid)
                    ->with('dp',$dp)
                    ->with('unitkerja',$arrayUnitKerja1)
                    ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    // ->with('no_lhp',$no_lhp)
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    // ->with('pejabat',$pejabat)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi);
    }
    public function rekomendasi_overdue_unitkerja_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
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
                                    
        if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa))
        {
            $alldata->whereIn('daftar_lhp.pemeriksa_id',$arrayPemeriksa);
            // if($request->pemeriksa!=0)
            //     $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
            // $no_lhp=implode(',',$request->no_lhp);
        }
        
        $unitkerja=$arrayUnitKerja1;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");

        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            $dbidang=$arrayBidang;
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
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
        
        $data['nbid']=$nbid;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        // $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        // $data['pejabat']=$pejabat;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['rekomendasi']=$rekomendasi;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.rekomendasi-overdue-unitkerja.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-rekomendasi-overdue-unitkerja.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.rekomendasi-overdue-unitkerja.cetakpdf', $data), 'laporan-rekomendasi-overdue-unitkerja.xlsx');
        }
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
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
       
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
                                    
        if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa))
        {
            $alldata->whereIn('daftar_lhp.pemeriksa_id',$arrayPemeriksa);
        }

        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
        }
        
        $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            $dbidang=$arrayBidang;
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
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
                    ->with('request',$request)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('temuan',$temuan)
                    ->with('no_lhp',$arrayLHP)
                    ->with('rekomendasi',$rekomendasi);
    }
    
    public function laporan_rekomendasi_overdue_pdf(Request $request)
    {
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayLHP[] = $v;
            }
        }

        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        $dbidang=$unitkerja='';
        // $pejabat=PejabatTandaTangan::find($request->pejabat);
        // $nbidang=Bidang::find($bidang);
       
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
                                    
        if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa))
        {
            $alldata->whereIn('daftar_lhp.pemeriksa_id',$arrayPemeriksa);
        }

        if(count($arrayLHP)>0 && !in_array(0, $arrayLHP))
        {
            $alldata->whereIn('daftar_lhp.id',$arrayLHP);
        }
        
        $unitkerja=$request->unitkerja;
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->where('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       

        $all=$alldata->get();
        $unit=array();
        $pic_unit=array();
        if(count($arrayBidang)>0 && !in_array(0, $arrayBidang))
        {
            $dbidang=$arrayBidang;
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            $nbidang=Bidang::whereIn('id',$arrayBidang)->get();
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
        $statusrekom=StatusRekomendasi::all();
        // return $lhp;
        $dunitkerja=PICUnit::find($unitkerja);
        // $data['dunitkerja']=$dunitkerja;
        // $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        // $data['npemeriksa']=$npemeriksa;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['temuan']=$temuan;
        $data['no_lhp']=$arrayLHP;
        $data['statusrekom']=$statusrekom;
        $data['rekomendasi']=$rekomendasi;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.laporan-rekomendasi-overdue.cetakpdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('laporan-rekomendasi-overdue.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.laporan-rekomendasi-overdue.cetakpdf', $data), 'laporan-rekomendasi-overdue.xlsx');
        }
    }

    public function rekap_status_sesuai_tahun()
    {
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $bidang=Bidang::orderBy('nama_bidang')->get();
        $unitkerja=PICUnit::all();
        $year = DaftarTemuan::select('tahun_pemeriksa as tahun')
                        ->whereNull('deleted_at')
                        ->groupBy('tahun_pemeriksa')
                        ->orderBy('tahun_pemeriksa', 'desc')
                        ->get();
        return view('backend.pages.laporan.rekap-status-sesuai-tahun.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('bidang',$bidang)
                ->with('unitkerja',$unitkerja)
                ->with('year',$year);
    }

    public function rekap_status_sesuai_tahun_data(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $lhp_from_year = $request->lhp_from_year;
        $lhp_to_year = $request->lhp_to_year;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            $bidang = implode(',', $bidang);
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if($lhp_from_year!='0' && $lhp_to_year!='0'){
                                        $alldata = $alldata->whereBetween('daftar_lhp.tahun_pemeriksa', [$lhp_from_year, $lhp_to_year]);
                                    }elseif($lhp_from_year!='0'){
                                        $alldata = $alldata->where('daftar_lhp.tahun_pemeriksa', $lhp_from_year);
                                    }elseif($lhp_to_year!='0'){
                                        $alldata = $alldata->where('daftar_lhp.tahun_pemeriksa', $lhp_to_year);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
        
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $dunitkerja=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
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
        $statusrekom=StatusRekomendasi::all();

        $finalData = array();
        foreach($dunitkerja as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->nama_pic;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd->id == $v->pic_temuan_id && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd->id == $v->pic_temuan_id){
                        $jmlh++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        // return json_encode($dbidang);
        return view('backend.pages.laporan.rekap-status-sesuai-tahun.data')
                    ->with('nbidang',$nbidang)
                    ->with('pic_unit',$pic_unit)
                    ->with('dunitkerja',$dunitkerja)
                    ->with('unitkerja',$arrayUnitKerja1)
                    // ->with('dbidang',$dbidang)
                    ->with('alldata',$alldata)
                    ->with('npemeriksa',$npemeriksa)
                    ->with('request',$request)
                    ->with('statusrekom',$statusrekom)
                    ->with('lhp',$lhp)
                    ->with('jlh_by_status',$jlh_by_status)
                    ->with('unit',$unit)
                    ->with('bidang',$bidang)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('finalData',$finalData)
                    ->with('temuan',$temuan)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('pemeriksaTitle',$pemeriksaTitle);
    }

    public function rekap_status_sesuai_tahun_pdf(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $lhp_from_year = $request->lhp_from_year;
        $lhp_to_year = $request->lhp_to_year;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayBidang = array();
        if($request->bidang != '' && $request->bidang!=0){
            $bidang = $request->bidang;
            foreach(explode(',',$bidang) as $v){
                $arrayBidang[] = $v;
            }
        }
        
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if($lhp_from_year!='0' && $lhp_to_year!='0'){
                                        $alldata = $alldata->whereBetween('daftar_lhp.tahun_pemeriksa', [$lhp_from_year, $lhp_to_year]);
                                    }elseif($lhp_from_year!='0'){
                                        $alldata = $alldata->where('daftar_lhp.tahun_pemeriksa', $lhp_from_year);
                                    }elseif($lhp_to_year!='0'){
                                        $alldata = $alldata->where('daftar_lhp.tahun_pemeriksa', $lhp_to_year);
                                    }
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at');
        
        if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1))
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
            ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
        }
       
        $all=$alldata->get();
        if(count($arrayPemeriksa)==0 || in_array(0, $arrayPemeriksa)){
            $npemeriksa=Pemeriksa::all();
        }else{
            $npemeriksa=Pemeriksa::whereIn('id', $arrayPemeriksa)->get();
        }
        $unit=array();
        $pic_unit=array();
        $pemeriksaTitle='';
        if(count($arrayBidang)==0 || in_array(0, $arrayBidang)){
            $nbidang=Bidang::all();
            $picunit=PICUnit::all();
            $dunitkerja=PICUnit::all();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
            }
            $pemeriksaTitle.='Semua';
        }else{
            $dunitkerja=PICUnit::whereIn($arrayUnitKerja1)->get();
            $nbidang=Bidang::whereIn('id', $arrayBidang)->get();
            $picunit=PICUnit::whereIn('bidang',$arrayBidang)->get();
            foreach($picunit as $k=>$v)
            {
                $pic_unit[$v->id]=$v->id;
                $pemeriksaTitle.=$v->nama_pic;
            }
        }
        
        if(count($pic_unit)!=0)
        {
            $alldata->whereIn('data_rekomendasi.pic_1_temuan_id',$pic_unit);
        }
        
        $all=$alldata->get();
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
        $statusrekom=StatusRekomendasi::all();

        $finalData = array();
        foreach($dunitkerja as $idx=>$bd){
            $finalData[$idx]['name'] = $bd->nama_pic;
            foreach($statusrekom as $q=>$r){
                $totalData = $jmlh=$selesai=$tdd=0;
                foreach($all as $k=>$v){
                    if($bd->id == $v->pic_temuan_id && $r->id == $v->status_rekomendasi_id){
                        $totalData++;
                    }
                    if($bd->id == $v->pic_temuan_id){
                        $jmlh++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==1){
                        $selesai++;
                    }
                    if(($bd->id == $v->pic_temuan_id) && $v->status_rekomendasi_id==4){
                        $tdd++;
                    }
                }
                $finalData[$idx][$q]=$totalData;
                $finalData[$idx]['jumlah']=$jmlh;
                $finalData[$idx]['selesai']=$selesai;
                $finalData[$idx]['tdd']=$tdd;
            }
        }
        $data['nbidang']=$nbidang;
        $data['pic_unit']=$pic_unit;
        $data['alldata']=$alldata;
        $data['request']=$request;
        $data['lhp']=$lhp;
        $data['unit']=$unit;
        $data['bidang']=$arrayBidang;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        $data['jlh_by_status']=$jlh_by_status;
        $data['temuan']=$temuan;
        $data['finalData']=$finalData;
        $data['statusrekom']=$statusrekom;
        $data['rekomendasi']=$rekomendasi;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.rekap-status-sesuai-tahun.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('rekap-status-sesuai-tahun.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.rekap-status-sesuai-tahun.cetakpdf', $data), 'rekap-status-sesuai-tahun.xlsx');
        }
    }

    public function rekap_risiko_temuan(){
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        return view('backend.pages.laporan.rekap-risiko-temuan.index')
                ->with('pemeriksa',$pemeriksa);
    }
    
    public function rekap_risiko_temuan_data(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')->groupBy('pic_unit.nama_pic')->get();

        // return json_encode($alldata);
        $now=date('Y-m-d');
        $finalData = array();
        foreach($alldata as $k=>$v){
            if($v->nama_bidang!=''){
                $finalData[$k]['bidang'] = $v->nama_bidang;
            }else{
                $finalData[$k]['bidang'] = $v->keterangan;
            }
            $query = DataTemuan::where('id_lhp',$v->id_lhp)->whereNull('deleted_at')->get();
            $finalData[$k]['nama_pic'] = $v->nama_pic;
            $finalData[$k]['high'] = $query->where('level_resiko_id','2')->count();
            $finalData[$k]['medium'] = $query->where('level_resiko_id','3')->count();
            $finalData[$k]['low'] = $query->where('level_resiko_id','4')->count();
            $finalData[$k]['total'] = $finalData[$k]['high'] + $finalData[$k]['medium'] + $finalData[$k]['low'];
        }
        
        return view('backend.pages.laporan.rekap-risiko-temuan.data')
                    ->with('finalData',$finalData)
                    ->with('tgl_awal',$tgl_awal)
                    ->with('tgl_akhir',$tgl_akhir)
                    ->with('request',$request);
    }

    public function rekap_risiko_temuan_pdf(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')->groupBy('pic_unit.nama_pic')->get();

        // return json_encode($alldata);
        $now=date('Y-m-d');
        $finalData = array();
        foreach($alldata as $k=>$v){
            if($v->nama_bidang!=''){
                $finalData[$k]['bidang'] = $v->nama_bidang;
            }else{
                $finalData[$k]['bidang'] = $v->keterangan;
            }
            $query = DataTemuan::where('id_lhp',$v->id_lhp)->whereNull('deleted_at')->get();
            $finalData[$k]['nama_pic'] = $v->nama_pic;
            $finalData[$k]['high'] = $query->where('level_resiko_id','2')->count();
            $finalData[$k]['medium'] = $query->where('level_resiko_id','3')->count();
            $finalData[$k]['low'] = $query->where('level_resiko_id','4')->count();
            $finalData[$k]['total'] = $finalData[$k]['high'] + $finalData[$k]['medium'] + $finalData[$k]['low'];
        }
        $data['finalData']=$finalData;
        $data['request']=$request;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.rekap-risiko-temuan.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('rekap-risiko-temuan.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.rekap-risiko-temuan.cetakpdf', $data), 'rekap-risiko-temuan.xlsx');
        }
    }

    public function laporan_jenis_temuan(){
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        $unitkerja=PICUnit::all();
        $jenistemuan=MasterTemuan::orderBy('temuan')->get();
        $levelresiko=LevelResiko::orderBy('level_resiko')->get();
        return view('backend.pages.laporan.laporan-jenis-temuan.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('unitkerja',$unitkerja)
                ->with('levelresiko',$levelresiko)
                ->with('jenistemuan',$jenistemuan);
    }

    public function laporan_jenis_temuan_data(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            $level_resiko = implode(',', $level_resiko);
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }

        $arrayJenisTemuan = array();
        if($request->jenis_temuan != '' && $request->jenis_temuan!=0){
            $jenis_temuan = $request->jenis_temuan;
            $jenis_temuan = implode(',', $jenis_temuan);
            foreach(explode(',',$jenis_temuan) as $v){
                $arrayJenisTemuan[] = $v;
            }
        }

        $arrayKodeLHP = array();
        if($request->kode_lhp != '' && $request->kode_lhp!=0){
            $kode_lhp = $request->kode_lhp;
            $kode_lhp = implode(',', $kode_lhp);
            foreach(explode(',',$kode_lhp) as $v){
                $arrayKodeLHP[] = $v;
            }
        }

        $arrayNoLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayNoLHP[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            $unit_kerja1 = implode(',', $unit_kerja1);
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->status_rekomendasi != '' && $request->status_rekomendasi!=0){
            $status_rekomendasi = $request->status_rekomendasi;
            $status_rekomendasi = implode(',', $status_rekomendasi);
            foreach(explode(',',$status_rekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->select('data_rekomendasi.id as id_rekom', 'level_resiko.level_resiko', 'pemeriksa.pemeriksa', 'master_temuan.temuan as jenis_temuan',
                                    'daftar_lhp.kode_lhp', 'daftar_lhp.no_lhp', 'pic_unit.nama_pic', 'data_temuan.no_temuan', 'data_temuan.temuan',
                                    'data_rekomendasi.nomor_rekomendasi','data_rekomendasi.rekomendasi', 'status_rekomendasi.rekomendasi as status_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->leftjoin('status_rekomendasi','data_rekomendasi.status_rekomendasi_id', '=', 'status_rekomendasi.id')
                                    ->leftjoin('master_temuan','data_temuan.jenis_temuan_id', '=', 'master_temuan.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayJenisTemuan)>0 && !in_array(0, $arrayJenisTemuan)){
                                        $alldata = $alldata->whereIn('data_temuan.jenis_temuan_id', $arrayJenisTemuan);
                                    }
                                    if(count($arrayKodeLHP)>0 && !in_array(0, $arrayKodeLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.kode_lhp', $arrayKodeLHP);
                                    }
                                    if(count($arrayNoLHP)>0 && !in_array(0, $arrayNoLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.no_lhp', $arrayNoLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
                                        ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
                                    }
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')->get();

        // return json_encode($alldata);
        return view('backend.pages.laporan.laporan-jenis-temuan.data')
                ->with('alldata',$alldata)
                ->with('tgl_awal',$tgl_awal)
                ->with('tgl_akhir',$tgl_akhir)
                ->with('request',$request)
                ->with('no_lhp',$arrayNoLHP)
                ->with('kode_lhp',$arrayKodeLHP);
    }

    public function laporan_jenis_temuan_pdf(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);

        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;

        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }
        
        $arrayLevelResiko = array();
        if($request->level_resiko != '' && $request->level_resiko!=0){
            $level_resiko = $request->level_resiko;
            foreach(explode(',',$level_resiko) as $v){
                $arrayLevelResiko[] = $v;
            }
        }

        $arrayJenisTemuan = array();
        if($request->jenis_temuan != '' && $request->jenis_temuan!=0){
            $jenis_temuan = $request->jenis_temuan;
            foreach(explode(',',$jenis_temuan) as $v){
                $arrayJenisTemuan[] = $v;
            }
        }

        $arrayKodeLHP = array();
        if($request->kode_lhp != '' && $request->kode_lhp!=0){
            $kode_lhp = $request->kode_lhp;
            foreach(explode(',',$kode_lhp) as $v){
                $arrayKodeLHP[] = $v;
            }
        }

        $arrayNoLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayNoLHP[] = $v;
            }
        }
        $arrayUnitKerja1 = array();
        if($request->unit_kerja1 != '' && $request->unit_kerja1!=0){
            $unit_kerja1 = $request->unit_kerja1;
            foreach(explode(',',$unit_kerja1) as $v){
                $arrayUnitKerja1[] = $v;
            }
        }
        $arrayStatusRekom = array();
        if($request->status_rekomendasi != '' && $request->status_rekomendasi!=0){
            $status_rekomendasi = $request->status_rekomendasi;
            foreach(explode(',',$status_rekomendasi) as $v){
                $arrayStatusRekom[] = $v;
            }
        }
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->select('data_rekomendasi.id as id_rekom', 'level_resiko.level_resiko', 'pemeriksa.pemeriksa', 'master_temuan.temuan as jenis_temuan',
                                    'daftar_lhp.kode_lhp', 'daftar_lhp.no_lhp', 'pic_unit.nama_pic', 'data_temuan.no_temuan', 'data_temuan.temuan',
                                    'data_rekomendasi.nomor_rekomendasi','data_rekomendasi.rekomendasi', 'status_rekomendasi.rekomendasi as status_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->join('level_resiko','data_temuan.level_resiko_id','=','level_resiko.id')
                                    ->leftjoin('status_rekomendasi','data_rekomendasi.status_rekomendasi_id', '=', 'status_rekomendasi.id')
                                    ->leftjoin('master_temuan','data_temuan.jenis_temuan_id', '=', 'master_temuan.id')
                                    ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                                    ->leftjoin('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                                    ->leftjoin('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
                                    if(count($arrayLevelResiko)>0 && !in_array(0, $arrayLevelResiko)){
                                        $alldata = $alldata->whereIn('data_temuan.level_resiko_id', $arrayLevelResiko);
                                    }
                                    if(count($arrayJenisTemuan)>0 && !in_array(0, $arrayJenisTemuan)){
                                        $alldata = $alldata->whereIn('data_temuan.jenis_temuan_id', $arrayJenisTemuan);
                                    }
                                    if(count($arrayKodeLHP)>0 && !in_array(0, $arrayKodeLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.kode_lhp', $arrayKodeLHP);
                                    }
                                    if(count($arrayNoLHP)>0 && !in_array(0, $arrayNoLHP)){
                                        $alldata = $alldata->whereIn('daftar_lhp.no_lhp', $arrayNoLHP);
                                    }
                                    if(count($arrayUnitKerja1)>0 && !in_array(0, $arrayUnitKerja1)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.pic_1_temuan_id', $arrayUnitKerja1)
                                        ->orWhereIn('data_rekomendasi.pic_2_temuan_id','like', "%$arrayUnitKerja1%,");
                                    }
                                    if(count($arrayStatusRekom)>0 && !in_array(0, $arrayStatusRekom)){
                                        $alldata = $alldata->whereIn('data_rekomendasi.status_rekomendasi_id', $arrayStatusRekom);
                                    }
        $alldata = $alldata->whereNull('data_rekomendasi.deleted_at')->get();

        $data['alldata']=$alldata;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.laporan-jenis-temuan.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-jenis-temuan.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.laporan-jenis-temuan.cetakpdf', $data), 'laporan-jenis-temuan.xlsx');
        }
    }

    public function laporan_jenis_audit(){
        $pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $jenisaudit=JenisAudit::orderBy('jenis_audit')->get();
        return view('backend.pages.laporan.laporan-jenis-audit.index')
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
    }

    public function laporan_jenis_audit_data(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tgl_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tgl_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            $pemeriksa = implode(',', $pemeriksa);
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }

        $arrayJenisAudit = array();
        if($request->jenis_audit != '' && $request->jenis_audit!=0){
            $jenis_audit = $request->jenis_audit;
            $jenis_audit = implode(',', $jenis_audit);
            foreach(explode(',',$jenis_audit) as $v){
                $arrayJenisAudit[] = $v;
            }
        }

        $arrayKodeLHP = array();
        if($request->kode_lhp != '' && $request->kode_lhp!=0){
            $kode_lhp = $request->kode_lhp;
            $kode_lhp = implode(',', $kode_lhp);
            foreach(explode(',',$kode_lhp) as $v){
                $arrayKodeLHP[] = $v;
            }
        }

        $arrayNoLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            $no_lhp = implode(',', $no_lhp);
            foreach(explode(',',$no_lhp) as $v){
                $arrayNoLHP[] = $v;
            }
        }
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->leftJoin('jenis_audit', 'daftar_lhp.jenis_audit_id','=','jenis_audit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
        $alldata = $alldata->groupBy('daftar_lhp.no_lhp')->get();
        
        $finalData = array();
        foreach($alldata as $k=>$v){
            $finalData[$k]['pemeriksa'] = $v->pemeriksa;
            $finalData[$k]['jenis_audit'] = $v->jenis_audit;
            $finalData[$k]['kode_lhp'] = $v->kode_lhp;
            $finalData[$k]['no_lhp'] = $v->no_lhp;

            $temuanData = DataTemuan::where('id_lhp',$v->id_lhp)->whereNull('deleted_at')->get();
            $totalRekom = 0;
            foreach($temuanData as $td){
                $totalRekom += DataRekomendasi::where('id_temuan', $td->id)->whereNull('deleted_at')->get()->count();
            }
            
            $finalData[$k]['jumlah_temuan'] = $temuanData->count();
            $finalData[$k]['jumlah_rekomendasi'] = $totalRekom;
        }
        return view('backend.pages.laporan.laporan-jenis-audit.data')
                ->with('finalData',$finalData)
                ->with('tgl_awal',$tgl_awal)
                ->with('tgl_akhir',$tgl_akhir)
                ->with('request',$request)
                ->with('no_lhp',$arrayNoLHP)
                ->with('kode_lhp',$arrayKodeLHP);
    }

    public function laporan_jenis_audit_pdf(Request $request){
        list($tg1,$bl1,$th1)=explode('/',$request->tanggal_awal);
        list($tg2,$bl2,$th2)=explode('/',$request->tanggal_akhir);
        
        $tgl_awal = $th1.'-'.$bl1.'-'.$tg1;
        $tgl_akhir = $th2.'-'.$bl2.'-'.$tg2;
        
        $arrayPemeriksa = array();
        if($request->pemeriksa != '' && $request->pemeriksa!=0){
            $pemeriksa = $request->pemeriksa;
            foreach(explode(',',$pemeriksa) as $v){
                $arrayPemeriksa[] = $v;
            }
        }

        $arrayJenisAudit = array();
        if($request->jenis_audit != '' && $request->jenis_audit!=0){
            $jenis_audit = $request->jenis_audit;
            foreach(explode(',',$jenis_audit) as $v){
                $arrayJenisAudit[] = $v;
            }
        }

        $arrayKodeLHP = array();
        if($request->kode_lhp != '' && $request->kode_lhp!=0){
            $kode_lhp = $request->kode_lhp;
            foreach(explode(',',$kode_lhp) as $v){
                $arrayKodeLHP[] = $v;
            }
        }

        $arrayNoLHP = array();
        if($request->no_lhp != '' && $request->no_lhp!=0){
            $no_lhp = $request->no_lhp;
            foreach(explode(',',$no_lhp) as $v){
                $arrayNoLHP[] = $v;
            }
        }
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                    ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->join('pemeriksa','daftar_lhp.pemeriksa_id','=','pemeriksa.id')
                                    ->leftJoin('jenis_audit', 'daftar_lhp.jenis_audit_id','=','jenis_audit.id')
                                    ->whereBetween('daftar_lhp.tanggal_lhp', [$tgl_awal, $tgl_akhir])
                                    ->where('daftar_lhp.status_lhp','Publish LHP');
                                    if(count($arrayPemeriksa)>0 && !in_array(0, $arrayPemeriksa)){
                                        $alldata = $alldata->whereIn('daftar_lhp.pemeriksa_id', $arrayPemeriksa);
                                    }
        $alldata = $alldata->groupBy('daftar_lhp.no_lhp')->get();
        
        $finalData = array();
        foreach($alldata as $k=>$v){
            $finalData[$k]['pemeriksa'] = $v->pemeriksa;
            $finalData[$k]['jenis_audit'] = $v->jenis_audit;
            $finalData[$k]['kode_lhp'] = $v->kode_lhp;
            $finalData[$k]['no_lhp'] = $v->no_lhp;

            $temuanData = DataTemuan::where('id_lhp',$v->id_lhp)->whereNull('deleted_at')->get();
            $totalRekom = 0;
            foreach($temuanData as $td){
                $totalRekom += DataRekomendasi::where('id_temuan', $td->id)->whereNull('deleted_at')->get()->count();
            }
            
            $finalData[$k]['jumlah_temuan'] = $temuanData->count();
            $finalData[$k]['jumlah_rekomendasi'] = $totalRekom;
        }
        $data['finalData']=$finalData;
        $data['tgl_awal']=$tgl_awal;
        $data['tgl_akhir']=$tgl_akhir;
        if($request->export == 'pdf'){
            $pdf = PDF::loadView('backend.pages.laporan.laporan-jenis-audit.cetakpdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan-jenis-audit.pdf');
        }elseif($request->export == 'xls'){
            return Excel::download(new LaporanExportController('backend.pages.laporan.laporan-jenis-audit.cetakpdf', $data), 'laporan-jenis-audit.xlsx');
        }
    }
}