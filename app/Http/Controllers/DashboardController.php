<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailTemuan;

use Auth;
use App\Models\PICUnit;
use App\Models\JenisAudit;
use App\Models\Pemeriksa;
use App\Models\StatusRekomendasi;
use App\Models\MasterTemuan;
use App\Models\DaftarTemuan;
use App\Models\LevelPIC;
use App\Models\TindakLanjutTemuan;
use App\Models\DataRekomendasi;
use App\User;
class DashboardController extends Controller
{
    public function index($tahun=null)
    {
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $levelpic=LevelPIC::where('flag',1)->orderBy('nama_level')->get();
        $picunit=PICUnit::orderBy('nama_pic')->get();
        
        $dpicunit=toArray($picunit,'level_pic');
        $datalevelpic=$colorlevel=array();
        foreach($levelpic as $k=>$v)
        {
            $datalevelpic['labels'][]=$v->nama_level;

            if(isset($dpicunit[$v->id]))
            {
                $datalevelpic['datasets'][0]['data'][]=count($dpicunit[$v->id]);
                $datalevelpic['datasets'][0]['backgroundColor'][]=$colorlevel[]=generate_color_one();
            }
            else
            {
                $datalevelpic['datasets'][0]['data'][]=0;
                $datalevelpic['datasets'][0]['backgroundColor'][]=$colorlevel[]=generate_color_one();
            }
        }
        $color['colorlevel']=$colorlevel;


        $pengguna=User::where('flag',1)->orderBy('name')->get();
        $dpengguna=$datasetuser=$coloruser=array();
        foreach($pengguna as $k=>$v)
        {
            if($v->level=='0')
            {
                continue;
                $level='Administrator';
            }
            else
                $level=ucwords(str_replace('-',' ',$v->level));

            $datasetuser[$level][]=$v;
        }
        foreach($datasetuser as $k=>$v){
            $dpengguna['labels'][]=$k;
            $dpengguna['datasets'][0]['data'][]=count($datasetuser[$k]);
            $dpengguna['datasets'][0]['backgroundColor'][]=$coloruser[]=generate_color_one();
        }
        $color['coloruser']=$coloruser;

        $pemeriksa=Pemeriksa::orderBy('code')->get();
        $dpemeriksa=$datapemeriksa=$colorpemeriksa=array();
        foreach($pemeriksa as $k=>$v)
        {
            $datapemeriksa[$v->code][]=$v;
        }
        foreach($datapemeriksa as $k=>$v){
            $dpemeriksa['labels'][]=$k;
            $dpemeriksa['datasets'][0]['data'][]=count($datapemeriksa[$k]);
            $dpemeriksa['datasets'][0]['backgroundColor'][]=$colorpemeriksa[]=generate_color_one();
        }
        $color['colorpemeriksa']=$colorpemeriksa;
        // return $dpemeriksa;
        if(Auth::user()->flag==0)
            return redirect('force-logout')->with('error','Anda Tidak Mendapatkan Akses Login');

        // echo Auth::user()->level;
        if(Auth::user()->level=='0')
        {
            $user=User::all();
            $duser=array();
            foreach($user as $k=>$v)
            {
                $duser[$v->level][]=$v;
            }

            $jenistemuan=MasterTemuan::get()->count();
            $status=StatusRekomendasi::get()->count();
            $picunit=PICUnit::with('levelpic')->with('fak')->with('bid')->orderByRaw('RAND()')->limit(10)->get();
            $jenisaudit=JenisAudit::get()->count();
            return view('backend.pages.dashboard.admin')
                    ->with('jenistemuan',$jenistemuan)
                    ->with('datalevelpic',$datalevelpic)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('dpemeriksa',$dpemeriksa)
                    ->with('colorpemeriksa',$colorpemeriksa)
                    ->with('status',$status)
                    ->with('picunit',$picunit)
                    ->with('color',$color)
                    ->with('duser',$duser)
                    ->with('tahun',$thn)
                    ->with('dpengguna',$dpengguna)
                    ->with('jenisaudit',$jenisaudit);
        }
        elseif(Auth::user()->level=='auditor-junior')
        {
            // $lhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->with('dpemeriksa')->with('djenisaudit')->get();
            $tindaklanjut=TindakLanjutTemuan::with('lhp')->get();
            // return $lhp; 
            $datatl=$dtl=$dlhp=$colorlhp=$arraylhp=array();
            foreach($tindaklanjut as $k=>$v)
            {
                if(isset($v->lhp))
                {
                    if($v->lhp->user_input_id==Auth::user()->id)
                    {
                        // return $v->dtemuan->totemuan;
                        list($th,$bl,$tg)=explode('-',$v->lhp->tanggal_lhp);
                        if($th==$thn)
                        {
                            if($v->status_review_pic_1=='')
                                $status='Create oleh Unit Kerja';
                            else
                                $status=$v->status_review_pic_1;
    
                            $dlhp[$status][]=$v;
                            $arraylhp[$v->lhp->id]=$v->lhp->id;
                        }
                    }
                }
            }
            foreach($dlhp as $k=>$v)
            {
                $dtl['labels'][]=$k;
                $dtl['datasets'][0]['data'][]=isset($dlhp[$k]) ? count($dlhp[$k]) : 0;
                $dtl['datasets'][0]['backgroundColor'][]=$colorlhp[str_slug($k)]=generate_color_one();
                $datatl[str_slug($k)][]=$v;
            }

            // return $dtl;

            //Status Rekomendasi
            $status=StatusRekomendasi::get();
            $data_rekom=DataRekomendasi::with('dtemuan')->get();
            $rekomendasi=$rekom=$colorrekom=array();
            // return $data_rekom;

            foreach($data_rekom as $k=>$v)
            {
                if(isset($v->dtemuan->temuan))
                {
                    // return $v->dtemuan->totemuan;
                    list($th,$bl,$tg)=explode('-',$v->dtemuan->totemuan->tanggal_lhp);
                    if($th==$thn)
                    {
                        if(in_array($v->dtemuan->id_lhp,$arraylhp))
                            $rekomendasi[$v->status_rekomendasi_id][]=$v;
                    }
                }
            }
            $dstatus=array();
            // return $rekomendasi;
            foreach($status as $k=>$v)
            {
                $rekom['labels'][]=$v->rekomendasi;
                $rekom['datasets'][0]['data'][]=isset($rekomendasi[$v->id]) ? count($rekomendasi[$v->id]) : 0;
                $rekom['datasets'][0]['backgroundColor'][]=$colorrekom[str_slug($v->rekomendasi)]=generate_color_one();
                $dstatus[str_slug($v->rekomendasi)]=$v;
            }
            $color['colorrekom']=$colorrekom;
            $color['colorlhp']=$colorlhp;
            // return $dlhp;
            return view('backend.pages.dashboard.auditor-junior')
                    // ->with('lhp',$lhp)
                    ->with('dtl',$dtl)
                    ->with('status',$status)
                    ->with('dstatus',$dstatus)
                    ->with('rekom',$rekom)
                    ->with('color',$color)
                    ->with('tahun',$thn)
                    ->with('datatl',$datatl);
        }
        elseif(Auth::user()->level=='auditor-senior')
        {
            $lhp=DaftarTemuan::with('dpemeriksa')->with('djenisaudit')->get();
            $datalhp=array();
            foreach($lhp as $k=>$v)
            {
                $datalhp[str_slug($v->status_lhp)][]=$v;
            }
            $status=StatusRekomendasi::get()->count();
            return view('backend.pages.dashboard.auditor-senior')
                    ->with('lhp',$lhp)
                    ->with('status',$status)
                    ->with('color',$color)
                    ->with('datalhp',$datalhp);
        }
        elseif(Auth::user()->level=='pic-unit')
        {
            $lhp=DaftarTemuan::with('dpemeriksa')->with('djenisaudit')->get();
            $datalhp=array();
            foreach($lhp as $k=>$v)
            {
                $datalhp[str_slug($v->status_lhp)][]=$v;
            }
            $status=StatusRekomendasi::get()->count();
            return view('backend.pages.dashboard.pic-unit')
                    ->with('lhp',$lhp)
                    ->with('status',$status)
                    ->with('color',$color)
                    ->with('datalhp',$datalhp);
        }
    }
}
