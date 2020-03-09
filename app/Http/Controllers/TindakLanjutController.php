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
use App\Models\StatusRekomendasi;
use Auth;
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
            $wh['data_rekomendasi.id']=$request->rekomid;

        if($request->temuan_id!='')
            $wh['data_temuan.id']=$request->temuan_id;

        if($request->statusrekom!='')
            $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekom;

        if($request->pemeriksa!='')
            $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;

        $pemeriksaa=Pemeriksa::orderBy('code')->get();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();


        if(count($wh)!=0)
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                    ->where('daftar_lhp.user_input_id',Auth::user()->id)
                                    ->where($wh)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
        }
        else
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                    ->where('daftar_lhp.status_lhp','Publish LHP')
                                    ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                    ->where('daftar_lhp.user_input_id',Auth::user()->id)
                                    ->whereNull('data_rekomendasi.deleted_at')
                                    ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                    ->get();
        }

        $lhp=$temuan=$rekomendasi=$dt=array();
        foreach($alldata as $k=>$v)
        {
            // if(betweendate($v->tanggal_lhp,$t_awal,$t_akhir))
            // {
                $lhp[$v->id_lhp]=$v;
                $temuan[]=$v;
                $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            // }
            // $dt['cek'][]=$v->tanggal_lhp;
            // $dt['awal'][]=$t_awal;
            // $dt['akhir'][]=$t_akhir;
            $dt['between'][]=betweendate($v->tanggal_lhp,$t_awal,$t_akhir);
        }
        // return $temuan;
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-data')
                ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('temuanid',$temuan_id)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksaa)
                ->with('temuan',$alldata);

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

    public function junior_index($tahun=null,$rekomid=null,$temuanid=null)
    {
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


        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where('daftar_lhp.user_input_id',Auth::user()->id)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();

        $lhp=$temuan=$rekomendasi=$arrayrekomid=array();
        foreach($alldata as $k=>$v)
        {
            $lhp[$v->id_lhp]=$v;
            $temuan[$v->id_temuan]=$v;
            $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
        }

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $tindaklanjut;
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut')
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

        $rinc['sewa']=RincianSewa::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['uangmuka']=RincianUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['listrik']=RincianListrik::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutang']=RincianPiutang::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutangkary']=RincianPiutangKaryawan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['titipan']=RincianHutangTitipan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['penutupanrekening']=RincianPenutupanRekening::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['umum']=RincianUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();

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
        // return $tindaklanjut;
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut')
                ->with('rincian',$rincian)
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
        $temuanid=($request->temuanid ? $request->temuanid : -1);
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
        if($request->rekomid!='')
            $wh['data_rekomendasi.id']=$request->rekomid;

        if($request->temuan_id!='')
            $wh['data_temuan.id']=$request->temuan_id;

        if($request->statusrekom!='')
            $wh['data_rekomendasi.status_rekomendasi_id']=$request->statusrekom;

        if($request->pemeriksa!='')
            $wh['daftar_lhp.pemeriksa_id']=$request->pemeriksa;

        // return $wh;
        $pemeriksaa=Pemeriksa::orderBy('code')->get();
        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                                ->where($wh)
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

        $rinc['sewa']=RincianSewa::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['uangmuka']=RincianUangMuka::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['listrik']=RincianListrik::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutang']=RincianPiutang::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['piutangkary']=RincianPiutangKaryawan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['titipan']=RincianHutangTitipan::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['penutupanrekening']=RincianPenutupanRekening::whereIn('id_rekomendasi',$arrayrekomid)->get();
        $rinc['umum']=RincianUmum::whereIn('id_rekomendasi',$arrayrekomid)->get();

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
                $temuan[]=$v;
                $rekomendasi[$v->id_temuan][$v->id_rekom]=$v;
            // }
            // $dt['cek'][]=$v->tanggal_lhp;
            // $dt['awal'][]=$t_awal;
            // $dt['akhir'][]=$t_akhir;

        }
        // return $temuan;
                
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-list')
               ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('lhp',$lhp)
                ->with('user_pic',$user_pic)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksaa)
                ->with('temuan',$temuan);
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
        $data=DaftarTemuan::find($idlhp);

        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();

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
                        
        $temuan=DataTemuan::where('id_lhp',$idlhp)->get();
        // $temuan=DataTemuan::where('pic_temuan_id',$user_pic->id)->get();
        // $temuan=DataTemuan::all();
        $arrayidtemuan=$dtemuan=array();
        foreach($temuan as $k=>$v)
        {
            // if($idlhp==$v->id_lhp)
            $arrayidtemuan[$v->id]=$v->id;   
            $dtemuan[$k]=$v;
        }

        $rekomendasi=DataRekomendasi::where('id_temuan',$temuan_id)
            ->where(function($query) use ($user_pic){
                 $query->where('pic_1_temuan_id', $user_pic->id);
                 $query->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,");
             })
             ->with('dtemuan')->get();
        // ->where('pic_1_temuan_id',$user_pic->id)
        if($temuan_idx!=0)
        {
            $dtem=$dtemuan[$temuan_idx];
            $rekomendasi=DataRekomendasi::where('id_temuan',$dtem->id)
                ->where(function($query) use ($user_pic){
                    $query->where('pic_1_temuan_id', $user_pic->id);
                    $query->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,");
                })->with('dtemuan')->get();
            // ->where('pic_1_temuan_id',$user_pic->id)
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
        }
        // return $drekomendasi[$rekom_idx];
        // return $drekomendasi;
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
        
        $tindaklanjut=new TindakLanjutTemuan;
        $tindaklanjut->lhp_id = $request->idlhp;
        $tindaklanjut->temuan_id = $request->temuan_id;
        $tindaklanjut->rekomendasi_id = $request->rekomendasi_id;
        $tindaklanjut->tindak_lanjut = $request->tindak_lanjut;
        $tindaklanjut->rincian = $request->jenis;
        $tindaklanjut->action_plan = $request->action_plan;
        $tindaklanjut->tgl_tindaklanjut = $request->tgl_tindak_lanjut;
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

        if($request->hasFile('dokumen_pendukung')){
            $file = $request->file('dokumen_pendukung');
            $filenameWithExt = $request->file('dokumen_pendukung')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('dokumen_pendukung')->getClientOriginalExtension();
            // $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('dokumen_pendukung')->storeAs('public/dokumen',$fileNameToStore);

            $dokumen=new DokumenTindakLanjut;
            $dokumen->id_tindak_lanjut_temuan=$idtindaklanjut;
            $dokumen->nama_dokumen=$fileNameToStore;
            $dokumen->path=$path;
            $dokumen->save();
        }

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
                    ->with('idform',$idform)
                    ->with('jenis',$jenis);
        }
    }
    public function list_tindaklanjut_rincian($idrincian,$jenis,$idtl=null)
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
        }

        $unitkerja=$rinciantindaklanjut=array();
        if($rincian)
        {
            $unitkerja=PICUnit::find($rincian->unit_kerja_id);
            $rinciantindaklanjut=TindakLanjutRincian::where('id_temuan',$rincian->id_temuan)
                    ->where('id_rekomendasi',$rincian->id_rekomendasi)
                    ->where('unit_kerja_id',($rincian ? $rincian->unit_kerja_id : 0))
                    ->get();

        }



        return view('backend.pages.data-lhp.rincian-table.table-rincian')
                    ->with('idrincian',$idrincian)
                    ->with('unitkerja',$unitkerja)
                    ->with('rinciantindaklanjut',$rinciantindaklanjut)
                    ->with('rincian',$rincian)
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
        }

        $data['idtemuan']=$rincian->id_temuan;
        $data['rekom_id']=$rincian->id_rekomendasi;
        $rincian->delete();
        return $data;
    }

    public function simpan_tindaklanjut_rincian(Request $request)
    {
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

        $rincian->id_tindak_lanjut=$request->idform;
        $rincian->save();

        $path='-';
        if($request->hasFile('file_pendukung')){
            $file = $request->file('file_pendukung');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension(); 
            $filenameWithExt = $request->file('file_pendukung')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file_pendukung')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            // $fileNameToStore = rand() . '.' . $file->getClientOriginalExtension(); 
            $path = $request->file('file_pendukung')->storeAs('public/dokumen',$fileNameToStore);

            // $dokumen=new DokumenTindakLanjut;
            // $dokumen->id_tindak_lanjut_temuan=$tindak_id;
            // $dokumen->nama_dokumen=$fileNameToStore;
            // $dokumen->path=$path;
            // $dokumen->save();
        }
        $rekommm=DataRekomendasi::where('id',$request->id_rekomendasi)->with('dtemuan')->first();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        
        $insert=new TindakLanjutRincian;
        $insert->id_temuan = $rincian->id_temuan;
        $insert->id_rekomendasi = $rincian->id_rekomendasi;
        $insert->unit_kerja_id = $rincian->unit_kerja_id;
        $insert->id_tindak_lanjut = $request->idform;
        $insert->dokumen_pendukung = $path;

        if($user_pic)
        {
            if($rekommm->pic_1_temuan_id==$user_pic->id)
                $insert->pic_1_id = $rekom->pic_1_temuan_id;
            
            if($rekommm->pic_2_temuan_id==$user_pic->id)
                $insert->pic_2_id = $rekom->pic_2_temuan_id;
                
                // $insert->pic_1_id = $rekommm->pic_1_temuan_id;
                // $insert->pic_2_id = $rekommm->pic_2_temuan_id;
        }

        if($jenis=='kontribusi' || $jenis=='sewa' || $jenis=='listrik' || $jenis=='piutang' || $jenis=='piutangkaryawan' || $jenis=='hutangtitipan')
        {
            $insert->jenis = $request->jenis;
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

            $insert->nama_bank = $request->nama_bank;
            $insert->nomor_rekening = $request->nomor_rekening;
            $insert->nama_rekening = $request->nama_rekening;
            $insert->jenis_rekening = $request->jenis_rekening;
            $insert->saldo_akhir = str_replace('.','',$request->saldo_akhir);
            
        }

        if($jenis=='umum')
        {
            $insert->jumlah_rekomendasi = str_replace('.','',$request->jumlah_rekomendasi);
            $insert->dokumen_pendukung = $request->dokumen_pendukung;
            $insert->keterangan = $request->keterangan;
        }
        $insert->save();

        $data['jenis']=$insert->jenis;
        $data['temuan_id']=$insert->id_temuan;
        $data['rekomendasi_id']=$insert->id_rekomendasi;

        
        return $data;
    }

    public function table_data_tindaklanjut($idrekomendasi)
    {
        $dokumen=DokumenTindakLanjut::all();
        $dok=array();
        foreach($dokumen as $k=>$v)
        {
            $dok[$v->id_tindak_lanjut_temuan]=$v;
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
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-detail-form')
                ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('status',$status)
                ->with('id_rekomendasi',$idrekomendasi);
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
}
