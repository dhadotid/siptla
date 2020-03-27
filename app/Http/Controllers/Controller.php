<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\TindakLanjutTemuan;
use App\Models\DataRekomendasi;
use App\Models\Notifikasi;
use Auth;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function tindaklanjut()
    {
        $tindaklanjut=TindakLanjutTemuan::selectRaw('*,tindak_lanjut_temuan.id as tl_id')->with('pic1')->with('pic2')->with('dokumen_tindak_lanjut')->get();
        $data=array();
        foreach($tindaklanjut as $k=>$v)
        {
            $data[$v->rekomendasi_id][]=$v;
        }
        return $data;
    }

    public function open_file($dir1,$dir2,$filename)
    {
        $file=$dir1.'/'.$dir2.'/'.$filename;
        return response()->file(storage_path('app').'/'.$file);
        // return '<iframe src="/uploads/media/default/0001/01/540cb75550adf33f281f29132dddd14fded85bfc.pdf" width="100%" height="500px">';
    }
    public function read_pdf($dir1,$dir2,$filename)
    {
        echo '<iframe src="'.url('open-file/'.$dir1.'/'.$dir2.'/'.$filename).'" width="100%" height="100%">
                </iframe>';
        // return '<iframe src="/uploads/media/default/0001/01/540cb75550adf33f281f29132dddd14fded85bfc.pdf" width="100%" height="500px">';
    }

    public function jlh_tindaklanjut($tahun=null,$bulan=null)
    {
        if($bulan==null)
            $bln=date('m');
        else
            $bln=$bulan;
        
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $dt=$thn.'-'.$bln;

        $tl=TindakLanjutTemuan::where('tgl_tindaklanjut','like',"$dt%")->get();
        $tindaklanjut=array();
        foreach($tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        return $tindaklanjut;
    }

    public function cekstrekomsenior()
    {
        $rekom=DataRekomendasi::with('dtemuan')->get();
        $rekomendasi=array();
        foreach($rekom as $k=>$v)
        {
            if(isset($v->dtemuan->id_lhp))
            {
                if(Auth::user()->level=='auditor-senior')
                {
                    if($v->senior_publish==1)
                        $rekomendasi[$v->senior_user_id][$v->dtemuan->id_lhp][$v->id_temuan]['setuju'][]=$v;
                    else
                        $rekomendasi[$v->senior_user_id][$v->dtemuan->id_lhp][$v->id_temuan]['belum'][]=$v;
                }
                elseif(Auth::user()->level=='super-user')
                {
                    if($v->senior_publish==1)
                        $rekomendasi[$v->dtemuan->id_lhp][$v->id_temuan]['setuju'][]=$v;
                    else
                        $rekomendasi[$v->dtemuan->id_lhp][$v->id_temuan]['belum'][]=$v;
                }
            }
        }

        return $rekomendasi;
    }

    public function sendnotif(&$data)
    {
        $dari=$data['dari'];
        $kepada=$data['kepada'];
        $pesan=$data['pesan'];

        $new=new Notifikasi;
        $new->dari = $dari;
        $new->kepada = $kepada;
        $new->pesan = $pesan;
        $c=$new->save();
        
        return $c;
    }
    
    public function kirimemail(&$data)
    {
        $dari=$data['dari'];
        $kepada=$data['kepada'];
        $pesan=$data['pesan'];
        $email=$data['email'];

    }
}
