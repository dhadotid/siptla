<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarTemuan;
use App\Models\DataTemuan;
use App\Models\DataRekomendasi;
use App\Models\TindakLanjutRincian;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;
use App\Models\PICUnit;
use Auth;
use DB;
class RepositoryController extends Controller
{
    public function index(Request $request,$tahun=null){
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;
        
            if(Auth::user()->level=='auditor-senior')
            {
                    return view('backend.pages.repository.auditor-senior.index')
                        ->with('tahun',$thn);
            }
            elseif(Auth::user()->level=='super-user')
            {
                    return view('backend.pages.repository.super-user.index')
                        ->with('tahun',$thn);
            }
            else
                return view('backend.pages.repository.auditor-junior.index')
                        ->with('tahun',$thn);
    }

    public function data(Request $request, $tahun=null){
        if($tahun==null)
            $thn=date('Y');
        else
            $thn=$tahun;
        $wh['daftar_lhp.tahun_pemeriksa']=$thn;

        if(Auth::user()->level=='auditor-senior')
        {
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.*', 'daftar_lhp.id as lhp_id','data_rekomendasi.senior_user_id as senior_user_id')
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
            $query = $query->where('data_rekomendasi.senior_user_id',Auth::user()->id)
                    // ->where('daftar_lhp.status_lhp','Publish LHP')
                    ->where($wh)
                    ->whereNull('daftar_lhp.deleted_at')
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->orderBy('tanggal_lhp','desc')
                    ->get();
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
            return view('backend.pages.repository.auditor-senior.data')
                ->with('data',$data);
        }elseif(Auth::user()->level=='auditor-junior'){
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.*', 'daftar_lhp.id as lhp_id')
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
            $data = $data->where($wh)
                    ->where('daftar_lhp.user_input_id',Auth::user()->id)
                    ->whereNull('daftar_lhp.deleted_at')
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->groupBy('daftar_lhp.id')
                    ->orderBy('tanggal_lhp','desc')->get();

            return view('backend.pages.repository.auditor-junior.data')
                    ->with('data',$data);
        }elseif(Auth::user()->level=='super-user'){
            $query=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.*', 'daftar_lhp.id as lhp_id',
                    DB::raw('MIN(data_rekomendasi.status_rekomendasi_id) as senior_publish'))
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
                    $query = $query->where($wh)
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
            return view('backend.pages.repository.super-user.data')
                ->with('data',$data);
        }elseif(Auth::user()->level=='pic-unit'){
            $picUnit = PICUnit::where('id','=',Auth::user()->pic_unit_id)->pluck('id')->first();
            $data=DaftarTemuan::selectRaw('*,daftar_lhp.id as lhp_id')
                    ->select('daftar_lhp.*', 'daftar_lhp.id as lhp_id', 'data_rekomendasi.pic_1_temuan_id', 'data_rekomendasi.pic_2_temuan_id')
                    ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                    ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id');
            $data = $data->where($wh)
                    ->whereNull('daftar_lhp.deleted_at')
                    ->where('data_rekomendasi.pic_1_temuan_id','=',$picUnit)
                    ->orWhere('data_rekomendasi.pic_2_temuan_id','=','%'.$picUnit.'%')
                    ->with('dpemeriksa')
                    ->with('djenisaudit')
                    ->groupBy('daftar_lhp.id')
                    ->orderBy('tanggal_lhp','desc')->get();
            return view('backend.pages.repository.auditor-senior.data')
                ->with('data',$data);
        }
    }

    public function detail_repository(Request $request, $idlhp){
        if(Auth::user()->level=='auditor-junior'){
            $data=DaftarTemuan::where('user_input_id','=',Auth::user()->id)
            ->where('daftar_lhp.id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        }elseif(Auth::user()->level=='auditor-senior'){
            $data=DaftarTemuan::select('daftar_lhp.*')
                        ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                        ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id')
                        ->where('data_rekomendasi.senior_user_id','=',Auth::user()->id)
                        ->where('daftar_lhp.id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        }elseif(Auth::user()->level=='super-user'){
            $data=DaftarTemuan::where('id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        }elseif(Auth::user()->level=='pic-unit'){
            $picUnit = PICUnit::where('id','=',Auth::user()->pic_unit_id)->pluck('id')->first();
            $data=DaftarTemuan::select('daftar_lhp.*')
                        ->leftJoin('data_temuan', 'data_temuan.id_lhp', '=', 'daftar_lhp.id')
                        ->leftJoin('data_rekomendasi', 'data_rekomendasi.id_temuan', '=', 'data_temuan.id')
                        ->where('data_rekomendasi.pic_1_temuan_id','=',$picUnit)
                        ->orWhere('data_rekomendasi.pic_2_temuan_id','=','%'.$picUnit.'%')
                        ->where('daftar_lhp.id',$idlhp)->with('dpemeriksa')->with('djenisaudit')->first();
        }

        // return json_encode($data);
        $idlhpaa = 0;
        if($data){
            $idlhpaa = $data->id;
        }
        $getTemuanId = DataTemuan::select('id')->where('id_lhp', $idlhpaa)->pluck('id')->toArray();

        if(Auth::user()->level=='auditor-senior'){
            $rekom = DataRekomendasi::whereIn('id_temuan', $getTemuanId)->where('senior_user_id','=',Auth::user()->id)->get();
        }else{
            $rekom = DataRekomendasi::whereIn('id_temuan', $getTemuanId)->get();
        }

        // return json_encode($rekom);
        return view('backend.pages.repository.auditor-senior.detail')
                ->with('data',$data)
                ->with('rekom',$rekom);
    }

    public function tindaklanjut_rincian(Request $request, $rekom_id, $idtemuan){

        $query = TindakLanjutRincian::where('id_rekomendasi', $rekom_id)->where('id_temuan', $idtemuan)
                ->whereNotNull('jenis')->with('unit_kerja')->get();
                
        return view('backend.pages.repository.table-tindaklanjut-rincian')
                ->with('data',$query)
                ->with('rekom_id', $rekom_id)
                ->with('idtemuan', $idtemuan);
    }

    public function document_tindaklanjut(Request $request, $rekom_id, $idtemuan){
        //TindakLanjutTemuan dulu habis itu baru di get si DokumenTindakLanjut where('temuan_id', $idtemuan)->
        $tindakLanjut = TindakLanjutTemuan::where('rekomendasi_id',$rekom_id)
                        ->with('pic1')->with('dokumen_tindak_lanjut')->get();

        // return json_encode($tindakLanjut); 
        return view('backend.pages.repository.table-tindaklanjut')
                ->with('data',$tindakLanjut)
                ->with('rekom_id', $rekom_id)
                ->with('idtemuan', $idtemuan);
    }
}
