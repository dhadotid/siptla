<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;
use App\Models\DaftarTemuan;
use App\Models\DataRekomendasi;
use App\Models\DataTemuan;
use App\Models\Pemeriksa;
use App\Models\PICUnit;
use App\Models\RincianSewa;
use App\Models\RincianUangMuka;
use App\Models\RincianListrik;
use App\Models\RincianPiutang;
use App\Models\RincianPiutangKaryawan;
use App\Models\RincianHutangTitipan;
use App\Models\RincianPenutupanRekening;
use App\Models\RincianUmum;
use App\Models\TindakLanjutRincian;
use App\Models\CatatanMonev;
use App\Models\StatusRekomendasi;
use App\Models\RincianKontribusi;
use App\Models\RincianNonSetoranPerpanjanganPerjanjianKerjasama;
use App\Models\RincianNonSetoran;
use App\Models\RincianNonSetoranUmum;
use App\Models\RincianNonSetoranPertanggungjawabanUangMuka;
use Auth;
use App\Models\BankList;
use Validator;

class TindakLanjutController extends Controller
{
    public function index($id_rekom,$idtemuan)
    {
        $temuan=DataTemuan::find($idtemuan);

        $data=DaftarTemuan::selectRaw('*, daftar_lhp.id as id_lhp')
                ->where('daftar_lhp.id',$temuan->id_lhp)
                ->with('dpemeriksa')->first();
        $tindaklanjut=TindakLanjutTemuan::where('temuan_id',$idtemuan)->where('rekomendasi_id',$id_rekom)->get();

        
        return view('backend.pages.data-lhp.auditor-junior.tindak-lanjut-index')
            ->with('tindaklanjut',$tindaklanjut)
            ->with('data',$data);
    }
    public function index_unitkerja($id_rekom,$idtemuan)
    {
        $temuan=DataTemuan::find($idtemuan);

        $data=DaftarTemuan::selectRaw('*, daftar_lhp.id as id_lhp')
                ->where('daftar_lhp.id',$temuan->id_lhp)
                ->with('dpemeriksa')->first();
        $tindaklanjut=TindakLanjutTemuan::where('temuan_id',$idtemuan)->where('rekomendasi_id',$id_rekom)->get();

        
        return view('backend.pages.data-lhp.pic-unit.tindak-lanjut-index')
            ->with('tindaklanjut',$tindaklanjut)
            ->with('data',$data);
    }
    public function simpan(Request $request,$idrekom)
    {
        // dd($request);
        $rekom=DataRekomendasi::where('id',$idrekom)->with('dtemuan')->first();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        
        $idlhp=0;
        if(isset($rekom->dtemuan->id_lhp))
            $idlhp=$rekom->dtemuan->id_lhp;

        $idtemuan=$rekom->id_temuan;
        $tindaklanjut=$request->tindaklanjut;
        $nilai_tindaklanjut =str_replace('.','',$request->nilai_tindaklanjut);

        if(isset($request->idtindaklanjut))
        {
            $idtindaklanjut=$request->idtindaklanjut;
            $tindak=TindakLanjutTemuan::find($idtindaklanjut);
        }
        else
            $tindak=new TindakLanjutTemuan;
        
        $tindak->lhp_id = $idlhp;
        $tindak->temuan_id = $idtemuan;
        $tindak->rekomendasi_id = $idrekom;
        if($user_pic)
        {
            if($rekom->pic_1_temuan_id==$user_pic->id)
                $tindak->pic_1_id = $user_pic->id;
            
            if($rekom->pic_2_temuan_id==$user_pic->id)
                $tindak->pic_2_id = $user_pic->id;
        }
        // $tindak->pic_1_id = $rekom->pic_1_temuan_id;
        // $tindak->pic_2_id = $rekom->pic_2_temuan_id;
        $tindak->tindak_lanjut = $tindaklanjut;
        $tindak->nilai = $nilai_tindaklanjut;
        $c=$tindak->save();

        $tindak_id=$tindak->id;

        if($request->hasFile('file')){
            $file = $request->file('file');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension(); 
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // $fileNameToStore = rand() . '.' . $file->getClientOriginalExtension(); 
            $path = $request->file('file')->storeAs('public/dokumen',$fileNameToStore);

            $dokumen=new DokumenTindakLanjut;
            $dokumen->id_tindak_lanjut_temuan=$tindak_id;
            $dokumen->nama_dokumen=$fileNameToStore;
            $dokumen->path=$path;
            $dokumen->save();
        }
        if($c)
            echo $idtemuan;
        else
            echo 0;
    }
    
    public function edit($id)
    {
        $edit=TindakLanjutTemuan::selectRaw('*,tindak_lanjut_temuan.id as tl_id')->where('id',$id)->with('pic1')->with('pic2')->with('dokumen_tindak_lanjut')->first();
        return $edit;
    }

    public function destroy($id)
    {
        $d=TindakLanjutTemuan::destroy($id);
        return $d;
    }

    // public function junior_list($tahun=null,$rekomid=null,$temuanid=null,$statusrekom=null)
    public function junior_list(Request $request)
    {
        // return $request->all();
        list($tg_awal,$bl_awal,$th_awal) = explode('/',$request->tgl_awal);
        list($tg_akhir,$bl_akhir,$th_akhir) = explode('/',$request->tgl_akhir);
        $tahun=($request->tahun ? $request->tahun : date('Y'));
        $rekomid=($request->rekomid ? $request->rekomid : -1);
        $temuan_id=($request->temuan_id ? $request->temuan_id : -1);
        $statusrekom=($request->statusrekom ? $request->statusrekom : -1);
        $pemeriksa=($request->pemeriksa ? $request->pemeriksa : -1);

        $keybataswaktu='';
        if(isset($request->keybataswaktu))
            $keybataswaktu=$request->keybataswaktu;

        $t_awal=$th_awal.'-'.$bl_awal.'-'.$tg_awal;
        $t_akhir=$th_akhir.'-'.$bl_akhir.'-'.$tg_akhir;

        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;

            if($v->id_user==Auth::user()->id)
                $user_pic=$v;
        }

        $wh=array();
        if($request->rekomid!='')
        {
            if($request->rekomid!=0)
                $wh['data_rekomendasi.id']=$request->rekomid;
        }
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        if($request->temuan_id!='')
        {
            if($request->temuan_id!=0)
                $wh['data_temuan.id']=$request->temuan_id;
        }

