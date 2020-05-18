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
use Auth;
class AuditorSeniorController extends Controller
{
    public function tindaklanjut_index($tahun=null,$rekomid=null,$temuanid=null)
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

        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                    ->where('daftar_lhp.status_lhp','Publish LHP')
                    ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                    ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
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
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        return view('backend.pages.data-lhp.tindak-lanjut.senior.tl.index')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
    }

    public function tindaklanjut_list(Request $request)
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

        if(count($wh)!=0)
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                ->where('daftar_lhp.status_lhp','Publish LHP')
                ->where($wh)
                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                ->whereNull('data_rekomendasi.deleted_at')
                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                ->get();
        }
        else{
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
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        return view('backend.pages.data-lhp.tindak-lanjut.senior.tl.list')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuanid',$temuan_id)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
    }

    public function tindaklanjut_senior_simpan(Request $request)
    {
        // return $request->all();
        $tahun=$request->tahun;
        $rekom=DataRekomendasi::find($request->idrekomendasi);
        $rekom->review_auditor=$request->review_spi;
        $rekom->status_rekomendasi_id=$request->status_rekomendasi;
        $rekom->rekom_publish=$request->publish;
        $save=$rekom->save();

        if($request->publish==0)
        {
            if($save)
                return redirect('data-tindaklanjut-senior/'.$tahun)->with('success','Data Review Berhasil Di Simpan Sebagai Draft');
            else
                return redirect('data-tindaklanjut-senior/'.$tahun)->with('error','Data Review Gagal Di Simpan Sebagai Draft');
        }
        else
        {
            if($save)
                return redirect('data-tindaklanjut-senior/'.$tahun)->with('success','Data Review Berhasil Di Simpan dan Di Publish Ke Super User');
            else
                return redirect('data-tindaklanjut-senior/'.$tahun)->with('error','Data Review Gagal Di Simpan');
        }
    }

    public function tindaklanjut_su_index(Request $request,$tahun=null,$rekomid=null,$temuanid=null)
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

        $statusrekom=StatusRekomendasi::all();
        $st=array();
        foreach($statusrekom as $k=>$v)
        {
            $st[$v->id]=$v;
        }

        $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                    ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                    ->where('daftar_lhp.status_lhp','Publish LHP')
                    ->where('daftar_lhp.tahun_pemeriksa',$tahun)
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

        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->where('status_review_pic_1', $request->key)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        return view('backend.pages.data-lhp.tindak-lanjut.su.tl.index')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuanid',$temuanid)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
    }

    public function tindaklanjut_su_list(Request $request)
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
        if(count($wh)!=0)
        {
            $alldata=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                ->where('daftar_lhp.status_lhp','Publish LHP')
                ->where($wh)
                ->where('daftar_lhp.tahun_pemeriksa',$tahun)
                ->whereNull('data_rekomendasi.deleted_at')
                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                ->get();
        }
        else{
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
        // return $st;
        $jlhtl=$this->jlh_tindaklanjut();

        return view('backend.pages.data-lhp.tindak-lanjut.su.tl.list')
                ->with('tahun',$tahun)
                ->with('jumlahtl',$jlhtl)
                ->with('rekomid',$rekomid)
                ->with('gettindaklanjut',$tindaklanjut)
                ->with('temuanid',$temuan_id)
                ->with('alldata',$alldata)
                ->with('pic',$pic)
                ->with('strekom',$st)
                ->with('lhp',$lhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('pemeriksa',$pemeriksa)
                ->with('temuan',$temuan);
    }
    public function tindaklanjut_su_simpan(Request $request)
    {
        $tahun=$request->tahun;
        $rekom=DataRekomendasi::find($request->idrekomendasi);
        $rekom->review_auditor=$request->review_spi;
        $rekom->status_rekomendasi_id=$request->status_rekomendasi;
        $rekom->rekom_publish=$request->publish;
        $save=$rekom->save();

        if($request->publish==0)
        {
            if($save)
                return redirect('data-tindaklanjut-su/'.$tahun)->with('success','Data Review Berhasil Di Simpan Sebagai Draft');
            else
                return redirect('data-tindaklanjut-su/'.$tahun)->with('error','Data Review Gagal Di Simpan Sebagai Draft');
        }
        else
        {
            if($save)
                return redirect('data-tindaklanjut-su/'.$tahun)->with('success','Data Review Berhasil Di Simpan dan Di Publish Ke Super User');
            else
                return redirect('data-tindaklanjut-su/'.$tahun)->with('error','Data Review Gagal Di Simpan');
        }
    }
}