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
use App\Models\LevelResiko;
use App\Models\Bidang;
use App\User;
class DashboardController extends Controller
{
    public function pimpinanTest($tahun=null){
        $now=date('Y-m-d');
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $temuanPerbidang = DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                        ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                        ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                        ->join('pic_unit', 'pic_unit.id', '=','data_temuan.pic_temuan_id')
                        ->join('level_pic', 'level_pic.id', '=', 'pic_unit.level_pic')
                        ->join('bidang', 'bidang.id', '=', 'pic_unit.bidang')
                        ->join('status_rekomendasi', 'status_rekomendasi.id', '=', 'data_rekomendasi.status_rekomendasi_id')
                        ->where('daftar_lhp.tahun_pemeriksa',$thn)
                        ->whereNull('data_rekomendasi.deleted_at')
                        ->orderBy('data_rekomendasi.nomor_rekomendasi')
                        ->get();
        $statusRekomendasi = StatusRekomendasi::all();
        $levelPIC = LevelPIC::all();
        $dataBidang = Bidang::all();
        $temuans = $bidangfinal = array();
        $totalDataPerbidang = 0;
        
        foreach($statusRekomendasi as $s=>$r){
            $temuans['datasets'][$s]['label']=$r->rekomendasi;
        }
        foreach($dataBidang as $k=>$v){
            array_push($bidangfinal,$v->nama_bidang);
        }
        foreach($levelPIC as $r=>$s){
            if($s->id != 1 && $s->flag == 1){
                array_push($bidangfinal,$s->nama_level);
            }
        }
        array_push($bidangfinal,'Total');
        foreach($bidangfinal as $a=>$s){
            $temuans['labels'][]=$s;
            foreach($statusRekomendasi as $q=>$r){
                $totalData = 0;
                $iniUntukTotal = 0;
                $temuans['datasets'][$q]['backgroundColor']=generate_color_status($r->id);
                foreach($temuanPerbidang as $k=>$v){
                    if($s == $v->nama_bidang && $r->rekomendasi == $v->rekomendasi){
                        $totalData++;
                    }else if($s == $v->nama_level && $r->rekomendasi == $v->rekomendasi){
                        $totalData++;
                    }elseif($s == 'Total' && $r->rekomendasi == $v->rekomendasi){
                        $totalData++;
                    }
                }
                $temuans['datasets'][$q]['data'][]=$totalData;
                // if($s == 'Total'){
                //     $temuans['datasets'][$q]['data']= $iniUntukTotal;//[count($temuanPerbidang)];
                // }else{
                    
                // }
            }
        }
        
        // GET TOTAL TEMUAN BERJALAN DI TAHUN
        $temuanComplete=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                // ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$thn)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        $totalselesai=$totalbelumselesai=0;
        $jsonTotalTemuan=$totaldata=array();
        foreach($temuanComplete as $k=>$v){
            if($v->status_rekomendasi_id==1){
                $totalselesai++;
            }else{
                $totalbelumselesai++;
            }
        }
        $totaldata = [$totalselesai, $totalbelumselesai];
        foreach(total_data() as $k=>$v){
            $jsonTotalTemuan['labels'][]=$v;
            $jsonTotalTemuan['datasets'][0]['data'][]=$totaldata[$k];
            $jsonTotalTemuan['datasets'][0]['backgroundColor'][]=generate_color_total_data($k);
        }

        //GET TOTAL TEMUAN SPI ATAU BUKAN
        $totalspi=$totalbukanspi=0;
        $jsonPemeriksaInternal=$totalpemeriksainternal=array();
        foreach($temuanComplete as $k=>$v){
            if($v->pemeriksa_id == 3){
                if($v->status_rekomendasi_id==1){
                    $totalspi++;
                }else $totalbukanspi++;
            }
        }
        $totalpemeriksainternal = [$totalspi, $totalbukanspi];
        foreach(total_data() as $k=>$v){
            $jsonPemeriksaInternal['labels'][]=$v;
            $jsonPemeriksaInternal['datasets'][0]['data'][]=$totalpemeriksainternal[$k];
            $jsonPemeriksaInternal['datasets'][0]['backgroundColor'][]=generate_color_total_data($k);
        }

        //GET TOTAL TEMUAN External ATAU BUKAN
        $totalexternal=$totalbukanexternal=0;
        $jsonPemeriksaExternal=$totalpemeriksaexternal=array();
        foreach($temuanComplete as $k=>$v){
            if($v->pemeriksa_id != 3){
                if($v->status_rekomendasi_id==1)
                    $totalexternal++;
                else $totalbukanexternal++;
            }
        }
        $totalpemeriksaexternal = [$totalexternal, $totalbukanexternal];
        foreach(total_data() as $k=>$v){
            $jsonPemeriksaExternal['labels'][]=$v;
            $jsonPemeriksaExternal['datasets'][0]['data'][]=$totalpemeriksaexternal[$k];
            $jsonPemeriksaExternal['datasets'][0]['backgroundColor'][]=generate_color_total_data($k);
        }

        //Rekomendasi Yang Overdue
        $rekomendasiData = DataRekomendasi::with('statusrekomendasi')
                            ->select('data_rekomendasi.*', 'level_resiko.level_resiko as level_resiko', 'level_resiko.id as id_resiko')
                            ->join('data_temuan', 'data_temuan.id', '=', 'data_rekomendasi.id_temuan')
                            ->join('level_resiko', 'data_temuan.level_resiko_id', '=', 'level_resiko.id')->get();
        $rekomJson=$totalResiko=array();
        $totallow=$totalmed=$totalhight=0;
        foreach($rekomendasiData as $k=>$v){
            if($v->statusrekomendasi['id'] == 2){
                if($now > $v->tanggal_penyelesaian){
                    if($v->id_resiko == 2){
                        $totallow++;
                    }elseif($v->id_resiko == 3){
                        $totalmed++;
                    }elseif($v->id_resiko == 4){
                        $totalhight++;
                    }
                }
            }
        }
        $totalResiko = [$totallow, $totalmed, $totalhight];
        $levelresiko = LevelResiko::all();
        foreach($levelresiko as $k=>$v){
            if($v->id != 1){
                $rekomJson['labels'][]='Overdue '.($k+1).' - '.$v->level_resiko;
                $rekomJson['datasets'][0]['data']=$totalResiko;
                $rekomJson['datasets'][0]['backgroundColor'][]=generate_color_tindak_lanjut($k);
            }
        }

        //Monitoring Tindak Lanjut
        $temuanData=DaftarTemuan::selectRaw('*,data_rekomendasi.id as id_rekom')
                                ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                                ->join('data_rekomendasi','data_temuan.id','=','data_rekomendasi.id_temuan')
                                // ->join('level_resiko', 'data_temuan.level_resiko_id', '=', 'level_resiko.id')
                                // ->where('daftar_lhp.status_lhp','Publish LHP')
                                ->where('daftar_lhp.tahun_pemeriksa',$thn)
                                ->whereNull('data_rekomendasi.deleted_at')
                                ->orderBy('data_rekomendasi.nomor_rekomendasi')
                                ->get();
        $arrayrekomid=$rekomendasi=$temuanJson=$totalTindaklanjut=array();
        foreach($temuanData as $k=>$v)
        {
            $arrayrekomid[$v->id_rekom]=$v->id_rekom;
        }
        $get_tl=TindakLanjutTemuan::whereIn('rekomendasi_id',$arrayrekomid)->get();
        $tindaklanjut=array();
        foreach($get_tl as $k=>$v)
        {
            $tindaklanjut[$v->rekomendasi_id][]=$v;
        }
        $totalCreateUnitKerja=$totalBelumDireview=$totalSedangDireview=$totalSudahDireview=$totalPublish=0;
        foreach($temuanData as $k=>$v){
            if(!isset($tindaklanjut[$v->id]) ){
                $totalCreateUnitKerja++;
            }elseif(isset($tindaklanjut[$v->id]) && $v->review_spi =='' && $v->published!=1){
                $totalBelumDireview++;
            }elseif(isset($tindaklanjut[$v->id]) && $v->review_spi !='' && $v->published==0){
                $totalSedangDireview++;
            }elseif(isset($tindaklanjut[$v->id]) && $v->review_spi =='' && $v->published==0){
                $totalSudahDireview++;
            }elseif(isset($tindaklanjut[$v->id]) && $v->review_spi !='' && $v->published==1){
                $totalPublish++;
            }
        }
        $totalTindaklanjut = [$totalCreateUnitKerja, $totalBelumDireview, $totalSedangDireview, $totalSudahDireview, $totalPublish];
        foreach(status_lhp() as $k=>$v){
            $temuanJson['labels'][]=$v;
            $temuanJson['datasets'][0]['data'][] = $totalTindaklanjut[$k];
            $temuanJson['datasets'][0]['backgroundColor'][]=generate_color_tindak_lanjut($k);
        }
        // return $jsonTotalTemuan;
        return view('backend.pages.dashboard.pimpinan')
                ->with('tahun',$thn)
                ->with('temuans',$temuans)
                ->with('jsonTemuan', $temuanJson)
                ->with('rekomJson', $rekomJson)
                ->with('jsonTotalTemuan', $jsonTotalTemuan)
                ->with('jsonPemeriksaInternal', $jsonPemeriksaInternal)
                ->with('jsonPemeriksaExternal', $jsonPemeriksaExternal);
    }

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
            $tindaklanjut=TindakLanjutTemuan::with('lhp')->get();
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

