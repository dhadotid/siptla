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
use App\Models\MappingRekomendasiNotifikasi;
use App\User;
use App\Models\Review;
use Auth;
use Validator;
use DB;
use Redirect;
class DataTemuanController extends Controller
{
    public function index(Request $request,$tahun=null,$statusrekom=null)
    {
        $priority=$key='';
        if(isset($request->key))
            $key = '?key='.$request->key;
        if(isset($request->priority))
            $priority= 'priority='.$request->priority;
        
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;

        $data['pemeriksa']=$pemeriksa=Pemeriksa::orderBy('pemeriksa')->get();
        $data['jenisaudit']=$jenisaudit=JenisAudit::orderBy('jenis_audit')->get();

        // return $data;

        if(Auth::user()->level=='pic-unit')
        {
                return view('backend.pages.data-lhp.pic-unit.index')
                    ->with('key', $key)
                    ->with('priority', $priority)
                    ->with('tahun',$thn)
                    ->with('data',$data)
                    ->with('statusrekom',$statusrekom)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('jenisaudit',$jenisaudit);
        }
        elseif(Auth::user()->level=='auditor-senior')
        {
                return view('backend.pages.data-lhp.auditor-senior.index')
                    ->with('key', $key)
                    ->with('priority', $priority)
                    ->with('tahun',$thn)
                    ->with('data',$data)
                    ->with('statusrekom',$statusrekom)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('jenisaudit',$jenisaudit);
        }
        elseif(Auth::user()->level=='super-user')
        {
                return view('backend.pages.data-lhp.super-user.index')
                    ->with('key', $key)
                    ->with('priority', $priority)
                    ->with('tahun',$thn)
                    ->with('data',$data)
                    ->with('statusrekom',$statusrekom)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('jenisaudit',$jenisaudit);
        }
        else
            return view('backend.pages.data-lhp.auditor-junior.index')
                    ->with('key', $key)
                    ->with('priority', $priority)
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

        if(Auth::user()->level=='auditor-senior')
        {
            return view('backend.pages.data-lhp.auditor-senior.index-semua')
                ->with('tahun',$thn)
                ->with('data',$data)
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
        }
        elseif(Auth::user()->level=='super-user')
        {
            return view('backend.pages.data-lhp.super-user.index-semua')
                ->with('tahun',$thn)
                ->with('data',$data)
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
        }
        else
        {
            return view('backend.pages.data-lhp.auditor-junior.index-semua')
                ->with('tahun',$thn)
                ->with('data',$data)
                ->with('pemeriksa',$pemeriksa)
                ->with('jenisaudit',$jenisaudit);
        }
        
    }
    public function deliver_lhp($idlhp){
        $temuan=DaftarTemuan::where('id',$idlhp)->first();
        if(Auth::user()->level=='auditor-junior'){
            $temuan->deliver_to       = 1;
            $temuan->save();

            $temuanData = DataTemuan::where('id_lhp',$idlhp)->first();
            $rekomData = DataRekomendasi::where('id_temuan',$temuanData->id)->get();
            $su = User::where('level', 'super-user')->get();
            foreach($rekomData as $k=>$v){

                $this->createNotification($idlhp, $v->id, $v->senior_user_id, $temuanData->id, 
                Auth::user()->name .' mengirim permintaan persetujuan rekomendasi');
                foreach($su as $a=>$s){
                    $this->createNotification($idlhp, $v->id, $s->id, $temuanData->id, Auth::user()->name .
                    ' mengirim permintaan persetujuan rekomendasi kepada senior');
                }

            }
        }
        
    }
    public function isNotificationRead($idlhp, $idrekom=-1, $id=-1){
        if($id != -1){
            //Notification already read, delete it!
            MappingRekomendasiNotifikasi::find($id)->delete();
        }
        return Redirect::to('data-temuan-lhp/'.$idlhp);

    }
    public function createNotification($idlhp, $idrekom, $userId, $idtemuan,$status=null){
        $notification = new MappingRekomendasiNotifikasi();
        $notification->id_lhp = $idlhp;
        $notification->id_rekomendasi = $idrekom;
        $notification->user_id = $userId;
        $notification->id_temuan = $idtemuan;
        $notification->status = $status;
        $notification->save();
    }
    public function lhp_edit($id)
    {
        $lhp=DaftarTemuan::selectRaw('*, daftar_lhp.id as id_lhp')->where('id',$id)->with('dpemeriksa')->first();
        return $lhp;
    }
    public function lhp_delete(Request $request,$id)
    {
        //cek data temuan dulu apakah ada temuan kalo ada failed delete
        $temuan = DataTemuan::where('id_lhp',$id)->first();
        if($temuan){
            return redirect('data-lhp')->with('error','Data LHP tidak bisa dihapus karena masih ada Temuan pada Data LHP');
        }

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
    public function data_lhp(Request $request, $tahun=null,$statusrekom=null)
    {
        $wh=array();
        $priority=$priorityid=$keybataswaktu='';
        $now=date('Y-m-d');
        if(isset($request->priority) && isset($request->key)){
            $priorityid = $request->priority;
            $priority= 'priority='.$request->priority;
            $keybataswaktu = 'key='.$request->key;
            $wh['data_temuan.level_resiko_id']=$priorityid;
        }
        
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;
        $wh['daftar_lhp.tahun_pemeriksa']=$thn;

        $drekom=$arraylhp=array();
        if($statusrekom!=null)
        {
            $datarekom=DataRekomendasi::where('status_rekomendasi_id',$statusrekom)->with('dtemuan')->get();
            foreach($datarekom as $k=>$v)
            {
                $drekom[$v->id_temuan][]=$v;
                if(isset($v->dtemuan->id_lhp))
                    $arraylhp[$v->dtemuan->id_lhp]=$v->dtemuan->id_lhp;
            }
        }

        if(Auth::user()->level=='auditor-junior')
        {
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.id', 'daftar_lhp.id as lhp_id', 'daftar_lhp.no_lhp',
                    'daftar_lhp.flag_tindaklanjut_id', 'daftar_lhp.kode_lhp', 'daftar_lhp.review',
                    'daftar_lhp.user_input_id','daftar_lhp.judul_lhp','daftar_lhp.pemeriksa_id','daftar_lhp.tanggal_lhp',
                    'daftar_lhp.tahun_pemeriksa', 'daftar_lhp.jenis_audit_id', 'daftar_lhp.status_lhp','daftar_lhp.review_lhp',
                    'daftar_lhp.create_flag', 'daftar_lhp.review_flag', 'daftar_lhp.publish_flag','daftar_lhp.tanggal_publish',
                    'daftar_lhp.flag_senior', 'daftar_lhp.flag_unit_kerja', 'daftar_lhp.deliver_to',
                    'daftar_lhp.created_at', 'daftar_lhp.updated_at', 'daftar_lhp.deleted_at'
                    )
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
                    if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                    }
                    if($request->key == 'melewati-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                    }
                    if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                    }
                    // ->where('data_rekomendasi.tanggal_penyelesaian', '=', $now)
            $query = $query->where($wh)
                    ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
                    ->where('daftar_lhp.user_input_id',Auth::user()->id)
                    ->whereNull('daftar_lhp.deleted_at')
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->groupBy('daftar_lhp.id')
                    ->orderBy('tanggal_lhp','desc')->get();

            $data = $sorted = array();
            foreach($query as $key=>$v){
                if($v->user_input_id == Auth::user()->id){
                    if(!isset($sorted[$v->id])){
                        $sorted[$v->id][] = $v;
                        array_push($data, $v);
                    }else{
                        $sorted[$v->id] = array($v);
                    }
                }
            }
            
        }
        elseif(Auth::user()->level=='auditor-senior')
        {
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.id', 'daftar_lhp.id as lhp_id', 'daftar_lhp.no_lhp',
                    'daftar_lhp.flag_tindaklanjut_id', 'daftar_lhp.kode_lhp', 'daftar_lhp.review',
                    'daftar_lhp.user_input_id','daftar_lhp.judul_lhp','daftar_lhp.pemeriksa_id','daftar_lhp.tanggal_lhp',
                    'daftar_lhp.tahun_pemeriksa', 'daftar_lhp.jenis_audit_id', 'daftar_lhp.status_lhp','daftar_lhp.review_lhp',
                    'daftar_lhp.create_flag', 'daftar_lhp.review_flag', 'daftar_lhp.publish_flag','daftar_lhp.tanggal_publish',
                    'daftar_lhp.flag_senior', 'daftar_lhp.flag_unit_kerja', 'daftar_lhp.deliver_to',
                    'data_rekomendasi.senior_user_id as senior_user_id'
                    )
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
                    if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                    }
                    if($request->key == 'melewati-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                    }
                    if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                    }
            $query = $query->where('daftar_lhp.tahun_pemeriksa',$thn)
                    ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                    ->where($wh)
                    ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
                    ->whereNull('daftar_lhp.deleted_at')
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')
                    ->get();
                    //->groupBy('daftar_lhp.id')
                    // return $query;
            $data = $sorted = array();
            foreach($query as $key=>$v){
                if($v->deliver_to != 0 || $v->publish_flag == 1)
                    if($v->senior_user_id == Auth::user()->id){
                        if(!isset($sorted[$v->id])){
                            $sorted[$v->id][] = $v;
                            array_push($data, $v);
                        }else{
                            $sorted[$v->id] = array($v);
                        }
                    }
            }
            // return $data;

            foreach ($data as $key => $value) {
                $count[$value->lhp_id]  = DataTemuan::where('id_lhp', $value->lhp_id)->count();
            }

            return view('backend.pages.data-lhp.auditor-senior.data')
                ->with('keybataswaktu', $keybataswaktu)
                ->with('priority', $priority)
                ->with('data',$data)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('count',$count)
                ->with('drekom',$drekom);
                // $data=DaftarTemuan::selectRaw('*,data_rekomendasi.id as idrekom')->join('pemeriksa','pemeriksa.id','=','daftar_lhp.pemeriksa_id')
                //                 ->join('jenis_audit','jenis_audit.id','=','daftar_lhp.jenis_audit_id')
                //                 ->join('data_temuan','data_temuan.id_lhp','=','daftar_lhp.id')
                //                 ->join('data_rekomendasi','data_rekomendasi.id_temuan','=','data_temuan.id')
                //                 ->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                //                 ->orderBy('daftar_lhp.tanggal_lhp','desc')->get();
        }
        elseif(Auth::user()->level=='super-user')
        {
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.id', 'daftar_lhp.id as lhp_id', 'daftar_lhp.no_lhp',
                    'daftar_lhp.flag_tindaklanjut_id', 'daftar_lhp.kode_lhp', 'daftar_lhp.review',
                    'daftar_lhp.user_input_id','daftar_lhp.judul_lhp','daftar_lhp.pemeriksa_id','daftar_lhp.tanggal_lhp',
                    'daftar_lhp.tahun_pemeriksa', 'daftar_lhp.jenis_audit_id', 'daftar_lhp.status_lhp','daftar_lhp.review_lhp',
                    'daftar_lhp.create_flag', 'daftar_lhp.review_flag', 'daftar_lhp.publish_flag','daftar_lhp.tanggal_publish',
                    'daftar_lhp.flag_senior', 'daftar_lhp.flag_unit_kerja', 'daftar_lhp.deliver_to',
                    DB::raw('MIN(data_rekomendasi.status_rekomendasi_id) as senior_publish'))
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
                    if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                    }
                    if($request->key == 'melewati-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                    }
                    if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                    }
                    $query = $query->where($wh)
                            ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
                            ->whereNull('daftar_lhp.deleted_at')
                            ->groupBy('lhp_id')
                            ->with('dpemeriksa')
                            ->with('djenisaudit')
                            ->orderBy('tanggal_lhp','desc')
                            ->get();

            
            $data = array();
            foreach($query as $key=>$v){
                if($v->deliver_to != 0 || $v->publish_flag == 1)
                    array_push($data, $v);
            }

            foreach ($data as $key => $value) {
                $dataTemuan = DataTemuan::select('id')->where('id_lhp', $value->lhp_id)->get();
                $count[$value->lhp_id]  = $dataTemuan->count();
                $rekom_publish[$value->lhp_id] = DataRekomendasi::whereIn('id_temuan', $dataTemuan)->where('senior_publish', 0)->count();
            }

            return view('backend.pages.data-lhp.super-user.data')
                ->with('keybataswaktu', $keybataswaktu)
                ->with('priority', $priority)
                ->with('data',$data)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('count',$count)
                ->with('drekom',$drekom)
                ->with('rekom_publish', $rekom_publish);
        }
        elseif(Auth::user()->level=='pic-unit')
        {
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
            ->select('daftar_lhp.id', 'daftar_lhp.id as lhp_id', 'daftar_lhp.no_lhp',
                    'daftar_lhp.flag_tindaklanjut_id', 'daftar_lhp.kode_lhp', 'daftar_lhp.review',
                    'daftar_lhp.user_input_id','daftar_lhp.judul_lhp','daftar_lhp.pemeriksa_id','daftar_lhp.tanggal_lhp',
                    'daftar_lhp.tahun_pemeriksa', 'daftar_lhp.jenis_audit_id', 'daftar_lhp.status_lhp','daftar_lhp.review_lhp',
                    'daftar_lhp.create_flag', 'daftar_lhp.review_flag', 'daftar_lhp.publish_flag','daftar_lhp.tanggal_publish',
                    'daftar_lhp.flag_senior', 'daftar_lhp.flag_unit_kerja', 'daftar_lhp.deliver_to',
                    DB::raw('MIN(data_rekomendasi.status_rekomendasi_id) as senior_publish'))
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
                    if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                    }
                    if($request->key == 'melewati-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                    }
                    if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                        $query = $query->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                    }
            $query = $query->where($wh)
                    ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
                    ->groupBy('lhp_id')
                    ->whereNull('daftar_lhp.deleted_at')
                    // ->where('daftar_lhp.flag_unit_kerja',1)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();
            $data = array();
            foreach($query as $key=>$v){
                if($v->deliver_to != 0 || $v->publish_flag == 1)
                    array_push($data, $v);
            }
            
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

            // return $data;

            return view('backend.pages.data-lhp.pic-unit.data')
                ->with('keybataswaktu', $keybataswaktu)
                ->with('priority', $priority)
                ->with('data',$data)
                ->with('filteridlhp',$filteridlhp)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('drekom',$drekom);
        }
        else
        {
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->where($wh)
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')->get();
        }
        // return $statusrekom;

        foreach ($data as $key => $value) {
            $count[$value->lhp_id]  = DataTemuan::where('id_lhp', $value->lhp_id)->count();
        }
        // return $statusrekom;
        

        return view('backend.pages.data-lhp.auditor-junior.data')
                ->with('keybataswaktu', $keybataswaktu)
                ->with('priority', $priority)
                ->with('data',$data)
                ->with('arraylhp',$arraylhp)
                ->with('statusrekom',$statusrekom)
                ->with('count',$count)
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
        elseif(Auth::user()->level=='super-user')
        {
        // return $drekomendasi;
            return view('backend.pages.data-lhp.super-user.detail-lhp')
                    ->with('temuan',$tm)
                    ->with('offset',$offset)
                    ->with('statusrekom',$statusrekom)
                    ->with('jlhtemuan',$jlhtemuan)
                    ->with('id',$id)
                    ->with('drekomendasi',$drekomendasi)
                    ->with('data',$data);
        }
        else
        {
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
        $insert->no_lhp = $request->kode_lhp;
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
        $update->no_lhp = $request->kode_lhp;
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
    
    public function data_temuan_lhp(Request $request, $idlhp,$statusrekom=null)
    {
        // $now=date('Y-m-d');
        $wh = array();
        $keyparam = '';
        if(isset($request->key) && isset($request->priority)){
            $wh['data_temuan.level_resiko_id']=$request->priority;
            $keyparam = '_?key='.$request->key.'_priority='.$request->priority;
        }
        if(Auth::user()->level=='auditor-junior'){
                    $dt['data']=$data=DaftarTemuan::where('daftar_lhp.id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
                    //where('user_input_id',Auth::user()->id)
        }else{
            $dt['data']=$data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        }

        $dt['jenistemuan']=MasterTemuan::orderBy('temuan')->get();
        $dt['picunit']=PICUnit::with('levelpic')->orderBy('nama_pic')->get();
        $dt['levelresiko']=LevelResiko::orderBy('level_resiko')->get();
        $dt['jangkawaktu']=$jangkawaktu=JangkaWaktu::orderBy('jangka_waktu')->get();
        $dt['statusrekomendasi']=$statusrekomendasi=StatusRekomendasi::orderBy('rekomendasi')->get();
        
        if(Auth::user()->level=='auditor-senior'){
            $rekomQuery=DataRekomendasi::where('data_rekomendasi.senior_user_id', Auth::id())
            ->join('data_temuan', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id')
            ->where($wh);
            if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
            }
            if($request->key == 'melewati-batas-waktu-penyelesaian'){
                $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
            }
            if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
            }
            $rekomQuery = $rekomQuery->whereNull('data_rekomendasi.deleted_at')
            // ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
            ->with('jenistemuan')->with('picunit1')->with('picunit2')->with('jangkawaktu')->with('statusrekomendasi')->get();
            $rekom = $sorted = array();
            foreach($rekomQuery as $key=>$v){
                if($v->senior_user_id == Auth::user()->id){
                    array_push($rekom, $v);
                }
            }
            // return $rekom;
        }else{
            $rekom=DataRekomendasi::join('data_temuan', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id')
            ->where($wh);
            if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
            }
            if($request->key == 'melewati-batas-waktu-penyelesaian'){
                $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
            }
            if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
            }
            $rekom = $rekom->whereNull('data_rekomendasi.deleted_at')
            // ->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
            ->with('jenistemuan')->with('picunit1')->with('picunit2')->with('jangkawaktu')->with('statusrekomendasi')->get();
            
            
        }
        
        $rekomendasi=$drekom=$arraytemuanid=array();
        foreach($rekom as $k=>$v)
        {
            $rekomendasi[$v->id_temuan][]=$v;
                
            if($statusrekom!=null)
            {
                if($v->status_rekomendasi_id==$statusrekom)
                {
                    $arraytemuanid[$v->id_temuan]=$v->id_temuan;
                    $drekom[$v->id_temuan][$v->status_rekomendasi_id][]=$v;
                }
            }
            else
                $drekom[$v->id_temuan][$v->status_rekomendasi_id][]=$v;
        }
        
        // return $arraytemuanid;
        if(count($arraytemuanid)!=0)
        {
            $temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')
                            ->with('jenistemuan')
                            ->with('picunit')
                            ->with('levelresiko')
                            ->where('id_lhp',$idlhp)
                            ->whereIn('data_temuan.id',$arraytemuanid)
                            ->get();
        }else{
            if(Auth::user()->level=='auditor-senior'){
                $temuanQuery=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')
                ->leftjoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id')
                ->whereNull('data_rekomendasi.deleted_at')
                ->select('data_temuan.*','data_temuan.id as temuan_id', 'data_rekomendasi.senior_user_id')
                ->with('jenistemuan')->with('picunit')->with('levelresiko')->where('id_lhp',$idlhp)->get();

                $temuan = $sorted = array();
                foreach($temuanQuery as $key=>$v){
                    if($v->senior_user_id == Auth::user()->id){
                        if(!isset($sorted[$v->id])){
                            $sorted[$v->id][] = $v;
                            array_push($temuan, $v);
                        }else{
                            $sorted[$v->id] = array($v);
                        }
                    }
                    // else if(DataRekomendasi::where('id_temuan',$v->temuan_id)->count() == 0){
                    //     $sorted[$v->id][] = $v;
                    //     array_push($temuan, $v);
                    // }
                }
            }else{
                $temuan=DataTemuan::selectRaw('*,data_temuan.id as temuan_id')
                ->with('jenistemuan')->with('picunit')->with('levelresiko')->where('id_lhp',$idlhp)->get();
            }

        }

        $dtem=array();
        foreach($temuan as $ktem=>$vtem)
        {
            $dtem[$vtem->temuan_id]=$vtem;
        }
        
        $dt['rekomendasi']=$rekomendasi;
        $dt['temuan']=$dtem;

        $senior=User::where('level','auditor-senior')->get();
        
        if($data)
        {
            $jlhsetujurekom=$this->cekstrekomsenior();

            if(Auth::user()->level=='auditor-senior')
            {
                $junior=User::where('level','auditor-junior')->get();
                return view('backend.pages.data-lhp.auditor-senior.temuan-new')
                    ->with('keyparam', $keyparam)
                    ->with('dt',$dt)
                    ->with('idlhp',$idlhp)
                    ->with('drekom',$drekom)
                    ->with('jlhsetujurekom',$jlhsetujurekom)
                    ->with('rekomendasi',$rekomendasi)
                    ->with('senior',$senior)
                    ->with('junior',$junior)
                    ->with('temuan',$temuan)
                    ->with('jangkawaktu',$jangkawaktu)
                    ->with('statusrekomendasi',$statusrekomendasi)
                    ->with('statusrekom',$statusrekom)
                    ->with('data',$data);
            }
            elseif(Auth::user()->level=='super-user')
            {
                return view('backend.pages.data-lhp.super-user.temuan-new')
                    ->with('keyparam', $keyparam)
                    ->with('dt',$dt)
                    ->with('idlhp',$idlhp)
                    ->with('drekom',$drekom)
                    ->with('jlhsetujurekom',$jlhsetujurekom)
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
                // return json_encode($temuan);
                return view('backend.pages.data-lhp.auditor-junior.temuan-new')
                    ->with('keyparam', $keyparam)
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

        if(Auth::user()->level=='auditor-senior')
        {
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
        }
        else
        {
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
        }
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
        $temuan->status_lhp         = 'Publish LHP';
        $temuan->publish_flag       = 1;
        $temuan->tanggal_publish    = date('Y-m-d');
        $temuan->save();

        if(Auth::user()->level=='super-user'){
            $temuanData = DataTemuan::where('id_lhp',$idlhp)->first();
            $rekomData = DataRekomendasi::where('id_temuan',$temuanData->id)->get();

            foreach($rekomData as $k=>$v){
                $pic = explode(',', $v->pic_2_temuan_id);
                array_push($pic, $v->pic_1_temuan_id);

                array_push($pic, $temuanData->pic_temuan_id);

                foreach($pic as $s=>$p){
                    if($v!='')
                        $this->createNotification($temuan->id, $v->id, $p, $temuanData->id,Auth::user()->name .' telah menyetujui data LHP');
                }

                $this->createNotification($temuan->id, $v->id, $temuan->user_input_id, $temuanData->id,
                Auth::user()->name .' telah menyetujui data LHP');

                $this->createNotification($temuan->id, $v->id, $temuan->senior_user_id, $temuanData->id,
                Auth::user()->name .' telah menyetujui data LHP');
            }
        }

        $request        = new Request();
        $request->judul = 'Publish LHP';
        $request->type  = 'publish_lhp';
        $request->idlhp = $idlhp;

        $this->sendEmail($request);
    }
}
