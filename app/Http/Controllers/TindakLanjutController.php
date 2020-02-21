<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;
use App\Models\DaftarTemuan;
use App\Models\DataRekomendasi;
use App\Models\DataTemuan;
use App\Models\Pemeriksa;
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
    public function simpan(Request $request,$idrekom)
    {
        // dd($request);
        $rekom=DataRekomendasi::where('id',$idrekom)->with('dtemuan')->first();
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
        $tindak->pic_1_id = $rekom->pic_1_temuan_id;
        $tindak->pic_2_id = $rekom->pic_2_temuan_id;
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
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $tahun=($request->tahun ? $request->tahun : date('Y'));
        $rekomid=($request->rekomid ? $request->rekomid : -1);
        $temuanid=($request->temuanid ? $request->temuanid : -1);
        $statusrekom=($request->statusrekom ? $request->statusrekom : -1);

        $temuan=$rekomendasi=$idtemuanarray=array();
        $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();
        
        $idlhparray=array();
        foreach($datalhp as $k=>$v)
        {
            $idlhparray[$v->id]=$v->id;
        }

        if(count($idlhparray)!=0)
        {
            $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->get();
            foreach($temuan as $kk=>$vv)
            {
                $idtemuanarray[]=$vv->id;
            }
        }

        if(count($idtemuanarray)!=0)
        {
            if($statusrekom==null)
                $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->get();
            else
                $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->where('status_rekomendasi_id',$statusrekom)->get();

            foreach($rekom as $k=>$v)
            {
                $rekomendasi[$v->id_temuan][]=$v;
            }
        }
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut-data')
                        ->with('rekomendasi',$rekomendasi)
                        ->with('idtemuanarray',$idtemuanarray)
                        ->with('temuan',$temuan);
    }

    public function junior_index($tahun=null,$rekomid=null,$temuanid=null)
    {
        $tahun=($tahun==null ? date('Y') : $tahun);
        $rekomid=($rekomid==null ? -1 : $rekomid);
        $temuanid=($temuanid==null ? -1 : $temuanid);

        // $temuan=($temuanid) ? DataTemuan::find($temuanid) : array();
        
        // if($temuan && $rekomid)
        //     $tindaklanjut=TindakLanjutTemuan::where('temuan_id',$temuanid)->where('rekomendasi_id',$rekomid)->get();
        // elseif($temuan)
        // $tindaklanjut=TindakLanjutTemuan::with('lhp')->where('temuan_id',$temuanid)->get();
        // $data=array();
        // if($temuan)
        // {
        //     $data=DaftarTemuan::selectRaw('*, daftar_lhp.id as id_lhp')
        //             ->where('daftar_lhp.id',$temuan->id_lhp)
        //             ->with('dpemeriksa')->first();
        // }
        $pemeriksa=Pemeriksa::orderBy('code')->get();
        $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();
        $idlhparray=array();
        foreach($datalhp as $k=>$v)
        {
            $idlhparray[$v->id]=$v->id;
        }
        $temuan=$rekomendasi=$idtemuanarray=array();
        if(count($idlhparray)!=0)
        {
            $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->get();
            foreach($temuan as $kk=>$vv)
            {
                $idtemuanarray[]=$vv->id;
            }
        }

        if(count($idtemuanarray)!=0)
        {
            $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->get();
            foreach($rekom as $k=>$v)
            {
                $rekomendasi[$v->id_temuan][]=$v;
            }
        }
        return view('backend.pages.data-lhp.auditor-junior.tindaklanjut')
                ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('idlhparray',$idlhparray)
                ->with('datalhp',$datalhp)
                ->with('pemeriksa',$pemeriksa)
                ->with('rekomendasi',$rekomendasi)
                ->with('temuan',$temuan)
                ->with('temuanid',$temuanid);
    }
}