            $doverdue=array();
            $bataswaktu=bataswaktu();
            $colorbataswaktu=array();
            foreach($bataswaktu as $k=>$v)
            {
                $doverdue['labels'][]=$bataswaktu[$k];
                $doverdue['datasets'][0]['data'][]=isset($overdue[$k]) ? count($overdue[$k]) : 0;
                $doverdue['datasets'][0]['backgroundColor'][]=$colorbataswaktu[($k)]=generate_color_one();
            }
            $color['colorbataswaktu']=$colorbataswaktu;

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
                    // ->with('overdue',$overdue)
                    ->with('dtl',$dtl)
                    ->with('doverdue',$doverdue)
                    ->with('datatl',$datatl)

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
        elseif(Auth::user()->level=='super-user')
        {
            $tindaklanjut=TindakLanjutTemuan::with('lhp')->get();
            $datatl=$dtl=$dlhp=$colorlhp=$arraylhp=array();
            foreach($tindaklanjut as $k=>$v)
            {
                if(isset($v->lhp))
                {
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
            
            foreach($dlhp as $k=>$v)
            {
                $dtl['labels'][]=$k;
                $dtl['datasets'][0]['data'][]=isset($dlhp[$k]) ? count($dlhp[$k]) : 0;
                $dtl['datasets'][0]['backgroundColor'][]=$colorlhp[str_slug($k)]=generate_color_one();
                $datatl[str_slug($k)][]=$v;
            }

            $doverdue=array();
            $bataswaktu=bataswaktu();
            $colorbataswaktu=array();
            foreach($bataswaktu as $k=>$v)
            {
                $doverdue['labels'][]=$bataswaktu[$k];
                $doverdue['datasets'][0]['data'][]=isset($overdue[$k]) ? count($overdue[$k]) : 0;
                $doverdue['datasets'][0]['backgroundColor'][]=$colorbataswaktu[($k)]=generate_color_one();
            }
            $color['colorbataswaktu']=$colorbataswaktu;

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
                    // ->with('overdue',$overdue)
                    ->with('dtl',$dtl)
                    ->with('doverdue',$doverdue)
                    ->with('datatl',$datatl)

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
            $rekomendasi=$rekom=$colorrekom=$overdue=array();
            // return $data_rekom;

            foreach($data_rekom as $k=>$v)
            {
                if(isset($v->dtemuan->temuan))
                {
                    // return $v->dtemuan->totemuan;
                    if($v->dtemuan->totemuan->user_input_id==Auth::user()->id)
                    {
                        list($th,$bl,$tg)=explode('-',$v->dtemuan->totemuan->tanggal_lhp);
                        if($th==$thn)
                        {
                            if(in_array($v->dtemuan->id_lhp,$arraylhp))
                                $rekomendasi[$v->status_rekomendasi_id][]=$v;

                            if($v->tanggal_penyelesaian!='')
                            {
                                $tgl_penyelsaian=$v->tanggal_penyelesaian;
                                $now=date('Y-m-d');
                                if($now==$tgl_penyelsaian)
                                {
                                    $overdue['sudah-masuk-batas-waktu-penyelesaian'][]=$v;
                                }
                                elseif($now>$tgl_penyelsaian)
                                {
                                    $overdue['melewati-batas-waktu-penyelesaian'][]=$v;
                                }
                                elseif($now<$tgl_penyelsaian)
                                {
                                    $overdue['belum-masuk-batas-waktu-penyelesaian'][]=$v;
                                }
                            }
                        }
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
            // return $overdue;
            $doverdue=array();
            // return $rekomendasi;
            $bataswaktu=bataswaktu();
            $colorbataswaktu=array();
            foreach($bataswaktu as $k=>$v)
            {
                $doverdue['labels'][]=$bataswaktu[$k];
                $doverdue['datasets'][0]['data'][]=isset($overdue[$k]) ? count($overdue[$k]) : 0;
                $doverdue['datasets'][0]['backgroundColor'][]=$colorbataswaktu[($k)]=generate_color_one();
            }
            $color['colorbataswaktu']=$colorbataswaktu;
            // return $doverdue;
            return view('backend.pages.dashboard.auditor-junior')
                    // ->with('lhp',$lhp)
                    ->with('dtl',$dtl)
                    ->with('status',$status)
                    ->with('dstatus',$dstatus)
                    ->with('overdue',$overdue)
                    ->with('doverdue',$doverdue)
                    ->with('rekom',$rekom)
                    ->with('color',$color)
                    ->with('tahun',$thn)
                    ->with('datatl',$datatl);
        }
        elseif(Auth::user()->level=='auditor-senior' || Auth::user()->level=='pic-unit')
        {
            // $lhp=DaftarTemuan::with('dpemeriksa')->with('djenisaudit')->get();
            // $datalhp=array();
            // foreach($lhp as $k=>$v)
            // {
            //     $datalhp[str_slug($v->status_lhp)][]=$v;
            // }
            // $status=StatusRekomendasi::get()->count();
            // return view('backend.pages.dashboard.auditor-senior')
            //         ->with('lhp',$lhp)
            //         ->with('status',$status)
            //         ->with('color',$color)
            //         ->with('datalhp',$datalhp);
            // $lhp=DaftarTemuan::where('user_input_id',Auth::user()->id)->with('dpemeriksa')->with('djenisaudit')->get();
            $tindaklanjut=TindakLanjutTemuan::with('lhp')->get();
            // return $lhp; 
            $datatl=$dtl=$dlhp=$colorlhp=$arraylhp=array();
            foreach($tindaklanjut as $k=>$v)
            {
                if(isset($v->lhp))
                {
                    // if($v->lhp->user_input_id==Auth::user()->id)
                    // {
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
                    // }
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
            $rekomendasi=$rekom=$colorrekom=$overdue=array();
            // return $data_rekom;

            foreach($data_rekom as $k=>$v)
            {
                if(isset($v->dtemuan->temuan))
                {
                    list($th,$bl,$tg)=explode('-',$v->dtemuan->totemuan->tanggal_lhp);
                    if($th==$thn)
                    {
                        if(in_array($v->dtemuan->id_lhp,$arraylhp))
                            $rekomendasi[$v->status_rekomendasi_id][]=$v;

                        if($v->tanggal_penyelesaian!='')
                        {
                            $tgl_penyelsaian=$v->tanggal_penyelesaian;
                            $now=date('Y-m-d');
                            if($now==$tgl_penyelsaian)
                            {
                                $overdue['sudah-masuk-batas-waktu-penyelesaian'][]=$v;
                            }
                            elseif($now>$tgl_penyelsaian)
                            {
                                $overdue['melewati-batas-waktu-penyelesaian'][]=$v;
                            }
                            elseif($now<$tgl_penyelsaian)
                            {
                                $overdue['belum-masuk-batas-waktu-penyelesaian'][]=$v;
                            }
                        }
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
            // return $overdue;
            $doverdue=array();
            // return $rekomendasi;
            $bataswaktu=bataswaktu();
            $colorbataswaktu=array();
            foreach($bataswaktu as $k=>$v)
            {
                $doverdue['labels'][]=$bataswaktu[$k];
                $doverdue['datasets'][0]['data'][]=isset($overdue[$k]) ? count($overdue[$k]) : 0;
                $doverdue['datasets'][0]['backgroundColor'][]=$colorbataswaktu[($k)]=generate_color_one();
            }
            $color['colorbataswaktu']=$colorbataswaktu;
            // return $doverdue;
            return view('backend.pages.dashboard.auditor-senior')
                    // ->with('lhp',$lhp)
                    ->with('dtl',$dtl)
                    ->with('status',$status)
                    ->with('overdue',$overdue)
                    ->with('dstatus',$dstatus)
                    ->with('doverdue',$doverdue)
                    ->with('rekom',$rekom)
                    ->with('color',$color)
                    ->with('tahun',$thn)
                    ->with('datatl',$datatl);
        }
        elseif(Auth::user()->level=='pic-unit')
        {
            // $lhp=DaftarTemuan::with('dpemeriksa')->with('djenisaudit')->get();
            // $datalhp=array();
            // foreach($lhp as $k=>$v)
            // {
            //     $datalhp[str_slug($v->status_lhp)][]=$v;
            // }
            // $status=StatusRekomendasi::get()->count();
            // return view('backend.pages.dashboard.pic-unit')
            //         ->with('lhp',$lhp)
            //         ->with('status',$status)
            //         ->with('color',$color)
            //         ->with('datalhp',$datalhp);
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
            return view('backend.pages.dashboard.pic-unit')
                    // ->with('lhp',$lhp)
                    ->with('dtl',$dtl)
                    ->with('status',$status)
                    ->with('dstatus',$dstatus)
                    ->with('rekom',$rekom)
                    ->with('color',$color)
                    ->with('tahun',$thn)
                    ->with('datatl',$datatl);
        }
    }
}
