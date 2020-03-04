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

       
        $pemeriksa=Pemeriksa::orderBy('code')->get();
        $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->orderBy('id','desc')->get();
        // return $datalhp;
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
    
    public function unitkerja_index($tahun=null,$rekomid=null,$temuanid=null)
    {
        $tahun=($tahun==null ? date('Y') : $tahun);
        $rekomid=($rekomid==null ? -1 : $rekomid);
        $temuanid=($temuanid==null ? -1 : $temuanid);

        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        
        $pemeriksa=Pemeriksa::orderBy('code')->get();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();
        $datalhp=DaftarTemuan::where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->orderBy('id','desc')->get();
        // return $datalhp;
        $idlhparray=$dlhp=array();
        $lhp=array();
        foreach($datalhp as $k=>$v)
        {
            $idlhparray[$v->id]=$v->id;
            $lhp[$v->id]=$v;
        }
        $temuan=$rekomendasi=$idtemuanarray=array();
        if(count($idlhparray)!=0)
        {
            // $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->where('pic_temuan_id',$user_pic->id)->with('totemuan')->get();
            $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->with('totemuan')->get();
            foreach($temuan as $kk=>$vv)
            {
                if($vv->totemuan->tahun_pemeriksa==$tahun)
                {
                    // if($vv->pic_temuan_id==$user_pic->id)
                    // {
                        $idtemuanarray[]=$vv->id;
                        if(isset($lhp[$vv->id_lhp]))
                            $dlhp[]=$lhp[$vv->id_lhp];
                    // }
                }
            }
        }

        // return $idtemuanarray;
        if(count($idtemuanarray)!=0)
        {
            $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)
                        ->where(function($query) use ($user_pic){
                            $query->where('pic_1_temuan_id', $user_pic->id);
                            $query->orWhere('pic_2_temuan_id', $user_pic->id);
                        })->with('picunit2')->get();

            foreach($rekom as $k=>$v)
            {
                // if($v->pic_1_temuan_id!=null)
                // {
                //     if()
                //     $rekomendasi[$v->id_temuan][]=$v;
                // }
                // else
                    $rekomendasi[$v->id_temuan][]=$v;
            }
        }
        // return $idtemuanarray;
        // return $rekomendasi;
        
        
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut')
                ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('idlhparray',$idlhparray)
                ->with('datalhp',$dlhp)
                ->with('pemeriksa',$pemeriksa)
                ->with('rekomendasi',$rekomendasi)
                ->with('temuan',$temuan)
                ->with('temuanid',$temuanid);
    }

    public function unitkerja_list(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $tahun=($request->tahun ? $request->tahun : date('Y'));
        $rekomid=($request->rekomid ? $request->rekomid : -1);
        $temuanid=($request->temuanid ? $request->temuanid : -1);
        $statusrekom=($request->statusrekom ? $request->statusrekom : -1);
        $pemeriksa=($request->pemeriksa ? $request->pemeriksa : -1);
        $no_lhp=($request->no_lhp ? $request->no_lhp : -1);

        $temuan=$rekomendasi=$idtemuanarray=array();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        // $datalhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('status_lhp','Publish LHP')->orderBy('id','desc')->get();

        
        $lhp=$wh=array();
        if($pemeriksa!=-1)
        {
            $wh['pemeriksa_id']=$pemeriksa;
        }
        if($no_lhp!=-1)
        {
            $wh['id']=$no_lhp;
        }


        if(count($wh)!=0)
            $datalhp=DaftarTemuan::where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->where($wh)->orderBy('id','desc')->get();
        else
            $datalhp=DaftarTemuan::where('status_lhp','Publish LHP')->where('tahun_pemeriksa',$tahun)->orderBy('id','desc')->get();

        $idlhparray=array();
        foreach($datalhp as $k=>$v)
        {
            $lhp[$v->id]=$v;
            $idlhparray[$v->id]=$v->id;
        }

        // $temuan=$rekomendasi=$idtemuanarray=array();
        if(count($idlhparray)!=0)
        {
            // $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->where('pic_temuan_id',$user_pic->id)->with('totemuan')->get();
            $temuan=DataTemuan::whereIn('id_lhp',$idlhparray)->with('totemuan')->get();
            foreach($temuan as $kk=>$vv)
            {
                if($vv->totemuan->tahun_pemeriksa==$tahun)
                {
                    if($vv->pic_temuan_id==$user_pic->id)
                    {
                        $idtemuanarray[]=$vv->id;
                    }
                }
            }
        }

        if(count($idtemuanarray)!=0)
        {
            if($statusrekom==-1)
                $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->where('pic_1_temuan_id',$user_pic->id)->with('picunit2')->get();
            else
                $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuanarray)->where('pic_1_temuan_id',$user_pic->id)->where('status_rekomendasi_id',$statusrekom)->with('picunit2')->get();

            foreach($rekom as $k=>$v)
            {
                $rekomendasi[$v->id_temuan][]=$v;
            }
        }

        // return $rekomendasi;
        // return view('backend.pages.data-lhp.pic-unit.tindaklanjut-data')
        //                 ->with('rekomendasi',$rekomendasi)
        //                 ->with('idtemuanarray',$idtemuanarray)
        //                 ->with('temuan',$temuan);
        // echo 'ss';
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-list')
                ->with('tahun',$tahun)
                ->with('rekomid',$rekomid)
                ->with('idlhparray',$idlhparray)
                ->with('datalhp',$datalhp)
                ->with('pemeriksa',$pemeriksa)
                ->with('rekomendasi',$rekomendasi)
                ->with('temuan',$temuan)
                ->with('temuanid',$temuanid);
    }

    public function set_tgl_penyelesaian($temuanid,$rekomid,$tgl,$bln,$thn)
    {
        $date=$thn.'-'.$bln.'-'.$tgl;
        $rekom=DataRekomendasi::find($rekomid);
        $rekom->tanggal_penyelesaian=$date;
        $c=$rekom->save();
        if($c)
            echo tgl_indo($date);
    }

    public function unitkerja_add_form($idlhp,$temuan_id_index,$rekom_id_index)
    {
        list($temuan_id,$temuan_idx)=explode('_',$temuan_id_index);
        list($rekom_id,$rekom_idx)=explode('_',$rekom_id_index);
        $data=DaftarTemuan::find($idlhp);

        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();

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
                 $query->orWhere('pic_2_temuan_id', $user_pic->id);
             })
             ->with('dtemuan')->get();
        // ->where('pic_1_temuan_id',$user_pic->id)
        if($temuan_idx!=0)
        {
            $dtem=$dtemuan[$temuan_idx];
            $rekomendasi=DataRekomendasi::where('id_temuan',$dtem->id)
                ->where(function($query) use ($user_pic){
                    $query->where('pic_1_temuan_id', $user_pic->id);
                    $query->orWhere('pic_2_temuan_id', $user_pic->id);
                })->with('dtemuan')->get();
            // ->where('pic_1_temuan_id',$user_pic->id)
        }

        // return $arrayidtemuan;
        // $rekomendasi=DataRekomendasi::whereIn('id_temuan',$arrayidtemuan)->get();
        
        
        $drekomendasi=array();
        foreach($rekomendasi as $k=>$v)
        {
            // if($v->dtemuan->id_lhp==$idlhp)
                $drekomendasi[$k]=$v;
        }

        // return $drekomendasi;
        return view('backend.pages.data-lhp.pic-unit.tindaklanjut-form')
                        ->with('rekomendasi',isset($drekomendasi[$rekom_idx]) ? $drekomendasi[$rekom_idx] : array())
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
        $tindaklanjut=new TindakLanjutTemuan;
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
                ->with('success', 'Anda telah Berhasil Menambah data Tindak Lanjut Untuk Nomor Rekomendasi '.$request->nomor_rekomendasi.'.');
        }
        else
        {
            return redirect('data-tindaklanjut-unitkerja/'.$tahun)
                ->with('error', 'Menambah data Tindak Lanjut Untuk Nomor Rekomendasi '.$request->nomor_rekomendasi.' Gagal');
        }
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
    public function list_tindaklanjut_rincian($idrincian,$jenis)
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

        $insert=new TindakLanjutRincian;
        $insert->id_temuan = $rincian->id_temuan;
        $insert->id_rekomendasi = $rincian->id_rekomendasi;
        $insert->unit_kerja_id = $rincian->unit_kerja_id;
        $insert->id_tindak_lanjut = $request->idform;
        $insert->dokumen_pendukung = $path;

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
}
