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
use Auth;
use Validator;
class DataTemuanController extends Controller
{
    public function index($tahun=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $data['pemeriksa']=$pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $data['jenisaudit']=$jenisaudit=JenisAudit::orderBy('jenis_audit')->get();
        return view('backend.pages.data-lhp.auditor-junior.index')
                ->with('tahun',$thn)
                ->with('data',$data)
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
    }
    public function lhp_edit($id)
    {
        $lhp=DaftarTemuan::where('id',$id)->with('dpemeriksa')->first();
        return $lhp;
    }
    public function lhp_delete(Request $request,$id)
    {
        DaftarTemuan::destroy($id);
        return redirect('data-lhp')
            ->with('success', 'Anda telah menghapus data LHP.');
    }
    public function review_lhp($idlhp)
    {
        $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        return view('backend.pages.data-lhp.auditor-junior.review-lhp')->with('data',$data);
    }
    public function data_lhp($tahun=null)
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
    public function detail_lhp($id)
    {
        $data=DaftarTemuan::where('id',$id)
                ->with('dpemeriksa')
                ->with('djenisaudit')
                ->first();

        return view('backend.pages.data-lhp.auditor-junior.detail')
                ->with('data',$data);
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
        $insert->save();

        return redirect()->route('data-lhp.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }
    public function update(Request $request,$idlhp)
    {
        // return $request->all();
        list($idpem,$code,$pemeriksa)=explode('-',$request->pemeriksa);
        list($tgl,$bln,$thn)=explode('/',$request->tanggal_lhp);

        $insert=DaftarTemuan::find($idlhp);
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
        $insert->save();

        return redirect()->route('data-lhp.index')
            ->with('success', 'Anda telah mengubah data LHP.');
    }
    
    public function data_temuan_lhp($idlhp)
    {
        $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        $dt['jenistemuan']=MasterTemuan::orderBy('temuan')->get();
        $dt['picunit']=PICUnit::with('levelpic')->orderBy('nama_pic')->get();
        $dt['levelresiko']=LevelResiko::orderBy('level_resiko')->get();
        $dt['jangkawaktu']=$jangkawaktu=JangkaWaktu::orderBy('jangka_waktu')->get();
        $dt['statusrekomendasi']=$statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();

        $dt['temuan']=$temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')->with('jenistemuan')->with('picunit')->with('levelresiko')->where('id_lhp',$idlhp)->get();

        $rekom=DataRekomendasi::with('jenistemuan')->with('picunit1')->with('picunit2')->with('jangkawaktu')->with('statusrekomendasi')->get();
        $rekomendasi=array();
        foreach($rekom as $k=>$v)
        {
            $rekomendasi[$v->id_temuan][]=$v;
        }
        $dt['rekomendasi']=$rekomendasi;
        return view('backend.pages.data-lhp.auditor-junior.temuan')
                ->with('dt',$dt)
                ->with('idlhp',$idlhp)
                ->with('rekomendasi',$rekomendasi)
                ->with('temuan',$temuan)
                ->with('jangkawaktu',$jangkawaktu)
                ->with('statusrekomendasi',$statusrekomendasi)
                ->with('data',$data);
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
}
