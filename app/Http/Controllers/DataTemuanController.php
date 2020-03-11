<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksa;
use App\Models\JenisAudit;
use App\Models\DaftarTemuan;
use App\Models\MasterTemuan;
use App\Models\PICUnit;
use App\Models\LevelResiko;
use App\Models\DataTemuan;
use App\Models\DataRekomendasi;
use App\Models\JangkaWaktu;
use App\Models\StatusRekomendasi;
use App\User;
use App\Models\Review;
use Auth;
use Validator;
class DataTemuanController extends Controller
{
    public function index($tahun=null,$statusrekom=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $data['pemeriksa']=$pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $data['jenisaudit']=$jenisaudit=JenisAudit::orderBy('jenis_audit')->get();

        if(Auth::user()->level=='pic-unit')
        {
                return view('backend.pages.data-lhp.pic-unit.index')
                    ->with('tahun',$thn)
                    ->with('data',$data)
                    ->with('statusrekom',$statusrekom)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('jenisaudit',$jenisaudit);
        }
        else
            return view('backend.pages.data-lhp.auditor-junior.index')
                    ->with('tahun',$thn)
                    ->with('data',$data)
                    ->with('statusrekom',$statusrekom)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('jenisaudit',$jenisaudit);
    }
    public function index_semua($tahun=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $data['pemeriksa']=$pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $data['jenisaudit']=$jenisaudit=JenisAudit::orderBy('jenis_audit')->get();
        return view('backend.pages.data-lhp.auditor-junior.index-semua')
                ->with('tahun',$thn)
                ->with('data',$data)
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
    }
    public function lhp_edit($id)
    {
        $lhp=DaftarTemuan::selectRaw('*, daftar_lhp.id as id_lhp')->where('id',$id)->with('dpemeriksa')->first();
        return $lhp;
    }
    public function lhp_delete(Request $request,$id)
    {
        DaftarTemuan::destroy($id);
        return redirect('data-lhp')
            ->with('success', 'Anda telah menghapus data LHP.');
    }
    public function hapus_lhp_review($idreview)
    {
        Review::destroy($idreview);
    }
    public function review_lhp($idlhp)
    {
        $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        $dt['review']=$review=Review::selectRaw('*,review.id as review_id')->where('id_lhp',$idlhp)
                    ->with('reviewer')->with('tanggapan')->orderBy('id')->get();
        return view('backend.pages.data-lhp.auditor-junior.review-lhp')
                ->with('idlhp',$idlhp)
                ->with('data',$data)
                ->with('review',$review);
    }
    public function form_review_lhp($idlhp,$idreview=0)
    {
        $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        
        $review=Review::selectRaw('*,review.id as review_id')->where('id',$idreview)
                    ->with('reviewer')->with('tanggapan')->first();
        return view('backend.pages.data-lhp.auditor-junior.review-form')
                ->with('idlhp',$idlhp)
                ->with('idreview',$idreview)
                ->with('review',$review)
                ->with('data',$data);
    }
    public function simpan_lhp_review(Request $request,$id)
    {
        // if($request->idreview!=0)
        // {
        //     $lhp=DaftarTemuan::find($id);
        //     $lhp->status_lhp = 'Review LHP';
        //     $lhp->review_flag=1;
        //     $lhp->save();
        // }
        $lhp=DaftarTemuan::find($id);
        $lhp->status_lhp = $request->status_lhp;
        if($request->status_lhp=='Review LHP')
            $lhp->review_flag=1;
            
        if($request->status_lhp=='Publish LHP')
            $lhp->publish_flag=1;

        if(Auth::user()->level=='auditor-junior')
            $lhp->flag_senior=1;
        
        if(Auth::user()->level=='auditor-senior')
        {
            $lhp->flag_senior=1;
            $lhp->flag_unit_kerja=1;
        }
        
        $lhp->save();

        if($request->idreview==0)
            $insert=new Review;
        else
            $insert=Review::find($request->idreview);
        
        $insert->id_lhp=$id;
        $insert->review_id=0;
        $insert->reviewer_id=Auth::user()->id;
        $insert->review=$request->review;
        $c=$insert->save();
        if($c)
            echo 1;
        else    
            echo 0;
    }
    public function data_lhp($tahun=null,$statusrekom=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;
        
        $drekom=$arraylhp=array();
        if($statusrekom!=null)
        {
            $datarekom=DataRekomendasi::where('status_rekomendasi_id',$statusrekom)->with('dtemuan')->get();
            foreach($datarekom as $k=>$v)
            {
                $drekom[$v->id_temuan][]=$v;
                $arraylhp[$v->dtemuan->id_lhp]=$v->dtemuan->id_lhp;
            }
        }

        if(Auth::user()->level=='auditor-junior')
        {
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->where('daftar_lhp.tahun_pemeriksa',$thn)
                    ->where('daftar_lhp.user_input_id',Auth::user()->id)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();
        }
        elseif(Auth::user()->level=='auditor-senior')
        {
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->where('daftar_lhp.tahun_pemeriksa',$thn)
                    // ->where('daftar_lhp.user_input_id',Auth::user()->id)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();

                // $data=DaftarTemuan::selectRaw('*,data_rekomendasi.id as idrekom')->join('pemeriksa','pemeriksa.id','=','daftar_lhp.pemeriksa_id')
                //                 ->join('jenis_audit','jenis_audit.id','=','daftar_lhp.jenis_audit_id')
                //                 ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                //                 ->join('data_rekomendasi','data_rekomendasi.id_temuan','=','data_temuan.id')
                //                 ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                //                 ->orderBy('daftar_lhp.tanggal_lhp','desc')->get();
        }
       elseif(Auth::user()->level=='pic-unit')
        {
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->where('daftar_lhp.tahun_pemeriksa',$thn)
                    // ->where('daftar_lhp.flag_unit_kerja',1)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();
            
            $idlhp=$idtemuan=$filteridlhp=array();
            foreach($data as $k=>$v)
            {
                $idlhp[$v->lhp_id]=$v->lhp_id;
            }

            $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
            $tem=DataTemuan::whereIn('id_lhp',$idlhp)->get();
            foreach($tem as $k=>$v)
            {
                $idtemuan[$v->id]=$v->id;
                if($user_pic->id==$v->pic_temuan_id)
                    $filteridlhp[$v->id_lhp]=$v->id_lhp;
            }
            $rekom=DataRekomendasi::whereIn('id_temuan',$idtemuan)
                        ->where(function($query) use ($user_pic){
                                    $query->where('pic_1_temuan_id', $user_pic->id);
                                    $query->orWhere('pic_2_temuan_id','like', "%$user_pic->id%,");
                                })    
                        ->with('dtemuan')->get();

            foreach($rekom as $kr=>$vr)
            {
                if(isset($vr->dtemuan->id_lhp))
                    $filteridlhp[$vr->dtemuan->id_lhp]=$vr->dtemuan->id_lhp;
            }

            // return $filteridlhp;

            return view('backend.pages.data-lhp.pic-unit.data')
                ->with('data',$data)
                ->with('filteridlhp',$filteridlhp)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('drekom',$drekom);
        }
        else
        {
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->where('daftar_lhp.tahun_pemeriksa',$thn)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();
        }
        // return $statusrekom;
        return view('backend.pages.data-lhp.auditor-junior.data')
                ->with('data',$data)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('drekom',$drekom);
    }
    public function semua_data_lhp($tahun=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;
        
        
        $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                ->where('daftar_lhp.tahun_pemeriksa',$thn)
                ->with('dpemeriksa')
                ->with('djenisaudit')
                ->orderBy('tanggal_lhp','desc')->get();

        return view('backend.pages.data-lhp.auditor-junior.data')
                ->with('data',$data);
    }