        if($request->statusrekom!='')
        {
            if($request->statusrekom!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekom;
        }

        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }
        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }
        
        if(Auth::user()->level=='auditor-junior')
        {
            // return $wh;
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where($wh)
                                ->where('daftar_lhp.user_input_id',Auth::user()->id)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();

        }
        elseif(Auth::user()->level=='auditor-senior')
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        }

        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($alldata as $k=>$v)
        {
            if($keybataswaktu!='')
            {
                if($v->tanggal_penyelesaian!='')
                {
                    $tgl_penyelsaian=$v->tanggal_penyelesaian;
                    if($keybataswaktu=='sudah-masuk-batas-waktu-penyelesaian')
                    {
                        if($now==$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }
                    if($keybataswaktu=='melewati-batas-waktu-penyelesaian')
                    {
                        if($now>$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }
                    if($keybataswaktu=='belum-masuk-batas-waktu-penyelesaian')
                    {
                        if($now<$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }

                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_temuan]=$v;
                $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }
        }

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-data')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('keybataswaktu',$keybataswaktu)
                ->with('temuanid',$temuan_id)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);

        // $temuan=$rekomendasi=$idtemuanarray=array();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();

        // $idlhparray=array();
        // foreach($datalhp as $k=>$v)
        // {
        //     $idlhparray[$v->id]=$v->id;
        // }

        // if(count($idlhparray)!=0)
        // {
        //     $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->get();
        //     foreach($temuan as $kk=>$vv)
        //     {
        //         $idtemuanarray[]=$vv->id;
        //     }
        // }

        // if(count($idtemuanarray)!=0)
        // {
        //     if($statusrekom==null)
        //         $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->get();
        //     else
        //         $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->where('status_rekomendasi_id',$statusrekom)->get();

        //     foreach($rekom as $k=>$v)
        //     {
        //         $rekomendasi[$v->id_temuan][]=$v;
        //     }
        // }
        // return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-data')
        //                 ->with('rekomendasi',$rekomendasi)
        //                 ->with('idtemuanarray',$idtemuanarray)
        //                 ->with('temuan',$temuan);
    }

    public function junior_index(Request $request,$tahun=null,$rekomid=null,$temuanid=null)
    {
        // return $request->all();
        $keybataswaktu='';
        if(isset($request->key))
            $keybataswaktu=$request->key;

        // return $keybataswaktu;
        $tahun=($tahun==null ? date('Y') : $tahun);
        $rekomid=($rekomid==null ? -1 : $rekomid);
        $temuanid=($temuanid==null ? -1 : $temuanid);

       
        $pemeriksa=Pemeriksa::orderBy('code')->get();

        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;

            if($v->id_user==Auth::user()->id)
                $user_pic=$v;
        }

        $pemeriksa=Pemeriksa::orderBy('code')->get();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();

        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }
        if(Auth::user()->level=='auditor-junior')
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                // ->join('tindak_lanjut_rincian', 'data_temuan.id', '=', 'tindak_lanjut_rincian.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where('daftar_lhp.user_input_id',Auth::user()->id)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        }
        elseif(Auth::user()->level=='auditor-senior')
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        }elseif(Auth::user()->level=='super-user'){
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        }
        

        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        $now=date('Y-m-d');
        foreach($alldata as $k=>$v)
        {
            if($keybataswaktu!='')
            {
                if($v->tanggal_penyelesaian!='')
                {
                    $tgl_penyelsaian=$v->tanggal_penyelesaian;
                    if($keybataswaktu=='sudah-masuk-batas-waktu-penyelesaian')
                    {
                        if($now==$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }
                    if($keybataswaktu=='melewati-batas-waktu-penyelesaian')
                    {
                        if($now>$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }
                    if($keybataswaktu=='belum-masuk-batas-waktu-penyelesaian')
                    {
                        if($now<$tgl_penyelsaian)
                        {
                            $lhp[$v->id_lhp]=$v;
                            $temuan[$v->id_temuan]=$v;
                            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                        }
                    }
                    if($keybataswaktu=='create-oleh-unit-kerja'){
                        $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                    }
                    if($keybataswaktu=='belum-direview-spi'){
                        $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                    }
                    if($keybataswaktu=='sedang-direview-spi'){
                        $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                    }
                    if($keybataswaktu=='sudah-direview-spi'){
                        $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                    }
                    if($keybataswaktu=='sudah-dipublish-oleh-spi'){
                        $arrayrekomid[$v->id_rekom]=$v->id_rekom;
                    }
                }
            }
            else
            {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_temuan]=$v;
                $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                $arrayrekomid[$v->id_rekom]=$v->id_rekom;
            }
        }

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        if($keybataswaktu=='create-oleh-unit-kerja'){
            foreach($alldata as $k=>$v){
                if(!isset($tindaklanjut[$v->id]) ){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_temuan]=$v;
                    $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                }
            }
        }else if($keybataswaktu=='belum-direview-spi'){
            foreach($alldata as $k=>$v){
                if(isset($tindaklanjut[$v->id]) && $v->review_spi =='' && $v->published!=1){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_temuan]=$v;
                    $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                }
            }
        }else if($keybataswaktu=='sudah-direview-spi'){
            foreach($alldata as $k=>$v){
                if(isset($tindaklanjut[$v->id]) && $v->review_spi !='' && $v->published==0){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_temuan]=$v;
                    $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                }
            }
        }else if($keybataswaktu=='sedang-direview-spi'){
            foreach($alldata as $k=>$v){
                if(isset($tindaklanjut[$v->id]) && $v->review_spi =='' && $v->published==0){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_temuan]=$v;
                    $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                }
            }
        }else if($keybataswaktu=='sudah-dipublish-oleh-spi'){
            foreach($alldata as $k=>$v){
                if(isset($tindaklanjut[$v->id]) && $v->review_spi !='' && $v->published==1){
                    $lhp[$v->id_lhp]=$v;
                    $temuan[$v->id_temuan]=$v;
                    $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
                }
            }
        }

        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('keybataswaktu',$keybataswaktu)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->orderBy('id','desc')->get();
        // // return $datalhp;
        // $idlhparray=array();
        // foreach($datalhp as $k=>$v)
        // {
        //     $idlhparray[$v->id]=$v->id;
        // }
        // $temuan=$rekomendasi=$idtemuanarray=array();
        // if(count($idlhparray)!=0)
        // {
        //     $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->get();
        //     foreach($temuan as $kk=>$vv)
        //     {
        //         $idtemuanarray[]=$vv->id;
        //     }
        // }

        // if(count($idtemuanarray)!=0)
        // {
        //     $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->get();
        //     foreach($rekom as $k=>$v)
        //     {
        //         $rekomendasi[$v->id_temuan][]=$v;
        //     }
        // }
        // return view('backend.pages.data-lhp.auditor-junior.tindaklanjut')
        //         ->with('tahun',$tahun)
        //         ->with('rekomid',$rekomid)
        //         ->with('idlhparray',$idlhparray)
        //         ->with('datalhp',$datalhp)
        //         ->with('pemeriksa',$pemeriksa)
        //         ->with('rekomendasi',$rekomendasi)
        //         ->with('temuan',$temuan)
        //         ->with('temuanid',$temuanid);
    }
    
    public function unitkerja_index($tahun=null,$rekomid=null,$temuanid=null)
    {
        $tahun=($tahun==null ? date('Y') : $tahun);
        $rekomid=($rekomid==null ? -1 : $rekomid);
        $temuanid=($temuanid==null ? -1 : $temuanid);

        // $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;

            if($v->id_user==Auth::user()->id)
                $user_pic=$v;
        }

        $pemeriksa=Pemeriksa::orderBy('code')->get();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();


        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where(function($query) use ($user_pic){
                                    $query->where('data_rekomendasi.pic_1_temuan_id', $user_pic->id);
                                    $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$user_pic->id%,");
                                    // $query->orWhere('data_rekomendasi.pic_2_temuan_id', $user_pic->id);
                                })
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();

        

        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        $idpic2=array();
        foreach($alldata as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_temuan]=$v;
            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
        }

        $jlhtl=$this->jlh_tindaklanjut();
        // return $jlhtl;
        $rinc['sewa']=RincianSewa::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['uangmuka']=RincianUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['listrik']=RincianListrik::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutang']=RincianPiutang::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutangkary']=RincianPiutangKaryawan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['titipan']=RincianHutangTitipan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['penutupanrekening']=RincianPenutupanRekening::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['umum']=RincianUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();
        //
        $rinc['kontribusi']=RincianKontribusi::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranperjanjiankerjasama'] = RincianNonSetoranPerpanjanganPerjanjianKerjasama::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoran'] = RincianNonSetoran::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranumum'] = RincianNonSetoranUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranpertanggungjawabanuangmuka'] = RincianNonSetoranPertanggungjawabanUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();

        $rincian=array();
        foreach($rinc as $jns=>$det)
        {
            foreach($det as $k=>$v)
            {
                $rincian[$jns][$v->id_rekomendasi][]=$v;
            }
        }

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }

        $tlrincian=TindakLanjutRincian::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc=array();
        foreach($tlrincian as $k=>$v)
        {
            $rinc[$v->id_rekomendasi][]=$v;
        }

        // return $tindaklanjut;
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut')
                ->with('jumlahtl',$jlhtl)
                ->with('jumlahrincian',$rinc)
                ->with('rincian',$rincian)
                ->with('strekom',$st)
                ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
        

        // $datalhp=DaftarTemuan::where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->orderBy('id','desc')->get();
        // // return $datalhp;
        // $idlhparray=$dlhp=$ambiltemuan=array();
        // $lhp=array();
        // foreach($datalhp as $k=>$v)
        // {
        //     $idlhparray[$v->id]=$v->id;
        //     $lhp[$v->id]=$v;
        // }
        // $temuann=$temuan=$rekomendasi=$idtemuanarray=array();
        // if(count($idlhparray)!=0)
        // {
        //     // $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->where('pic_temuan_id',$user_pic->id)->with('totemuan')->get();
        //     $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->with('totemuan')->get();
        //     foreach($temuan as $kk=>$vv)
        //     {
        //         if($vv->totemuan->tahun_pemeriksa==$tahun)
        //         {
        //             // if($vv->pic_temuan_id==$user_pic->id)
        //             // {
        //                 $idtemuanarray[]=$vv->id;
        //                 if(isset($lhp[$vv->id_lhp]))
        //                     $dlhp[]=$lhp[$vv->id_lhp];
        //             // }
        //                 $temuann[$vv->id]=$vv;
        //         }
        //     }
        // }

        // // return $idtemuanarray;
        // if(count($idtemuanarray)!=0)
        // {
        //     $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)
        //                 ->where(function($query) use ($user_pic){
        //                     $query->where('pic_1_temuan_id', $user_pic->id);
        //                     $query->orWhere('pic_2_temuan_id', $user_pic->id);
        //                 })->with('picunit2')->with('dtemuan')->get();

        //     foreach($rekom as $k=>$v)
        //     {
        //         // if($v->pic_1_temuan_id!=null)
        //         // {
        //         //     if()
        //         //     $rekomendasi[$v->id_temuan][]=$v;
        //         // }
        //         // else
        //             $rekomendasi[$v->id_temuan][]=$v;

        //             if(isset($temuann[$v->dtemuan->id_lhp]))
        //                 $ambiltemuan[$v->dtemuan->id_lhp]=$temuann[$v->dtemuan->id_lhp];
        //     }
        // }
        // return $idtemuanarray;
        // return $rekomendasi;
        
        
        // return view('backend.pages.data-lhp.pic-unit.tindaklanjut')
        //         ->with('tahun',$tahun)
        //         ->with('rekomid',$rekomid)
        //         ->with('idlhparray',$idlhparray)
        //         ->with('datalhp',$dlhp)
        //         ->with('pemeriksa',$pemeriksa)
        //         ->with('rekomendasi',$rekomendasi)
        //         // ->with('temuan',$ambiltemuan)
        //         ->with('temuan',$temuan)
        //         ->with('temuanid',$temuanid);
    }

    public function unitkerja_list(Request $request)
    {
        $tahun=($request->tahun ? $request->tahun : date('Y'));
        $rekomid=($request->rekomid ? $request->rekomid : -1);
        $temuanid=($request->temuan_id ? $request->temuan_id : -1);
        $statusrekom=($request->statusrekom ? $request->statusrekom : -1);
        $pemeriksa=($request->pemeriksa ? $request->pemeriksa : -1);
        $no_lhp=($request->no_lhp ? $request->no_lhp : -1);

        $temuan=$rekomendasi=$idtemuanarray=array();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();

        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;

            if($v->id_user==Auth::user()->id)
                $user_pic=$v;
        }

        $wh=array();
        if($request->pemeriksa!='')
        {
            if($request->pemeriksa!=0)
                $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;
        }

        if($request->rekomid!='')
        {
            if($request->rekomid!=0)
                $wh['data_rekomendasi.id']=$request->rekomid;
        }

        if($request->temuan_id!='')
        {
            if($request->temuan_id!=0)
                $wh['data_temuan.id']=$request->temuan_id;
        }

        if($request->statusrekom!='')
        {
            if($request->statusrekom!=0)
                $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekom;
        }
        if($request->no_lhp!='')
        {
            if($request->no_lhp!=0)
                $wh['daftar_lhp.id']=$request->no_lhp;
        }

        // return $wh;
        $pemeriksaa=Pemeriksa::orderBy('code')->get();
        $jlhtl=$this->jlh_tindaklanjut();

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                // ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where($wh)
                                ->where(function($query) use ($user_pic){
                                    $query->where('data_rekomendasi.pic_1_temuan_id', $user_pic->id);
                                    $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$user_pic->id%,");
                                    // $query->orWhere('data_rekomendasi.pic_2_temuan_id', $user_pic->id);
                                })
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();

        
                                $jlhtl=$this->jlh_tindaklanjut();

        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        $idpic2=array();
        foreach($alldata as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_temuan]=$v;
            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
        }

        $rinc['sewa']=RincianSewa::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['uangmuka']=RincianUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['listrik']=RincianListrik::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutang']=RincianPiutang::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutangkary']=RincianPiutangKaryawan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['titipan']=RincianHutangTitipan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['penutupanrekening']=RincianPenutupanRekening::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['umum']=RincianUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();
        //
        $rinc['kontribusi']=RincianKontribusi::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranperjanjiankerjasama'] = RincianNonSetoranPerpanjanganPerjanjianKerjasama::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoran'] = RincianNonSetoran::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranumum'] = RincianNonSetoranUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['nonsetoranpertanggungjawabanuangmuka'] = RincianNonSetoranPertanggungjawabanUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();

        $rincian=array();
        foreach($rinc as $jns=>$det)
        {
            foreach($det as $k=>$v)
            {
                $rincian[$jns][$v->id_rekomendasi][]=$v;
            }
        }

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }

        
        $lhp=$temuan=$rekomendasi=$dt=array();
        foreach($alldata as $k=>$v)
        {
            // if(betweendate($v->tanggal_lhp,$t_awal,$t_akhir))
            // {
                $lhp[$v->id_lhp]=$v;
                $temuan[$v->id_temuan]=$v;
                $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            // }
            // $dt['cek'][]=$v->tanggal_lhp;
            // $dt['awal'][]=$t_awal;
            // $dt['akhir'][]=$t_akhir;

        }
        // return $temuan;
        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }
 
        $tlrincian=TindakLanjutRincian::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc=array();
        foreach($tlrincian as $k=>$v)
        {
            $rinc[$v->id_rekomendasi][]=$v;
        }
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-list')
        ->with('jumlahtl',$jlhtl)
                ->with('tahun',$tahun)
                ->with('jumlahrincian',$rinc)
                ->with('rincian',$rincian)
                ->with('rekomid',$rekomid)
                ->with('strekom',$st)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('lhp',$lhp)
                ->with('user_pic',$user_pic)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksaa)
                // ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuan',$temuan);
                
                // ->with('jumlahtl',$jlhtl)
                // // ->with('tahun',$tahun)
                // ->with('jlhrincian',$rinc)
                // ->with('rekomid',$rekomid)
                // ->with('strekom',$st)
                // ->with('temuanid',$temuanid)
                // ->with('alldata',$alldata)
                // ->with('pic',$pic)
                // ->with('lhp',$lhp)
                // ->with('user_pic',$user_pic)
                // ->with('rekomendasi',$rekomendasi)
                // ->with('pemeriksa',$pemeriksaa)
                // ->with('temuan',$temuan);
    }

    public function set_tgl_penyelesaian($temuanid,$rekomid,$tgl,$bln,$thn)
    {
        $date=$thn.'-'.$bln.'-'.$tgl;
        $rekom=DataRekomendasi::find($rekomid);
        $rekom->tanggal_penyelesaian=$date;
        $c=$rekom->save();
        if($c)
            echo '<div class="row" style="height:60px;border-bottom:1px dotted #ddd;padding:5px 0">
                                                            <div class="col-md-12">'.tgl_indo($date).'</div>
                                                        </div>';
            // echo tgl_indo($date);
    }

    public function unitkerja_edit_form($idlhp,$temuan_id,$rekom_id,$id_tl)
    {
        $tl=TindakLanjutTemuan::find($id_tl);

        $data=DaftarTemuan::find($idlhp);

        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();

        $temuan=DataTemuan::where('id_lhp',$idlhp)->first();

        $rekomendasi=DataRekomendasi::where('id',$tl->rekomendasi_id)->first();
        
        

        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-form-edit')
                        ->with('rekomendasi',$rekomendasi)
                        ->with('tl',$tl)
                        ->with('temuan_id',$temuan_id)
                        ->with('data',$data)
                        ->with('rekom_id',$rekom_id)
                        ->with('idlhp',$idlhp)
                        ->with('temuan',$temuan);
    }
    public function unitkerja_add_form($idlhp,$temuan_id_index,$rekom_id_index,$idrekom=null)
    {
        list($temuan_id,$temuan_idx)=explode('_',$temuan_id_index);
        list($rekom_id,$rekom_idx)=explode('_',$rekom_id_index);
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();

        $data=DaftarTemuan::find($idlhp);

        $getrekom=DataRekomendasi::where('pic_1_temuan_id', $user_pic->id)->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,")->with('dtemuan')->orderBy('nomor_rekomendasi')->get();
        $getidtemuan=$datarekom=array();
        foreach($getrekom as $kr=>$vr)
        {
            if(isset($vr->dtemuan->id_lhp))
            {
                if($vr->dtemuan->id_lhp==$idlhp)
                {
                    $getidtemuan[$vr->id_temuan]=$vr->id_temuan;
                    $datarekom[$vr->id_temuan][]=$vr;
                }
            }
        }

        // $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
        //                         ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
        //                         ->where('data_temuan.id',$temuan_id)
        //                         ->where('data_rekomendasi.id',$rekom_id)
        //                         ->whereNull('data_rekomendasi.deleted_at')
        //                         ->orderBy('data_rekomendasi.nomor_rekomendasi')
        //                         ->get();

        // $lhp=$temuan=$rekomendasi=array();
        // foreach($alldata as $k=>$v)
        // {
        //     $lhp[$v->id_lhp]=$v;
        //     $temuan[$v->id_temuan]=$v;
        //     $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
        // }

        // return view('backend.pages.data-lhp.pic-unit.tindaklanjut-form')
        //                 ->with('rekomendasi',isset($drekomendasi[$rekom_idx]) ? $drekomendasi[$rekom_idx] : array())
        //                 ->with('temuan_id',$temuan_id)
        //                 ->with('data',$data)
        //                 ->with('rekom_id',$rekom_id)
        //                 ->with('temuan_idx',$temuan_idx)
        //                 ->with('rekom_idx',$rekom_idx)
        //                 ->with('dtemuan',$dtemuan)
        //                 ->with('idlhp',$idlhp)
        //                 ->with('drekomendasi',$drekomendasi)
        //                 ->with('temuan',isset($dtemuan[$temuan_idx]) ? $dtemuan[$temuan_idx] : array());
                        
        // $temuan=DataTemuan::where('id_lhp',$idlhp)->get();
        $temuan=DataTemuan::whereIn('id',$getidtemuan)->get();
        // $temuan=DataTemuan::where('pic_temuan_id',$user_pic->id)->get();
        // $temuan=DataTemuan::all();
        $arrayidtemuan=$dtemuan=$ddtem=array();
        foreach($temuan as $k=>$v)
        {
            // if($idlhp==$v->id_lhp)
            $arrayidtemuan[$v->id]=$v->id;   
            $dtemuan[$k]=$v;
            $ddtem[$v->id]=$v;
        }

        
        // ->where('pic_1_temuan_id',$user_pic->id)
        if($temuan_idx!=0)
        {
            $dtem=$dtemuan[$temuan_idx];
            // $rekomendasi=DataRekomendasi::where('id_temuan',$dtem->id)
            //     ->where(function($query) use ($user_pic){
            //         $query->where('pic_1_temuan_id', $user_pic->id);
            //         $query->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,");
            //     })->with('dtemuan')->get();
            $rekomendasi=$datarekom[$dtem->id];
            // ->where('pic_1_temuan_id',$user_pic->id)
        }
        else
        {
            $rekomendasi=$datarekom[$temuan_id];
            // $rekomendasi=DataRekomendasi::where('id_temuan',$temuan_id)
            // ->where(function($query) use ($user_pic){
            //      $query->where('pic_1_temuan_id', $user_pic->id);
            //      $query->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,");
            //  })
            //  ->with('dtemuan')->get();
        }

        // return $arrayidtemuan;
        // $rekomendasi=DataRekomendasi::whereIn('id_temuan',$arrayidtemuan)->get();
        
        
        $drekomendasi=$drekom=$rkm_idx=array();
        foreach($rekomendasi as $k=>$v)
        {
            // if($v->dtemuan->id_lhp==$idlhp)
                $drekomendasi[$k]=$v;
                $drekom[$v->id]=$v;
                $rkm_idx[$v->id]=$k;

                // if($v->pic_1_temuan_id==$user_pic->id || $v->pic_2_temuan_id==$user_pic->id)
                // $dtemuan[]=$ddtem[$v->id_temuan];
                // else
                //     $dtemuan=array();
        }
        // return $drekomendasi[$rekom_idx];
        // return $temuan_idx;
        if($idrekom==null)
            $rrekom=isset($drekomendasi[$rekom_idx]) ? $drekomendasi[$rekom_idx] : array();
        else
        {
            $rrekom=isset($drekom[$idrekom]) ? $drekom[$idrekom] : array();
            $rekom_idx=$rkm_idx[$idrekom];
            // return $rkm_idx;
        }

        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-form')
                        ->with('rekomendasi',$rrekom)
                        // // ->with('rekomendasi',isset($drekom[$rekom_id]) ? $drekom[$rekom_id] : array())
                        ->with('temuan_id',$temuan_id)
                        ->with('data',$data)
                        ->with('rekom_id',$rekom_id)
                        ->with('temuan_idx',$temuan_idx)
                        ->with('rekom_idx',$rekom_idx)
                        ->with('dtemuan',$dtemuan)
                        ->with('idlhp',$idlhp)
                        ->with('drekomendasi',$drekomendasi)
                        ->with('temuan',isset($dtemuan[$temuan_idx]) ? $dtemuan[$temuan_idx] : array());
    }

    public function unitkerja_tindak_lanjut_simpan(Request $request)
    {
        // return $request->all();
        $rekom=DataRekomendasi::where('id',$request->rekomendasi_id)->with('dtemuan')->first();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        
        $tem=TindakLanjutTemuan::where('rekomendasi_id',$request->rekomendasi_id)->get();
        foreach($tem as $km=>$vm)
        {
            $vm->action_plan=$request->action_plan;
            $vm->save();
        }

        $tindaklanjut=new TindakLanjutTemuan;
        $tindaklanjut->lhp_id = $request->idlhp;
        $tindaklanjut->temuan_id = $request->temuan_id;
        $tindaklanjut->rekomendasi_id = $request->rekomendasi_id;
        $tindaklanjut->tindak_lanjut = $request->tindak_lanjut;
        $tindaklanjut->rincian = $request->jenis;
        $tindaklanjut->action_plan = $request->action_plan;
        $tindaklanjut->tgl_tindaklanjut = $request->tgl_tindak_lanjut;
        $tindaklanjut->status_tindaklanjut = str_slug('Create oleh Unit Kerja');
        $tindaklanjut->create_oleh_pic_unit = 1;
        if($user_pic)
        {
            if($rekom->pic_1_temuan_id==$user_pic->id)
                $tindaklanjut->pic_1_id = $user_pic->id;
            
            if($rekom->pic_2_temuan_id==$user_pic->id)
                $tindaklanjut->pic_2_id = $user_pic->id;
                // $tindaklanjut->pic_1_id = $rekom->pic_1_temuan_id;
                // $tindaklanjut->pic_2_id = $rekom->pic_2_temuan_id;
        }
        $sv=$tindaklanjut->save();

        $idtindaklanjut=$tindaklanjut->id;

        $dokumen=DokumenTindakLanjut::where('id_tindak_lanjut_temuan',$request->form_tl)->get();
        foreach($dokumen as $k=>$v)
        {
            $v->id_tindak_lanjut_temuan=$idtindaklanjut;
            $v->save();
        }
        // if($request->hasFile('dokumen_pendukung')){
        //     $file = $request->file('dokumen_pendukung');
        //     $filenameWithExt = $request->file('dokumen_pendukung')->getClientOriginalName();
        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('dokumen_pendukung')->getClientOriginalExtension();
        //     // $fileNameToStore = $filename.'_'.time().'.'.$extension;
        //     $fileNameToStore = time().'.'.$extension;
        //     $path = $request->file('dokumen_pendukung')->storeAs('public/dokumen',$fileNameToStore);

        //     $dokumen=new DokumenTindakLanjut;
        //     $dokumen->id_tindak_lanjut_temuan=$idtindaklanjut;
        //     $dokumen->nama_dokumen=$fileNameToStore;
        //     $dokumen->path=$path;
        //     $dokumen->save();
        // }

        $lhp=DaftarTemuan::find($request->idlhp);
        $tahun=$lhp->tahun_pemeriksa;
        if($sv)
        {
            return redirect('data-tindaklanjut-unitkerja/'.$tahun)
                ->with('success', 'Anda telah Berhasil Menambah data Tindak Lanjut ');
        }
        else
        {
            return redirect('data-tindaklanjut-unitkerja/'.$tahun)
                ->with('error', 'Menambah data Tindak Lanjut Gagal');
        }
    }
    public function unitkerja_tindak_lanjut_edit_simpan(Request $request)
    {
        // return $request->all();
        $idtl=$request->idtl;
        $tindaklanjut=TindakLanjutTemuan::find($idtl);
        $tindaklanjut->lhp_id = $request->idlhp;
        $tindaklanjut->temuan_id = $request->temuan_id;
        $tindaklanjut->rekomendasi_id = $request->rekomendasi_id;
        $tindaklanjut->tindak_lanjut = $request->tindak_lanjut;
        $tindaklanjut->rincian = $request->jenis;
        $tindaklanjut->action_plan = $request->action_plan;
        $tindaklanjut->tgl_tindaklanjut = $request->tgl_tindak_lanjut;
        $sv=$tindaklanjut->save();

        $idtindaklanjut=$tindaklanjut->id;

        if($request->hasFile('dokumen_pendukung')){
            $file = $request->file('dokumen_pendukung');
            $filenameWithExt = $request->file('dokumen_pendukung')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('dokumen_pendukung')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('dokumen_pendukung')->storeAs('public/dokumen',$fileNameToStore);

            $dokumen=DokumenTindakLanjut::where('id_tindak_lanjut_temuan',$idtl)->first();
            if($dokumen)
            {
                $dokumen->nama_dokumen=$fileNameToStore;
                $dokumen->path=$path;
                $dokumen->save();
            }
            else
            {
                $dokumen=new DokumenTindakLanjut;
                $dokumen->id_tindak_lanjut_temuan=$idtl;
                $dokumen->nama_dokumen=$fileNameToStore;
                $dokumen->path=$path;
                $dokumen->save();
            }
        }

        $lhp=DaftarTemuan::find($request->idlhp);
        $tahun=$lhp->tahun_pemeriksa;
        $data['idrekomendasi']=$request->rekomendasi_id;
        return $data;
        // if($sv)
        // {
        //     return redirect('data-tindaklanjut-unitkerja/'.$tahun)
        //         ->with('success', 'Anda telah Berhasil Menambah data Tindak Lanjut Untuk Nomor Rekomendasi '.$request->nomor_rekomendasi.'.');
        // }
        // else
        // {
        //     return redirect('data-tindaklanjut-unitkerja/'.$tahun)
        //         ->with('error', 'Menambah data Tindak Lanjut Untuk Nomor Rekomendasi '.$request->nomor_rekomendasi.' Gagal');
        // }
    }

    public function form_tindaklanjut_rincian($idrincian,$jenis)
    {
        $bank = BankList::all();
        if($jenis=='sewa')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-sewa')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='uangmuka')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-uangmuka')
                    ->with('idrincian',$idrincian)
                    ->with('bank', $bank)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='listrik')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-listrik')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='piutang')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-piutang')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('bank', $bank)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='piutangkaryawan')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-piutangkaryawan')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='hutangtitipan')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-hutangtitipan')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='penutupanrekening')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-penutupanrekening')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        elseif($jenis=='umum')
        {
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.pic-unit.form-umum')
                    ->with('idrincian',$idrincian)
                    ->with('bank', $bank)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        else if($jenis=='kontribusi'){
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.form-kontribusi')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
        else if($jenis=='nonsetoranperjanjiankerjasama'){
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranperjanjiankerjasama')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoran'){
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoran')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoranumum'){
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranumum')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $idform=abs(crc32(sha1(md5(rand()))));
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranpertanggungjawabanuangmuka')
                    ->with('idrincian',$idrincian)
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
    }
    public function list_tindaklanjut_rincian($idrincian,$jenis,$idtl=null, $totalNilai=-1)
    {
        $status_rekomendasi = StatusRekomendasi::all();
        if($jenis=='sewa')
        {
            $rincian=RincianSewa::find($idrincian);
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::find($idrincian);
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::find($idrincian);
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::find($idrincian);
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::find($idrincian);
        }
        elseif($jenis=='hutangtitipan')
        {
            $rincian=RincianHutangTitipan::find($idrincian);
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::find($idrincian);
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::find($idrincian);
        }
        else if($jenis=='kontribusi'){
            $rincian=RincianKontribusi::find($idrincian);
        }else if($jenis=='nonsetoranperjanjiankerjasama'){
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::find($idrincian);
        }else if($jenis=='nonsetoran'){
            $rincian=RincianNonSetoran::find($idrincian);
        }else if($jenis=='nonsetoranumum'){
            $rincian=RincianNonSetoranUmum::find($idrincian);
        }else if($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::find($idrincian);
        }

        $unitkerja=$rinciantindaklanjut=array();
        if($rincian)
        {
            $unitkerja=PICUnit::find($rincian->unit_kerja_id);
            $rinciantindaklanjut=TindakLanjutRincian::where('id_temuan',$rincian->id_temuan)
                    ->where('id_rekomendasi',$rincian->id_rekomendasi)
                    ->where('unit_kerja_id',($rincian ? $rincian->unit_kerja_id : 0))
                    ->where('jenis', $jenis)
                    ->join('bank', 'tindak_lanjut_rincian.bank_tujuan', '=', 'bank.id')
                    ->get(['tindak_lanjut_rincian.*', 'bank.bank as bank_tujuan_name']);

        }



        return view('backend.pages.data-lhp.rincian-table.table-rincian')
                    ->with('idrincian',$idrincian)
                    ->with('unitkerja',$unitkerja)
                    ->with('rinciantindaklanjut',$rinciantindaklanjut)
                    ->with('rincian',$rincian)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi', $status_rekomendasi)
                    ->with('totalnilai',$totalNilai);
    }

    public function update_status_rincian($idtindaklanjut, $status_rincian){
        $tindaklanjut = TindakLanjutRincian::find($idtindaklanjut);
        $tindaklanjut->status_rincian = $status_rincian;
        $tindaklanjut->save();
    }

    public function list_rincian_rekomendasi($idrekomendasi,$jenis)
    {
        
        $unitkerja=PICUnit::where('id_user',Auth::user()->id)->first();

        $rinciantindaklanjut=TindakLanjutRincian::where('id_rekomendasi',$idrekomendasi)
                // ->where('unit_kerja_id',($unitkerja ? $unitkerja->id : 0))
                ->get();

        return view('backend.pages.data-lhp.rincian-table.table-rincian')
                    // ->with('idrincian',$idrincian)
                    ->with('unitkerja',$unitkerja)
                    ->with('rinciantindaklanjut',$rinciantindaklanjut)
                    // ->with('rincian',$rincian)
                    ->with('jenis',$jenis);
    }

    public function hapus_rincian_jenis($idrincian,$jenis)
    {
        if($jenis=='sewa')
        {
            $rincian=RincianSewa::find($idrincian);
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::find($idrincian);
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::find($idrincian);
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::find($idrincian);
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::find($idrincian);
        }
        elseif($jenis=='hutangtitipan')
        {
            $rincian=RincianHutangTitipan::find($idrincian);
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::find($idrincian);
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::find($idrincian);
        }elseif($jenis=='kontribusi'){
            $rincian=RincianKontribusi::find($idrincian);
        }elseif($jenis=='nonsetoranperjanjiankerjasama'){
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::find($idrincian);
        }elseif($jenis=='nonsetoran'){
            $rincian=RincianNonSetoran::find($idrincian);
        }elseif($jenis=='nonsetoranumum'){
            $rincian=RincianNonSetoranUmum::find($idrincian);
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::find($idrincian);
        }

        $data['idtemuan']=$rincian->id_temuan;
        $data['rekom_id']=$rincian->id_rekomendasi;
        $rincian->delete();
        return $data;
    }

    public function simpan_tindaklanjut_rincian(Request $request)
    {
        $isUpdate = $request->isupdate;
        if($request->totalnilai!=-1){
            return response()->json(['errors'=>['Nilai melebihi total nilai.']]);
        }
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        // return $request->all();
        $idrincian=$request->idrincian;
        $jenis=$request->jenis;
        if($jenis=='sewa')
            $rincian=RincianSewa::find($idrincian);
        elseif($jenis=='uangmuka')
            $rincian=RincianUangMuka::find($idrincian);
        elseif($jenis=='listrik')
            $rincian=RincianListrik::find($idrincian);
        elseif($jenis=='piutang')
            $rincian=RincianPiutang::find($idrincian);
        elseif($jenis=='piutangkaryawan')
            $rincian=RincianPiutangKaryawan::find($idrincian);
        elseif($jenis=='hutangtitipan')
            $rincian=RincianHutangTitipan::find($idrincian);
        elseif($jenis=='penutupanrekening')
            $rincian=RincianPenutupanRekening::find($idrincian);
        elseif($jenis=='umum')
            $rincian=RincianUmum::find($idrincian);
        elseif($jenis=='kontribusi')
            $rincian=RincianKontribusi::find($idrincian);
        elseif($jenis=='nonsetoranperjanjiankerjasama')
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::find($idrincian);
        elseif($jenis=='nonsetoran')
            $rincian=RincianNonSetoran::find($idrincian);
        elseif($jenis=='nonsetoranumum')
            $rincian=RincianNonSetoranUmum::find($idrincian);
        elseif($jenis=='nonsetoranpertanggungjawabanuangmuka')
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::find($idrincian);
        
        $id_rekomendasi=$rincian->id_rekomendasi;

        $path='-';
        $documentJSON = [];
        if((int)$request->total_file > 0){
            for($i = 1; $i <= (int)$request->total_file; $i++){
                $idNamaFile = ('nama_file_'.$i);
                if($request->hasFile('add_dokumen_'.$i)){
                    $file = $request->file('add_dokumen_'.$i);
                    $extension = $request->file('add_dokumen_'.$i)->getClientOriginalExtension();
                    $filename = $request->$idNamaFile;
                    $fileNameToStore = $filename.'.'.$extension;
                    $path = $request->file('add_dokumen_'.$i)->storeAs('public/dokumen',$fileNameToStore);
                    $documentJSON[] = [
                        'file' => $path
                    ];
                }
            }
        }
        // if($request->hasFile('file_pendukung')){
        //     $file = $request->file('file_pendukung');
        //     // $new_name = rand() . '.' . $file->getClientOriginalExtension(); 
        //     $filenameWithExt = $request->file('file_pendukung')->getClientOriginalName();
        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('file_pendukung')->getClientOriginalExtension();
        //     $fileNameToStore = time().'.'.$extension;
        //     // $fileNameToStore = rand() . '.' . $file->getClientOriginalExtension(); 
        //     $path = $request->file('file_pendukung')->storeAs('public/dokumen',$fileNameToStore);

        //     // $dokumen=new DokumenTindakLanjut;
        //     // $dokumen->id_tindak_lanjut_temuan=$tindak_id;
        //     // $dokumen->nama_dokumen=$fileNameToStore;
        //     // $dokumen->path=$path;
        //     // $dokumen->save();
        // }
        $rekommm=DataRekomendasi::where('id',$id_rekomendasi)->with('dtemuan')->first();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        
        $insert=new TindakLanjutRincian;
        if($isUpdate == '1'){
            $insert=TindakLanjutRincian::find($request->idtindaklanjut);
            if($documentJSON)
                $insert->dokumen_pendukung = json_encode($documentJSON);
        }else{
            $insert->dokumen_pendukung = json_encode($documentJSON);
        }
        
        $insert->id_temuan = $rincian->id_temuan;
        $insert->id_rekomendasi = $rincian->id_rekomendasi;
        $insert->unit_kerja_id = $rincian->unit_kerja_id;
        $insert->id_tindak_lanjut = $request->idform;
        $insert->jenis = $request->jenis;

        if($user_pic)
        {
            // if($rekommm->pic_1_temuan_id==$user_pic->id)
            //     $insert->pic_1_id = $rekommm->pic_1_temuan_id;
            
            // if($rekommm->pic_2_temuan_id==$user_pic->id)
            //     $insert->pic_2_id = $rekommm->pic_2_temuan_id;
                
                // $insert->pic_1_id = $rekommm->pic_1_temuan_id;
                // $insert->pic_2_id = $rekommm->pic_2_temuan_id;
        }

        if($jenis=='kontribusi' || $jenis=='sewa' || $jenis=='listrik' || $jenis=='piutang' || $jenis=='piutangkaryawan' || $jenis=='hutangtitipan')
        {
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->nilai = str_replace('.','',$request->nilai);
            $insert->tanggal = $request->tanggal;
            $insert->jenis_setoran = $request->jenis_setoran;
            $insert->bank_tujuan = $request->bank_tujuan;
            $insert->no_referensi = $request->no_ref;
            $insert->jenis_rekening = $request->jenis_rekening;
        }

        if($jenis=='uangmuka')
        {
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->nilai = str_replace('.','',$request->nilai);
            $insert->tanggal = $request->tanggal;
            $insert->jenis_setoran = $request->jenis_setoran;
            $insert->bank_tujuan = $request->bank_tujuan;
            $insert->no_referensi = $request->no_ref;
            $insert->jenis_rekening = $request->jenis_rekening;
        }

        if($jenis=='penutupanrekening')
        {
            $insert->tanggal = $request->tanggal;
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->tanggal_penutupan = $request->tanggal_penutupan_rekening;
            $insert->saldo_akhir = str_replace('.','',$request->saldo_akhir);
            $insert->no_rek_pemindah_saldo = $request->no_rekening_pemindahan_saldo;
            $insert->nama_rekening_pemindah_saldo = $request->nama_rekening_pemindahan_saldo;
        }

        if($jenis=='umum')
        {
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->nilai = str_replace('.','',$request->nilai);
            $insert->tanggal = $request->tanggal;
            $insert->jenis_setoran = $request->jenis_setoran;
            $insert->bank_tujuan = $request->bank_tujuan;
            $insert->no_referensi = $request->no_ref;
            $insert->jenis_rekening = $request->jenis_rekening;
        }
        if($jenis == 'nonsetoranperjanjiankerjasama'){
            $insert->tanggal = $request->tanggal;
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->no_pks = $request->no_pks;
            $insert->tanggal_pks = $request->tanggal_pks;
            $insert->periode_pks = $request->periode_perpanjangan_pks;
        }

        if($jenis == 'nonsetoran' || $jenis == 'nonsetoranpertanggungjawabanuangmuka' || $jenis == 'nonsetoranumum'){
            $insert->tanggal = $request->tanggal;
            $insert->tindak_lanjut_rincian = $request->tindak_lanjut;
            $insert->nilai = str_replace('.','',$request->nilai);
        }

        $insert->save();
        
        $rincian->id_tindak_lanjut=$insert->id;
        $rincian->save();

        $data['jenis']=$insert->jenis;
        $data['temuan_id']=$insert->id_temuan;
        $data['rekomendasi_id']=$insert->id_rekomendasi;
        $data['idrincian']=$idrincian;
        
        return $data;
    }

    public function table_data_tindaklanjut($idrekomendasi)
    {
        $dokumen=DokumenTindakLanjut::all();
        $dok=array();
        foreach($dokumen as $k=>$v)
        {
            $dok[$v->id_tindak_lanjut_temuan][]=$v;
        }
        $tl=TindakLanjutTemuan::where('rekomendasi_id',$idrekomendasi)->with('drekomendasi')->get();
        $rekom=DataRekomendasi::find($idrekomendasi);
        $arrayidtl=array();
        foreach($tl as $k=>$v)
        {
            $arrayidtl[$v->id]=$v->id;
        }
        $jenis=$rekom->rincian;
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        $where=['unit_kerja_id'=>$user_pic->id,'id_rekomendasi'=>$idrekomendasi];

        $rincian=array();
        if($jenis=='sewa')
            $rincian=RincianSewa::where($where)->get();
        elseif($jenis=='uangmuka')
            $rincian=RincianUangMuka::where($where)->get();
        elseif($jenis=='listrik')
            $rincian=RincianListrik::where($where)->get();
        elseif($jenis=='piutang')
            $rincian=RincianPiutang::where($where)->get();
        elseif($jenis=='piutangkaryawan')
            $rincian=RincianPiutangKaryawan::where($where)->get();
        elseif($jenis=='hutangtitipan')
            $rincian=RincianHutangTitipan::where($where)->get();
        elseif($jenis=='penutupanrekening')
            $rincian=RincianPenutupanRekening::where($where)->get();
        elseif($jenis=='umum')
            $rincian=RincianUmum::where($where)->get();
        elseif($jenis=='kontribusi')
            $rincian=RincianKontribusi::where($where)->get();
        elseif($jenis=='nonsetoranperjanjiankerjasama')
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where($where)->get();
        elseif($jenis=='nonsetoran')
            $rincian=RincianNonSetoran::where($where)->get();
        elseif($jenis=='nonsetoranumum')
            $rincian=RincianNonSetoranUmum::where($where)->get();
        elseif($jenis=='nonsetoranpertanggungjawabanuangmuka')
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where($where)->get();

        $tlrincian=TindakLanjutRincian::where('id_rekomendasi',$idrekomendasi)->get();
        $tindaklanjut_rincian=array();
        foreach($tlrincian as $k=>$v)
        {
            $tindaklanjut_rincian[$v->id_tindak_lanjut][]=$v;
        }
        // return $rincian;
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-table')
                ->with('user_pic',$user_pic)
                ->with('rincian',$rincian)
                ->with('tindaklanjut_rincian',$tindaklanjut_rincian)
                ->with('tindaklanjut',$tl)
                ->with('dok',$dok)
                ->with('idrekomendasi',$idrekomendasi);
    }

    function hapus_tindak_lanjut($idtl)
    {
        $tl=TindakLanjutTemuan::find($idtl);
        $del=$tl->delete();

        DokumenTindakLanjut::where('id_tindak_lanjut_temuan',$idtl)->first()->delete();

        if($del)
            echo 1;
        else
            echo 0;
    }

    function hapus_tindak_lanjut_rincian($idrincian){
        $tlr = TindakLanjutRincian::find($idrincian);
        $del = $tlr->delete();

        if($del)
            echo 1;
        else
            echo 2;
    }

    function get_tindak_lanjut_rincian($idrincian){
        $rinciantindaklanjut=TindakLanjutRincian::where('tindak_lanjut_rincian.id',$idrincian)
                    ->join('bank', 'tindak_lanjut_rincian.bank_tujuan', '=', 'bank.id')
                    ->get(['tindak_lanjut_rincian.*', 'bank.bank as bank_tujuan_name']);
        return json_encode($rinciantindaklanjut);
    }

    function list_rincian($idrekomendasi,$idunitkerja,$idtl)
    {
        $rekom=DataRekomendasi::find($idrekomendasi);
        $jenis=$rekom->rincian;
        $idtemuan=$rekom->id_temuan;
        $where=['unit_kerja_id'=>$idunitkerja,'id_rekomendasi'=>$idrekomendasi];

        if($jenis=='sewa')
        {
            $rincian=RincianSewa::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-sewa')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-uangmuka')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-listrik')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-piutang')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-piutangkaryawan')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='hutangtitipan')
        {
            $rincian=RincianHutangTitipan::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-hutangtitipan')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-penutupanrekening')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-umum')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='kontribusi'){
            $rincian=RincianKontribusi::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-kontribusi')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }elseif($jenis=='nonsetoranperjanjiankerjasama'){
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranperjanjiankerjasama')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='nonsetoran'){
            $rincian=RincianNonSetoran::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoran')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='nonsetoranumum'){
            $rincian=RincianNonSetoran::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranumum')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $rincian=RincianNonSetoran::where($where)->get();
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranpertanggungjawabanuangmuka')
                    ->with('rincian',$rincian)
                    ->with('idtl',$idtl)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
    }

    public function detail_tindaklanjut_junior($idrekomendasi)
    {
        $rekom=DataRekomendasi::where('id',$idrekomendasi)->with('picunit1')->with('picunit2')->first();
        // return $rekom;
        $status=StatusRekomendasi::all();
        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;
        }

        $d_tindaklanjut=TindakLanjutTemuan::where('rekomendasi_id',$idrekomendasi)->orderBy('tgl_tindaklanjut')->get();
        $pic1=$pic2=$arrayidtl=array();
        foreach($d_tindaklanjut as $k=>$v)
        {
            if($v->pic_1_id!=0)
            {
                $pic1['tindak_lanjut'][]=$v;
                $pic1['action_plan'][]=$v->action_plan;

            }
            if($v->pic_2_id!=0)
            {
                $pic2['tindak_lanjut'][]=$v;
                $pic2['action_plan'][]=$v->action_plan;
            }
            $arrayidtl[$v->id]=$v->id;
        }

        $dok=DokumenTindakLanjut::whereIn('id_tindak_lanjut_temuan',$arrayidtl)->get();
        $dokumen=array();
        foreach($dok as $k=>$v)
        {
            $dokumen[$v->id_tindak_lanjut_temuan]=$v;
        }

        if(Auth::user()->level=='auditor-senior')
        {
            return view('backend.pages.data-lhp.tindak-lanjut.senior.tl.tindaklanjut-detail-form')
                ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('status',$status)
                ->with('id_rekomendasi',$idrekomendasi);
        }
        elseif(Auth::user()->level=='auditor-junior')
        {
            return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-detail-form')
                ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('status',$status)
                ->with('id_rekomendasi',$idrekomendasi);
        }
        elseif(Auth::user()->level=='super-user')
        {
            // return view('backend.pages.data-lhp.super-user.tindaklanjut-detail-form')
            return view('backend.pages.data-lhp.tindak-lanjut.senior.tl.tindaklanjut-detail-form')
                ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('status',$status)
                ->with('id_rekomendasi',$idrekomendasi);
        }
    }
    public function detail_tindaklanjut_picunit1($idrekomendasi)
    {
        $rekom=DataRekomendasi::where('id',$idrekomendasi)->with('picunit1')->with('picunit2')->first();
        // return $rekom;
        $status=StatusRekomendasi::all();
        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;
        }

        $d_tindaklanjut=TindakLanjutTemuan::where('rekomendasi_id',$idrekomendasi)->orderBy('tgl_tindaklanjut')->get();
        $pic1=$pic2=$arrayidtl=array();
        foreach($d_tindaklanjut as $k=>$v)
        {
            if($v->pic_1_id!=0)
            {
                $pic1['tindak_lanjut'][]=$v;
                $pic1['action_plan'][]=$v->action_plan;

            }
            if($v->pic_2_id!=0)
            {
                $pic2['tindak_lanjut'][]=$v;
                $pic2['action_plan'][]=$v->action_plan;
            }
            $arrayidtl[$v->id]=$v->id;
        }

        $dok=DokumenTindakLanjut::whereIn('id_tindak_lanjut_temuan',$arrayidtl)->get();
        $dokumen=array();
        foreach($dok as $k=>$v)
        {
            $dokumen[$v->id_tindak_lanjut_temuan]=$v;
        }
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-detail-form')
                ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('status',$status)
                ->with('id_rekomendasi',$idrekomendasi);
    }

    public function review_pic1_simpan(Request $request)
    {
        // return $request->all();
        $tahun=$request->tahun;
        $data['monev']=$monev=$request->catatan_monev;
        $data['idrekom']=$idrekom=$request->idrekomendasi;
        $data['tgl']=$tgl=date('Y-m-d',strtotime($request->tgl_selesai));

        $path='';
        if($request->hasFile('file_pendukung')){
            $file = $request->file('file_pendukung');
            $filenameWithExt = $request->file('file_pendukung')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file_pendukung')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('file_pendukung')->storeAs('public/dokumen',$fileNameToStore);
        }

        $rekom=DataRekomendasi::find($idrekom);
        $rekom->review_monev=$monev;
        $rekom->tanggal_penyelesaian=$tgl;
        $rekom->rangkuman_rekomendasi=$request->txt_rangkuman_rekomendasi;

        if($rekom!='')
            $rekom->file_pendukung=$path;

        $save=$rekom->save();
        if($save)
            return redirect('data-tindaklanjut-unitkerja/'.$tahun)->with('success','Data Review dan Rangkuman Berhasil Di Simpan');
        else
            return redirect('data-tindaklanjut-unitkerja/'.$tahun)->with('error','Data Review dan Rangkuman Gagal Di Simpan');
    }

    public function tindaklanjut_junior_simpan(Request $request)
    {
        // return $request->all();
        $tahun=$request->tahun;
        $rekom=DataRekomendasi::find($request->idrekomendasi);
        $rekom->review_spi=$request->review_spi;
        $rekom->status_rekomendasi_id=$request->status_rekomendasi;
        $rekom->published=$request->publish;
        $save=$rekom->save();

        if($request->publish==0)
        {
            if($save)
                return redirect('data-tindaklanjut/'.$tahun)->with('success','Data Review Berhasil Di Simpan Sebagai Draft');
            else
                return redirect('data-tindaklanjut/'.$tahun)->with('error','Data Review Gagal Di Simpan Sebagai Draft');
        }
        else
        {
            if($save)
                return redirect('data-tindaklanjut/'.$tahun)->with('success','Data Review Berhasil Di Simpan dan Di Publish Ke Auditor Senior');
            else
                return redirect('data-tindaklanjut/'.$tahun)->with('error','Data Review Gagal Di Simpan');
        }
    }

    public function detail_tl_rincian($idrekomendasi)
    {
        $dokumen=DokumenTindakLanjut::all();
        $dok=array();
        foreach($dokumen as $k=>$v)
        {
            $dok[$v->id_tindak_lanjut_temuan]=$v;
        }
        $tl=TindakLanjutTemuan::where('rekomendasi_id',$idrekomendasi)->with('drekomendasi')->with('pic1')->with('pic2')->get();
        $rekom=DataRekomendasi::find($idrekomendasi);
        $arrayidtl=array();
        foreach($tl as $k=>$v)
        {
            $arrayidtl[$v->id]=$v->id;
        }
        $jenis=$rekom->rincian;
        $where=['id_rekomendasi'=>$idrekomendasi];

        $rincian=array();
        if($jenis=='sewa')
            $rincian=RincianSewa::where($where)->get();
        elseif($jenis=='uangmuka')
            $rincian=RincianUangMuka::where($where)->get();
        elseif($jenis=='listrik')
            $rincian=RincianListrik::where($where)->get();
        elseif($jenis=='piutang')
            $rincian=RincianPiutang::where($where)->get();
        elseif($jenis=='piutangkaryawan')
            $rincian=RincianPiutangKaryawan::where($where)->get();
        elseif($jenis=='hutangtitipan')
            $rincian=RincianHutangTitipan::where($where)->get();
        elseif($jenis=='penutupanrekening')
            $rincian=RincianPenutupanRekening::where($where)->get();
        elseif($jenis=='umum')
            $rincian=RincianUmum::where($where)->get();
        elseif($jenis=='kontribusi')
            $rincian=RincianKontribusi::where($where)->get();
        elseif($jenis=='nonsetoranperjanjiankerjasama')
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where($where)->get();
        elseif($jenis=='nonsetoran')
            $rincian=RincianNonSetoran::where($where)->get();
        elseif($jenis=='nonsetoranumum')
            $rincian=RincianNonSetoranUmum::where($where)->get();
        elseif($jenis=='nonsetoranpertanggungjawabanuangmuka')
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where($where)->get();

        $tlrincian=TindakLanjutRincian::where('id_rekomendasi',$idrekomendasi)->get();
        $tindaklanjut_rincian=array();
        foreach($tlrincian as $k=>$v)
        {
            $tindaklanjut_rincian[$v->id_tindak_lanjut][]=$v;
        }
        // return $rincian;
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-lhp-table')
                ->with('rincian',$rincian)
                ->with('tindaklanjut_rincian',$tindaklanjut_rincian)
                ->with('tindaklanjut',$tl)
                ->with('dok',$dok)
                ->with('idrekomendasi',$idrekomendasi);
    }

     public function ajaxFiles(Request $request)
    {

        //  
        // return $request->all();
        // $file=$request->file;
        // $newpath = $file->store('file/pengajuan_lelang');
        $idformtl = $request->idformtl;
        $idlhp = $request->idlhp;
        $temuan_id = $request->temuan_id;
        $rekomendasi_id = $request->rekomendasi_id;
        $nama = $request->namafile;

        $file = $request->file('file');
        $filenameWithExt = $request->file('file')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileNameToStore = time().'.'.$extension;
        $path = $request->file('file')->storeAs('public/dokumen',$fileNameToStore);

        if($path)
        {
            $dokumen=new DokumenTindakLanjut;
            $dokumen->id_tindak_lanjut_temuan=$idformtl;
            $dokumen->nama_dokumen=$nama;
            $dokumen->path=$path;
            $dokumen->save();
            return $path;
        }
        else
        {
            return array(
                    'fail' => true,
                    'errors' => 'Upload Gagal'
                );
        }
    }

    public function div_editor($idrekom,$idtl)
    {
        $rekom=DataRekomendasi::find($idrekom);
        $form='<div class="form-group" style="margin:0px;padding:0px;margin-bottom:10px;">
                        <div class="col-sm-12">
                            <input type="hidden" id="idtl_catatan_monev" name="idtl_catatan_monev" value="'.$idtl.'">
                            <input type="hidden" id="idrekom_catatan_monev" name="idrekom_catatan_monev" value="'.$idrekom.'">
                            <textarea class="fz11" name="catatan_monev" placeholder="" id="catatan_monev_pic"  style="padding:0px !important;border:0px;border-bottom:1px dotted #aaa;width:100%;min-height:50px"></textarea>
                        </div>
                    </div>';
        return $form;
    }

    public function simpan_monev_pic(Request $request)
    {
        // return $request->all();
        $idtl_catatan_monev=$request->idtl_catatan_monev;
        $idrekom_catatan_monev=$request->idrekom_catatan_monev;
        $catatan_monev=$request->catatan_monev;

        $tindaklanjut=TindakLanjutTemuan::where('id',$idtl_catatan_monev)->with('drekomendasi')->first();
        $catatan=new CatatanMonev;
        $catatan->id_tindaklanjut=$idtl_catatan_monev;
        $catatan->id_rekomendasi=$idrekom_catatan_monev;
        $catatan->catatan_monev=$catatan_monev;
        $catatan->pic_2=$tindaklanjut->pic_2_id;
        $catatan->pic_1=$tindaklanjut->drekomendasi->pic_1_temuan_id;
        $s=$catatan->save();
        if($s)
            echo 1;
        else
            echo 0;
    }

    public function detail_catatan($id)
    {
        $cat=CatatanMonev::find($id);
        return $cat->catatan_monev;
    }

    public function jumlah_rincian($temuan_id,$rekom_id)
    {
        $tindaklanjut=TindakLanjutRincian::where('id_temuan',$temuan_id)->where('id_rekomendasi',$rekom_id)->get();
        return $tindaklanjut->count();
    }
}
