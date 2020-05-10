<?php

namespace App\Http\Controllers;

use App\Models\DaftarTemuan;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\TindakLanjutTemuan;
use App\Models\DataRekomendasi;
use App\Models\DataTemuan;
use App\Models\Notifikasi;
use App\Models\Pemeriksa;
use App\Models\PeriodeReview;
use App\Models\PICUnit;
use App\User;
use Auth;
use Exception;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param int $number
     * @return string
     */
    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function reminder_7()
    {
        try {
            $request    = new Request();
            $datalhp    = DaftarTemuan::all();

            foreach ($datalhp as $key => $value) {
                if (strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('+7 days', strtotime($value->tanggal_publish))))) {
                    $request->type  = 'reminder_7';
                    $request->idlhp = $value->id;
                    $request->judul = 'Reminder LHP';
                    $request->days  = '+3 days';
                    $this->sendEmail($request);
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function reminder_3()
    {
        try {
            $request    = new Request();
            $periode    = PeriodeReview::where('status', 1)->first();
            $datarekom  = DataRekomendasi::all();
            $datapic    = PICUnit::all();

            foreach ($datapic as $key => $value) {
                $bss[$value->id]    = 0;
                $bdl[$value->id]    = 0;
            }

            foreach ($datarekom as $key => $value) {
                if (($value->status_rekomendasi_id == 2 || $value->status_rekomendasi_id == 3) && 
                    strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('-3 days', strtotime(date('Y-m-'.$periode->tanggal_selesai)))))) {
                    if ($value->status_rekomendasi_id == 2) {$bss[$value->pic_1_temuan_id]++;}
                    if ($value->status_rekomendasi_id == 3) {$bdl[$value->pic_1_temuan_id]++;}
                }
            }

            foreach ($datarekom as $key => $value) {
                if (($value->status_rekomendasi_id == 2 || $value->status_rekomendasi_id == 3) && 
                    strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('-3 days', strtotime(date('Y-m-'.$periode->tanggal_selesai)))))) {
                    $request->type  = 'reminder_3';
                    $request->judul = 'Reminder LHP';
                    $request->idrek = $value->id;
                    $request->idtem = $value->id_temuan;
                    $request->bss   = $bss[$value->pic_1_temuan_id];
                    $request->bdl   = $bdl[$value->pic_1_temuan_id];
                    $this->sendEmail($request);
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function reminder_overdue()
    {
        try {
            $request    = new Request();
            $datarekom  = DataRekomendasi::all();
            $datapic    = PICUnit::all();

            foreach ($datapic as $key => $value) {
                $rekjumlah[$value->id]  = 0;
                $rektinggi[$value->id]  = 0;
                $reksedang[$value->id]  = 0;
                $rekrendah[$value->id]  = 0;
            }

            foreach ($datarekom as $key => $value) {
                if (strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('-7 days', strtotime($value->tanggal_penyelesaian))))) {
                    $rekjumlah[$value->pic_1_temuan_id]++;
                    $temuan = DataTemuan::find($value->id_temuan);
                    if ($temuan->level_resiko_id == 4) {$rektinggi[$value->pic_1_temuan_id]++;}
                    if ($temuan->level_resiko_id == 3) {$reksedang[$value->pic_1_temuan_id]++;}
                    if ($temuan->level_resiko_id == 2) {$rekrendah[$value->pic_1_temuan_id]++;}
                }
            }

            foreach ($datarekom as $key => $value) {
                if (strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('-7 days', strtotime($value->tanggal_penyelesaian))))) {
                    $request->type  = 'reminder_overdue';
                    $request->judul = 'Reminder LHP';
                    $request->idrek = $value->id;
                    $request->days  = '+7 days';

                    $request->jml   = $rekjumlah[$value->pic_1_temuan_id];
                    $request->tin   = $rektinggi[$value->pic_1_temuan_id];
                    $request->sed   = $reksedang[$value->pic_1_temuan_id];
                    $request->ren   = $rekrendah[$value->pic_1_temuan_id];

                    $this->sendEmail($request);
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function reminder_junior()
    {
        try {
            $request    = new Request();
            $periode    = PeriodeReview::where('status', 1)->first();
            $datarekom  = DataRekomendasi::all();

            foreach ($datarekom as $key => $value) {
                if ($value->review_spi == null && 
                    strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-'.$periode->tanggal_selesai)))))) {
                    $request->type  = 'reminder_junior';
                    $request->judul = 'Reminder Junior';
                    $request->idrek = $value->id;
                    $request->idtem = $value->id_temuan;

                    $this->sendEmail($request);
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function reminder_senior()
    {
        try {
            $request    = new Request();
            $datarekom  = DataRekomendasi::all();

            foreach ($datarekom as $key => $value) {
                if ($value->review_auditor == null && 
                    $value->status_rekomendasi_id == 3 &&
                    strtotime(date('Y-m-d')) == strtotime(date('Y-m-d', strtotime('-3 days', strtotime(date($value->tanggal_penetapan)))))) {
                    $request->type  = 'reminder_senior';
                    $request->judul = 'Reminder Senior';
                    $request->idrek = $value->id;
                    $request->idtem = $value->id_temuan;

                    $this->sendEmail($request);
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function notifikasi_triwulan()
    {
        try {
            $request    = new Request();
            $datarekom  = DataRekomendasi::all();
            $datapic    = PICUnit::all();
            $tri        = ['01', '04', '07', '10'];
            $wulan      = date('m');

            foreach ($datapic as $key => $value) {
                $summ[$wulan][$value->id]   = 0;
                $done[$wulan][$value->id]   = 0;
            }

            foreach ($datarekom as $key => $value) {
                foreach ($tri as $k => $v) {
                    if ($wulan == $v && $k != 0 &&
                        strtotime($value->created_at) >= strtotime('first day of -3 months') &&
                        strtotime($value->created_at) <= strtotime('last day of -1 months')) {
                        $summ[$wulan][$value->pic_1_temuan_id]++;

                        if ($value->status_rekomendasi_id == 1) {$done[$wulan][$value->pic_1_temuan_id]++;}
                    }
                }
            }

            foreach ($datapic as $key => $value) {
                foreach ($tri as $k => $v) {
                    if ($wulan == $v && $k != 0 &&
                        date('d') == '01' &&
                        $done[$wulan][$value->id] != 0) {
                        $request->type  = 'notifikasi_triwulan';
                        $request->judul = 'Notifikasi Triwulan';
                        $request->idusr = $value->id_user;
                        $request->roman = $this->numberToRomanRepresentation($k);
                        $request->done  = $done[$wulan][$value->id];
                        $request->summ  = $summ[$wulan][$value->id];

                        $this->sendEmail($request);
                    }
                }
            }
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }

    public function sendEmail(Request $request)
    {
        try{
            if ($request->type == 'publish_lhp' || $request->type == 'reminder_7') {
                $data       = [];
                $datalhp    = DaftarTemuan::find($request->idlhp);
                $datatemuan = DataTemuan::where('id_lhp', $request->idlhp);
                $getTemuan  = $datatemuan->get();
                $ids_temuan = [];
                $pemeriksa  = Pemeriksa::find($datalhp->pemeriksa_id);
    
                foreach ($getTemuan as $key => $value) {
                    $ids_temuan[]    = $value->id;
                }
    
                $datarekom  = DataRekomendasi::whereIn('id_temuan', $ids_temuan);
                $jml_temuan = $datatemuan->count();
                $jml_rekom  = $datarekom->count();

                foreach ($getTemuan as $key => $value) {
                    $pic    = PICUnit::find($value->pic_temuan_id);
                    $user   = User::find($pic->id_user);

                    $request->email = $user->email;

                    $data   = [
                        'pic'   => $pic->nama_pic,
                        'lhp'   => $datalhp,
                        'pem'   => $pemeriksa,
                        'jmltem'=> $jml_temuan,
                        'jmlrek'=> $jml_rekom,
                        'tgl'   => date('j F Y', strtotime($request->days ?? '+10 days', strtotime($datalhp->tanggal_publish))),
                    ];
    
                    $body   = [
                        'type'  => $request->type,
                        'data'  => $data,
                    ];
        
                    Mail::send('email', $body, function ($message) use ($request)
                    {
                        $message->subject($request->judul);
                        $message->from('donotreply@gmail.com', 'SIPTLA');
                        $message->to($request->email);
                    });
                }
            }
            elseif ($request->type == 'reminder_3' || $request->type == 'reminder_overdue') {
                $rekom  = DataRekomendasi::find($request->idrek);
                $pic    = PICUnit::find($rekom->pic_1_temuan_id);
                $user   = User::find($pic->id_user);

                $request->email = $user->email;

                $data   = [
                    'pic'   => $pic->nama_pic,
                    'tgl'   => date('j F Y', strtotime($request->days ?? '+3 days')),
                    'bss'   => $request->bss ?? 0,
                    'bdl'   => $request->bdl ?? 0,
                    'jml'   => $request->jml ?? 0,
                    'tin'   => $request->tin ?? 0,
                    'sed'   => $request->sed ?? 0,
                    'ren'   => $request->ren ?? 0,
                ];

                $body   = [
                    'type'  => $request->type,
                    'data'  => $data,
                ];
    
                Mail::send('email', $body, function ($message) use ($request)
                {
                    $message->subject($request->judul);
                    $message->from('donotreply@gmail.com', 'SIPTLA');
                    $message->to($request->email);
                });
            }
            elseif ($request->type == 'reminder_junior' || $request->type == 'reminder_senior') {
                $rekom  = DataRekomendasi::find($request->idrek);
                $temuan = DataTemuan::find($request->idtem);
                $lhp    = DaftarTemuan::find($temuan->id_lhp);
                $user   = User::find($rekom->senior_user_id);

                $request->email = $user->email;

                $data   = [
                    'pic'   => $user->name,
                    'rek'   => $rekom->nomor_rekomendasi,
                    'tem'   => $temuan->no_temuan,
                    'lhp'   => $lhp->no_lhp,
                ];

                $body   = [
                    'type'  => $request->type,
                    'data'  => $data,
                ];
    
                Mail::send('email', $body, function ($message) use ($request)
                {
                    $message->subject($request->judul);
                    $message->from('donotreply@gmail.com', 'SIPTLA');
                    $message->to($request->email);
                });
            }
            elseif ($request->type == 'notifikasi_triwulan') {
                $user   = User::find($request->idusr);

                $request->email = $user->email;

                $data   = [
                    'pic'   => $user->name,
                    'rom'   => $request->roman,
                    'thn'   => date('Y'),
                    'per'   => ($request->done / $request->summ) * 100,
                ];

                $body   = [
                    'type'  => $request->type,
                    'data'  => $data,
                ];

                Mail::send('email', $body, function ($message) use ($request)
                {
                    $message->subject($request->judul);
                    $message->from('donotreply@gmail.com', 'SIPTLA');
                    $message->to($request->email);
                });
            }
            return response(['status' => true, 'message' => 'Berhasil terkirim']);
        }
        catch (Exception $e){
            return response(['status' => false, 'errors' => $e->getMessage()]);
        }
    }
}