    public function data_lhp_cek_kode($pemeriksa)
    {
        list($id,$code,$pem)=explode('-',$pemeriksa);
        $tahun=date('Y');
        $data=DaftarTemuan::where('kode_lhp','like',"%$code%")->orderBy('id','desc')->first();
        if($data)
        {
            if(strpos($data->kode_lhp,$tahun)!==false)
            {
                list($aw,$tg,$akh)=explode('/',$data->kode_lhp);
                $no=(int) $tg+ 1;
                if($no<10)
                    return $code.'/00'.$no.'/'.$tahun;
                elseif($no>=10 && $no<100)
                    return $code.'/0'.$no.'/'.$tahun;
                else
                    return $code.'/'.$no.'/'.$tahun;
            }
            else
            {
                return $code.'/001/'.$tahun;
            }
        }
        else
        {
            return $code.'/001/'.$tahun;
        }
    }
    public function detail_lhp($id,$offset,$statusrekom=null)
    {
        $data=DaftarTemuan::where('id',$id)
                ->with('dpemeriksa')
                ->with('djenisaudit')
                ->first();

        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();

        if(Auth::user()->level=='pic-unit')
            $getrekom=DataRekomendasi::where('pic_1_temuan_id', $user_pic->id)->orWhere('pic_2_temuan_id', 'like',"%$user_pic->id%,")->with('dtemuan')->orderBy('nomor_rekomendasi')->get();
        else
            $getrekom=DataRekomendasi::with('dtemuan')->orderBy('nomor_rekomendasi')->get();


        $getidtemuan=$datarekom=array();
        foreach($getrekom as $kr=>$vr)
        {
            if(isset($vr->dtemuan->id_lhp))
            {
                if($vr->dtemuan->id_lhp==$id)
                {
                    $getidtemuan[$vr->id_temuan]=$vr->id_temuan;
                    $datarekom[$vr->id_temuan][]=$vr;
                }
            }
        }

        // $temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')->where('id_lhp',$id)->with('jenistemuan')->with('picunit')->with('levelresiko')->get();
        $temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')->whereIn('id',$getidtemuan)->with('jenistemuan')->with('picunit')->with('levelresiko')->get();
        $dtemuan=$drekomendasi=array();
        $idx=0;
        foreach($temuan as $k=>$v)
        {
            $dtemuan[$idx]=$v;
            $idx++;

        }
        $jlhtemuan=$temuan->count();
        if(isset($dtemuan[$offset])) 
        {
            $tm=$dtemuan[$offset];
            if($statusrekom!=null && $statusrekom!='null')
            {
                $rekomendasi=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')
                            ->where('id_temuan',$tm->id)
                            ->where('status_rekomendasi_id',$statusrekom)
                            ->with('jenistemuan')
                            ->with('picunit1')
                            ->with('picunit2')
                            ->with('jangkawaktu')
                            ->with('statusrekomendasi')
                            ->with('drekanan')
                            ->get();
                
                
            }
            else{
                $rekomendasi=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')->where('id_temuan',$tm->id)
                            ->with('jenistemuan')
                            ->with('picunit1')
                            ->with('picunit2')
                            ->with('jangkawaktu')
                            ->with('statusrekomendasi')
                            ->with('drekanan')
                            ->get();
                            // return $rekomendasi;
            }
            foreach($rekomendasi as $kk=>$vv)
            {
                $drekomendasi[]=$vv;
            }
        }
        else
        {
            $tm=(object) array('data'=>0);
        }

        
        if(Auth::user()->level=='pic-unit')
        {
            $picunit=PICUnit::where('id_user',Auth::user()->id)->first();
            return view('backend.pages.data-lhp.pic-unit.detail-lhp')
                    ->with('temuan',$tm)
                    ->with('picunit_id',$picunit->id)
                    ->with('offset',$offset)
                    ->with('statusrekom',$statusrekom)
                    ->with('jlhtemuan',$jlhtemuan)
                    ->with('id',$id)
                    ->with('drekomendasi',$drekomendasi)
                    ->with('data',$data);
        }
        else
        {
        // return $drekomendasi;
            return view('backend.pages.data-lhp.auditor-junior.detail-lhp')
                    ->with('temuan',$tm)
                    ->with('offset',$offset)
                    ->with('statusrekom',$statusrekom)
                    ->with('jlhtemuan',$jlhtemuan)
                    ->with('id',$id)
                    ->with('drekomendasi',$drekomendasi)
                    ->with('data',$data);
        }
        // return $data;
    }
    public function store(Request $request)
    {
        // return $request->all();
        list($idpem,$code,$pemeriksa)=explode('-',$request->pemeriksa);
        list($tgl,$bln,$thn)=explode('/',$request->tanggal_lhp);

        $insert=new DaftarTemuan;
        $insert->no_lhp = $request->nomor_lhp;
        $insert->kode_lhp = $request->kode_lhp;
        $insert->judul_lhp = $request->judul_lhp;
        $insert->pemeriksa_id = $idpem;
        $insert->tanggal_lhp = $thn.'-'.$bln.'-'.$tgl;
        $insert->tahun_pemeriksa = $request->tahun_pemeriksaan;
        $insert->jenis_audit_id = $request->jenis_audit;
        $insert->status_lhp = $request->status_lhp;
        $insert->create_flag = $request->flag_status_lhp;
        $insert->user_input_id = Auth::user()->id;

        if(Auth::user()->level=='auditor-senior')
        {
            $insert->review_lhp=$request->review_lhp;
        }

        $insert->save();

        return redirect()->route('data-lhp.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }
    public function update(Request $request,$idlhp)
    {
        // return $request->all();
        list($idpem,$code,$pemeriksa)=explode('-',$request->pemeriksa);
        list($tgl,$bln,$thn)=explode('/',$request->tanggal_lhp);

        $update=DaftarTemuan::find($idlhp);
        $update->no_lhp = $request->nomor_lhp;
        $update->kode_lhp = $request->kode_lhp;
        $update->judul_lhp = $request->judul_lhp;
        $update->pemeriksa_id = $idpem;
        $update->tanggal_lhp = $thn.'-'.$bln.'-'.$tgl;
        $update->tahun_pemeriksa = $request->tahun_pemeriksaan;
        $update->jenis_audit_id = $request->jenis_audit;
        $update->status_lhp = $request->status_lhp;
        $update->create_flag = $request->flag_status_lhp;
        $update->user_input_id = Auth::user()->id;
        if(Auth::user()->level=='auditor-senior')
        {
            $update->review_lhp=$request->review_lhp;
            if($request->flag_status_lhp=='Review LHP')
            {
                $update->review_flag = 1;
            }
            if($request->flag_status_lhp=='Publish LHP')
            {
                $update->publish_flag = 1;
            }
        }
        $update->save();

        return redirect()->route('data-lhp.index')
            ->with('success', 'Anda telah mengubah data LHP.');
    }
    
    public function data_temuan_lhp($idlhp,$statusrekom=null)
    {
        if(Auth::user()->level=='auditor-junior')
            $dt['data']=$data=DaftarTemuan::where('user_input_id',Auth::user()->id)->where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        else
            $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();

        $dt['jenistemuan']=MasterTemuan::orderBy('temuan')->get();
        $dt['picunit']=PICUnit::with('levelpic')->orderBy('nama_pic')->get();
        $dt['levelresiko']=LevelResiko::orderBy('level_resiko')->get();
        $dt['jangkawaktu']=$jangkawaktu=JangkaWaktu::orderBy('jangka_waktu')->get();
        $dt['statusrekomendasi']=$statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();

        
        $temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')->with('jenistemuan')->with('picunit')->with('levelresiko')->where('id_lhp',$idlhp)->get();
        
        $dtem=array();
        foreach($temuan as $ktem=>$vtem)
        {
            $dtem[$vtem->temuan_id]=$vtem;
        }
        
        

        $rekom=DataRekomendasi::with('jenistemuan')->with('picunit1')->with('picunit2')->with('jangkawaktu')->with('statusrekomendasi')->get();
        $rekomendasi=$drekom=array();
        foreach($rekom as $k=>$v)
        {
            $rekomendasi[$v->id_temuan][]=$v;
            $drekom[$v->id_temuan][$v->status_rekomendasi_id][]=$v;
        }
        $dt['rekomendasi']=$rekomendasi;
        $dt['temuan']=$dtem;

        $senior=User::where('level','auditor-senior')->get();
        // return $dtem;
        if($data)
        {
            return view('backend.pages.data-lhp.auditor-junior.temuan-new')
                    ->with('dt',$dt)
                    ->with('idlhp',$idlhp)
                    ->with('drekom',$drekom)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('senior',$senior)
                    ->with('temuan',$temuan)
                    ->with('jangkawaktu',$jangkawaktu)
                    ->with('statusrekomendasi',$statusrekomendasi)
                    ->with('statusrekom',$statusrekom)
                    ->with('data',$data);
        }
        else
        {

            return redirect('data-lhp')->with('error','Data LHP Yang Anda Cari Tidak Ditemukan');
        }
    }

    public function data_temuan_data($idlhp)
    {
        $data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        $rekom=DataRekomendasi::with('jenistemuan')->with('picunit1')->with('picunit2')->with('jangkawaktu')->with('statusrekomendasi')->get();
        $rekomendasi=array();
        foreach($rekom as $k=>$v)
        {
            $rekomendasi[$v->id_temuan][]=$v;
        }
        
    }

    public function data_temuan_edit($id)
    {
        $temuan=DataTemuan::with('jenistemuan')
            ->with('picunit')
            ->with('levelresiko')
            ->where('id',$id)->first();
        return $temuan;
    }
    public function data_temuan_lhp_simpan(Request $request,$idlhp)
    {  
        $rules = [
            'nomor_temuan' => 'required',
            'temuan' => 'required',
            'jenis_temuan' => 'required',
            'pic_temuan' => 'required',
            'nominal' => 'required',
            'level_resiko' => 'required',
        ];

        $customMessages = [
            'nomor_temuan.required' => 'Nomor Temuan Harus Diisi',
            'temuan.required' => 'Temuan Harus Diisi',
            'jenis_temuan.required' => 'Jenis Temuan Harus Dipilih',
            'pic_temuan.required' => 'PIC Temuan Harus Dipilih',
            'nominal.required' => 'Jumlah Nominal Harus Diisi',
            'level_resiko.required' => 'Level Resiko Harus Dipilih',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $insert=new DataTemuan;
        $insert->id_lhp=$idlhp;
        $insert->no_lhp=$request->nomor_lhp;
        $insert->no_temuan=$request->nomor_temuan;
        // $insert->temuan=str_replace("\n","<br>",$request->temuan);
        $insert->temuan=$request->temuan;
        $insert->jenis_temuan_id=$request->jenis_temuan;
        $insert->pic_temuan_id=$request->pic_temuan;
        $insert->level_resiko_id=$request->level_resiko;
        $insert->nominal=str_replace('.','',$request->nominal);
        $insert->save();
        // return $request->all();
        return redirect('data-temuan-lhp/'.$idlhp)
            ->with('success', 'Anda telah memasukkan data temuan baru.');
    }
    public function data_temuan_lhp_update(Request $request,$idlhp)
    {  
        $rules = [
            'nomor_temuan' => 'required',
            'temuan' => 'required',
            'jenis_temuan' => 'required',
            'pic_temuan' => 'required',
            'nominal' => 'required',
            'level_resiko' => 'required',
        ];

        $customMessages = [
            'nomor_temuan.required' => 'Nomor Temuan Harus Diisi',
            'temuan.required' => 'Temuan Harus Diisi',
            'jenis_temuan.required' => 'Jenis Temuan Harus Dipilih',
            'pic_temuan.required' => 'PIC Temuan Harus Dipilih',
            'nominal.required' => 'Jumlah Nominal Harus Diisi',
            'level_resiko.required' => 'Level Resiko Harus Dipilih',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $temuan_id=$request->temuan_id;
        $update=DataTemuan::find($temuan_id);
        $update->no_temuan=$request->nomor_temuan;
        // $insert->temuan=str_replace("\n","<br>",$request->temuan);
        $update->temuan=$request->temuan;
        $update->jenis_temuan_id=$request->jenis_temuan;
        $update->pic_temuan_id=$request->pic_temuan;
        $update->level_resiko_id=$request->level_resiko;
        $update->nominal=str_replace('.','',$request->nominal);
        $update->save();
        // $idlhp=$update->id_lhp;
        // return $request->all();
        return redirect('data-temuan-lhp/'.$idlhp)
            ->with('success', 'Anda telah memperbaharui data temuan baru.');
    }

    public function data_temuan_delete($idlhp,$id)
    {
        DataTemuan::destroy($id);
        return redirect('data-temuan-lhp/'.$idlhp)
            ->with('success', 'Anda telah menghapus data temuan.');
    }

    public function temuan_by_lhp($idlhp)
    {
        $temuan=DataTemuan::where('id_lhp',$idlhp)->get();
        return $temuan;
    }
    public function temuan_by_lhp_select($idlhp,$userpic_id=null)
    {
        if($userpic_id!=null)
        {
            $temuan=array();
            $tem=DataTemuan::join('data_rekomendasi','data_rekomendasi.id_temuan','=','data_temuan.id')
                    ->where(function($query) use ($userpic_id){
                        $query->where('data_rekomendasi.pic_1_temuan_id', $userpic_id);
                        $query->orWhere('data_rekomendasi.pic_2_temuan_id','like', "%$userpic_id%,");
                        // $query->orWhere('data_rekomendasi.pic_2_temuan_id', $user_pic->id);
                    })->where('data_temuan.id_lhp',$idlhp)
                    ->get();
            foreach($tem as $k=>$v){
                $temuan[$v->id_temuan]=$v;
            }
        }
        else
            $temuan=DataTemuan::selectRaw('*, id as id_temuan')->where('id_lhp',$idlhp)->get();


        if(Auth::user()->level=='auditor-senior')
            $temuan=DataTemuan::selectRaw('*, id as id_temuan')->where('id_lhp',$idlhp)->get();
        
        $select ='<select class="select2 form-control" name="no_temuan" id="no_temuan" onchange="loaddata()">';
        $select.='<option value="0">-Semua-</option>';
        foreach($temuan as $v)
        {
            $select.='<option value="'.$v->id_temuan.'">'.$v->no_temuan.' - '.substr($v->temuan,0,80).'...</option>';
        }
        $select.='</select>';
        return $select;
    }

    public function publish_lhp($idlhp)
    {
        $temuan=DaftarTemuan::where('id',$idlhp)->first();
        $temuan->status_lhp='Publish LHP';
        $temuan->publish_flag=1;
        $temuan->save();
    }
}
