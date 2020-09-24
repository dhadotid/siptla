<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekomendasi;
use App\Models\DaftarRekanan;
use App\Models\DataTemuan;
use App\Models\PICUnit;
use App\Models\RincianSewa;
use App\Models\RincianUangMuka;
use App\Models\RincianListrik;
use App\Models\RincianPiutang;
use App\Models\RincianPiutangKaryawan;
use App\Models\RincianHutangTitipan;
use App\Models\RincianPenutupanRekening;
use App\Models\RincianUmum;
use App\Models\RincianKontribusi;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;
use App\Models\RincianNonSetoranPerpanjanganPerjanjianKerjasama;
use App\Models\RincianNonSetoran;
use App\Models\RincianNonSetoranUmum;
use App\Models\RincianNonSetoranPertanggungjawabanUangMuka;
use App\Models\StatusRekomendasi;
use App\Models\TindakLanjutRincian;
use App\Models\DaftarTemuan;
use App\Models\MappingRekomendasiNotifikasi;
use App\User;
use Auth;
use Config;
class DataRekomendasiController extends Controller
{
    public function rekomendasi_simpan(Request $request)
    {
        // return $request->all();
        $statusAction = '';
        if(isset($request->idrekom)){
            $statusAction = 'memperbarui';
            $insert=DataRekomendasi::find($request->idrekom);
        }else{
            $statusAction = 'menambahkan';
            $insert=new DataRekomendasi;
        }

        $insert->nomor_rekomendasi=$notemuan=$request->no_rekomendasi;
        $insert->no_temuan=$notemuan=$request->nomor_temuan;
        $insert->id_temuan=$request->id_temuan;
        $insert->jenis_temuan=$request->jenis_temuan;
        $insert->nominal_rekomendasi=str_replace('.','',$request->nilai_rekomendasi);
        $insert->rekomendasi=$request->rekomendasi;

        if(Auth::user()->level=='auditor-senior')
        {
            $insert->senior_user_id=Auth::user()->id;
            $insert->senior_publish=0;
        }
        else
        {
            $insert->senior_user_id=$request->senior_auditor;
            $insert->senior_publish=0;
        }
        $insert->pic_1_temuan_id=$request->pic_1;

        if(isset($request->rincian_tl))
            $insert->rincian=$request->rincian_tl;

        $pic2='';
        if(isset($request->pic_2))
        {
            foreach($request->pic_2 as $k=>$v)
            {
                $pic2.=$v.',';
            }
            // $insert->pic_2_temuan_id=substr($pic2,0,-1);
            $insert->pic_2_temuan_id=$pic2;
        }   
        $insert->jangka_waktu_id=$request->jangka_waktu;
        $insert->status_rekomendasi_id=$request->status_rekomendasi;
        $insert->review_auditor=$request->review_auditor;

        // $rekanan=$request->rekanan;
        // if($rekanan!='')
        // {
        //     $cekrekanan=DaftarRekanan::where('nama',$rekanan)->first();
        //     if($cekrekanan)
        //     {
        //         $insert->rekanan=$cekrekanan->id;
        //     }
        //     else
        //     {
        //         $new_rekan=new DaftarRekanan;
        //         $new_rekan->nama=$rekanan;
        //         $new_rekan->save();

        //         $insert->rekanan=$new_rekan->id;
        //     }
        // }

        $insert->save();

        $idlhp=$request->id_lhp;

        $idrekomendasi=$insert->id;
        if(isset($request->rincian_tl))
        {
            $rinciantl=$request->rincian_tl;
           
            $rekomid_tl=$idlhp.$request->id_temuan;

            if($rinciantl=='sewa')
                $tl=RincianSewa::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='uangmuka')
                $tl=RincianUangMuka::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='listrik')
                $tl=RincianListrik::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
                // $tl=RincianListrik::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='piutang')
                $tl=RincianPiutang::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='piutangkaryawan')
                $tl=RincianPiutangKaryawan::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='hutangtitipan')
                $tl=RincianHutangTitipan::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='penutupanrekening')
                $tl=RincianPenutupanRekening::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='umum')
                $tl=RincianUmum::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='kontribusi')
                $tl=RincianKontribusi::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='nonsetoranperjanjiankerjasama')
                $tl=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_rekomendasi','2')->where('id_temuan',$request->id_temuan)->get();

            foreach($tl as $v)
            {
                $v->id_rekomendasi=$idrekomendasi;
                $v->save();
            }
        }

        if(Auth::user()->level=='auditor-senior'){
            $temuanData = DataTemuan::where('id',$request->id_temuan)->first();
            $temuan=DaftarTemuan::where('id',$temuanData->id_lhp)->first();
            $this->createNotification($temuan->id, $request->idrekom, $temuan->user_input_id, $request->id_temuan,
            Auth::user()->name .' telah '.$statusAction.' rekomendasi baru');
            $su = User::where('level', 'super-user')->get();
            $sorted = array();
            foreach($su as $k=>$v){
                if(!isset($sorted[$v->id])){
                    $sorted[$v->id][] = $v;
                    $this->createNotification($temuan->id, $request->idrekom, $v->id, $request->id_temuan, 
                    Auth::user()->name .' telah '.$statusAction.' rekomendasi baru');
                }else{
                    $sorted[$v->id] = array($v);
                }
            }
        }

        return $insert;
        // return redirect('data-temuan-lhp/'.$idlhp)
        //     ->with('success', 'Anda telah memasukkan data rekomendasi baru untuk <B><u>Nomor Temuan : '.$notemuan.'</u></B>.');
    }
    public function rekomendasi_data($idtemuan)
    {
        $table='<ol style="list-style-type:upper-roman !important;padding-left:20px;">';
        $picunit=PICUnit::all();
        $pic_unit=datauserpic($picunit);
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')->where('id_temuan',$idtemuan)
                ->with('picunit1')
                ->with('picunit2')
                ->with('statusrekomendasi')
                ->get();
        // $table='<table class="table table-bordered " id="table-tl-rincian" id="table-rekom">';
        // $table.='<thead>
        //             <tr class="purple">
        //                 <th class="text-center">Rekomendasi</th>
        //                 <th class="text-center">Nilai<br>Rekomendasi</th>
        //                 <th class="text-center">PIC 1</th>
        //                 <th class="text-center">PIC 2</th>
        //                 <th class="text-center">Status<br>Rekomendasi</th>
        //                 <th class="text-center">Tindak Lanjut</th>
        //                 <th class="text-center">Aksi</th>
        //             </tr>
        //         </thead>';
        // $table.='<tbody>';

        $tl=$this->tindaklanjut();
        // return $tindaklanjut;
        if($rekom->count()!=0)
        {
            foreach($rekom as $k=>$v)
            {   
                if($v->status_rekomendasi_id==1) 
                    $status='success';
                elseif($v->status_rekomendasi_id==2)
                    $status='info';
                elseif($v->status_rekomendasi_id==3)
                    $status='warning';
                elseif($v->status_rekomendasi_id==4)
                    $status='danger';

                $table.='<li style="margin-bottom:10px;padding:10px 0;border-bottom:1px solid #bbb;">';
                if (Auth::user()->level != 'pic-unit') {
                    $table.='
                    <a href="javascript:hapusrekomendasi(\''.$v->id_temuan.'\',\''.$v->id.'\')" class="btn btn-danger btn-xs pull-right"><i class="fa fa-trash"></i> Hapus Rekomendasi</a>
                    <a href="javascript:rekomedit(\''.$v->id_temuan.'\',\''.$v->id.'\')" class="btn btn-info btn-xs pull-right"><i class="fa fa-edit"></i> Edit Rekomendasi</a>';
                }
                $table.='
                    <u>Nilai Rekomendasi :</u><br><h5><span class="text-primary">Rp.'.number_format($v->nominal_rekomendasi,2,',','.').'</span></h5>
                    <br>
                    <u>Rekomendasi : </u><br><h4>'.$v->rekomendasi.'</h4><br>
                    <a href="#" class="btn btn-sm btn-'.($status).'">'.$v->statusrekomendasi->rekomendasi.'</a>
                    <br>
                        <div style="margin-top:10px;">
                        <a class="label label-primary fz-sm" href="'.url('data-tindak-lanjut/'.$v->rekom_id.'/'.$idtemuan.'').'" target="_blank">'.(isset($tl[$v->rekom_id]) ? count($tl[$v->rekom_id]) : 0).'&nbsp;Tindak Lanjut</a> &nbsp;';
                        //<a style="color:#fff" href="javascript:formtindaklanjut('.$v->rekom_id.',-1)" class="label label-info fz-sm" data-value="0"><i class="fa fa-plus-circle"></i>&nbsp;Tambah Rincian</a>
                $table.='</div></li>';
                // $tindaklanjut='<div style="width:150px;text-align:center;margin:0 auto;">
                //                 <span style="cursor:pointer" class="label label-primary fz-sm" id="jlhtindaklanjut" onclick="opentl(\''.$v->rekom_id.'\')">'.(isset($tl[$v->rekom_id]) ? count($tl[$v->rekom_id]) : 0).'</span>
                //                 <span style="cursor:pointer" class="label label-success fz-sm" onclick="opentl(\''.$v->rekom_id.'\')">Tindak Lanjut</span>
                //                 <span style="cursor:pointer" class="label label-info fz-sm" onclick="formtindaklanjut('.$v->rekom_id.',-1)"> 
                //                     <a style="color:#fff" data-value="0">
                //                         <div class="tooltipcss"><i class="fa fa-plus-circle"></i>
                //                             <span class="tooltiptext">Tambah Rincian</span>
                //                         </div></a>
                //                 </span>
                //             </div>';
                // $table.='<tr id="data_rekom_'.$v->rekom_id.'">
                //         <td style="background:#fff;" id="rekom_'.$v->id_temuan.'_'.$v->rekom_id.'">
                //             '.str_replace("\n",'<br>',$v->rekomendasi).'
                //         </td>
                //         <td style="background:#fff;" class="text-right" id="nominal_'.$v->id_temuan.'_'.$v->rekom_id.'">
                //             '.number_format($v->nominal_rekomendasi,2,',','.').'
                //         </td>
                //         <td style="background:#fff;" class="text-center" id="pic1_'.$v->id_temuan.'_'.$v->rekom_id.'">
                //             '.(isset($v->picunit1->nama_pic) ? $v->picunit1->nama_pic : 'n/a').'
                //         </td>
                //         <td style="background:#fff;" class="text-center" id="pic2_'.$v->id_temuan.'_'.$v->rekom_id.'">';
                //         if(isset($v->picunit2->nama_pic))
                //         {
                //             $table.=$v->picunit2->nama_pic;
                //         }
                //         else
                //         {
                //             $dpic=explode(',',$v->pic_2_temuan_id);
                //             foreach($dpic as $c)
                //             {
                //                 if($c!='')
                //                 {
                //                     // $table.=$c.'-';
                //                     if(isset($pic_unit[(int)$c]))
                //                     {
                //                         // $table.='ss';
                //                         $table.=$pic_unit[(int)$c]->nama_pic.'<br>';
                //                     }
                //                 }
                //             }
                //         }
                // $table.='</td>
                //         <td style="background:#fff;" class="text-center" id="status_'.$v->id_temuan.'_'.$v->rekom_id.'">
                //             '.(isset($v->statusrekomendasi->rekomendasi) ? $v->statusrekomendasi->rekomendasi : 'n/a').'
                //         </td>
                //         <td style="background:#fff;" class="text-center" id="tindak_'.$v->id_temuan.'_'.$v->rekom_id.'">
                //             '.$tindaklanjut.'
                //         </td>
                //         <td style="background:#fff;" class="text-center">
                //             <div style="width:90px;text-align:center;margin:0 auto;">
                //                 <a class="btn btn-xs btn-success rounded" onclick="detailrekomendasi('.$v->rekom_id.')">
                //                     <div class="tooltipcss"><i class="glyphicon glyphicon-list"></i>
                //                         <span class="tooltiptext">Detail Rekomendasi</span>
                //                     </div></a>
                //                 <a class="btn btn-xs btn-primary rounded" onclick="editrekomendasi('.$v->rekom_id.')">
                //                     <div class="tooltipcss"><i class="glyphicon glyphicon-edit"></i>
                //                         <span class="tooltiptext">Edit Rekomendasi</span>
                //                     </div></a>
                //                 <a class="btn btn-xs btn-danger rounded btn-delete-rekomendasi" onclick="hapusrekomendasi('.$v->rekom_id.','.$idtemuan.')">
                //                     <div class="tooltipcss"><i class="glyphicon glyphicon-trash"></i>
                //                         <span class="tooltiptext">Hapus Rekomendasi</span>
                //                     </div></a>
                //             </div>
                //         </td>
                //     </tr>';

                //     if(isset($tl[$v->rekom_id]))
                //     {
                //         $table.='<tr id="tl_rekom_'.$v->rekom_id.'" class="kolom-hide">';
                //             $table.='<td colspan="7">';
                //             $data_tl=$this->tabletindaklanjut($idtemuan,$v->rekom_id,$tl[$v->rekom_id]);
                //             $table.=$data_tl;
                //             $table.='</td>';
                //         $table.='</tr>';
                //     }
            }
        }
        else
        {
            // $table.='<tr><td style="background:#fff;font-weight:bold" colspan="7" class="text-center">Rekomendasi Masih Kosong</td></tr>';
        }
        // $table.='</tbody>';
        $table.='</ol>';

        return $table;
    }
    public function rekomendasi_data_new(Request $request, $idtemuan,$status_rekom)
    {
        $now=date('Y-m-d');
        $wh = array();
        $keyparam = '';
        if(isset($request->key) && isset($request->priority)){
            $wh['data_temuan.level_resiko_id']=$request->priority;
        }
        if($status_rekom == 'setuju'){
            $wh['data_rekomendasi.senior_publish'] = '1';
        }elseif($status_rekom == 'belum'){
            $wh['data_rekomendasi.senior_publish'] = '0';
        }else{
            $wh['data_rekomendasi.status_rekomendasi_id'] = $status_rekom;
        }

        $table='<ol style="list-style-type:upper-roman !important;padding-left:20px;">';
        $picunit=PICUnit::all();
        $pic_unit=datauserpic($picunit);
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')
                ->select('data_rekomendasi.*', 'data_temuan.level_resiko_id')
                ->join('data_temuan', 'data_temuan.id', '=', 'data_rekomendasi.id_temuan');
                if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                    $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                }
                if($request->key == 'melewati-batas-waktu-penyelesaian'){
                    $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                }
                if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                    $rekom = $rekom->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                }

            $rekom = $rekom->where($wh)//->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
                    ->whereNull('data_rekomendasi.deleted_at')
                    ->where('data_rekomendasi.id_temuan',$idtemuan)
                    ->with('picunit1')
                    ->with('picunit2')
                    ->with('statusrekomendasi')
                    ->get();
        if(Auth::user()->level == 'auditor-senior'){
        $rekomQuery=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')
                ->select('data_rekomendasi.*', 'data_temuan.level_resiko_id')
                ->join('data_temuan', 'data_temuan.id', '=', 'data_rekomendasi.id_temuan');
                if($request->key == 'sudah-masuk-batas-waktu-penyelesaian'){
                    $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '=', $now);
                }
                if($request->key == 'melewati-batas-waktu-penyelesaian'){
                    $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '>', $now);
                }
                if($request->key=='belum-masuk-batas-waktu-penyelesaian'){
                    $rekomQuery = $rekomQuery->where('data_rekomendasi.tanggal_penyelesaian', '<', $now);
                }
        $rekomQuery = $rekomQuery->where($wh)//->orWhere('data_rekomendasi.status_rekomendasi_id','!=','1')
            ->where('data_rekomendasi.id_temuan',$idtemuan)
            // ->where('data_rekomendasi.status_rekomendasi_id',$status_rekom)
            ->whereNull('data_rekomendasi.deleted_at')
            // ->where('data_rekomendasi.senior_user_id', Auth::id())
            ->with('picunit1')
            ->with('picunit2')
            ->with('statusrekomendasi')
            ->get();
            $rekom = $sorted = array();
            foreach($rekomQuery as $key=>$v){
                if($v->senior_user_id == Auth::user()->id){
                    if(!isset($sorted[$v->id])){
                        $sorted[$v->id][] = $v;
                        array_push($rekom, $v);
                    }else{
                        $sorted[$v->id] = array($v);
                    }
                }
            }
        }
        

        $tl=$this->tindaklanjut();
        // return json_encode($tl);
        if(count($rekom)!=0)
        {
            foreach($rekom as $k=>$v)
            {   
                // return json_encode($rekom);
                if($v->status_rekomendasi_id==1) 
                    $status='success';
                elseif($v->status_rekomendasi_id==2)
                    $status='info';
                elseif($v->status_rekomendasi_id==3)
                    $status='warning';
                elseif($v->status_rekomendasi_id==4)
                    $status='danger';

                $table.='<li style="margin-bottom:10px;padding:10px 0;border-bottom:1px solid #bbb;">';

                if($v->senior_publish!=1 && Auth::user()->level != 'pic-unit' && $v->status_rekomendasi_id != 1)
                {
                    $table.='<a href="javascript:hapusrekomendasi(\''.$v->id_temuan.'\',\''.$v->id.'\')" class="btn btn-danger btn-xs pull-right"><i class="fa fa-trash"></i> Hapus Rekomendasi</a>
                    <a href="javascript:rekomedit(\''.$v->id_temuan.'\',\''.$v->id.'\')" class="btn btn-info btn-xs pull-right"><i class="fa fa-edit"></i> Edit Rekomendasi</a>';
                }
                if(Auth::user()->level=='auditor-senior' || Auth::user()->level=='super-user')
                {
                    if($v->senior_publish==0 && $v->status_rekomendasi_id != 1)
                    {
                        // $table.='<a href="javascript:rekomsetujui(\''.$v->id_temuan.'\',\''.$v->rekom_id.'\',\''.$v->status_rekomendasi_id.'\')" class="btn btn-success btn-xs pull-right"><i class="fa fa-edit"></i> Setujui Rekomendasi</a>';
                        $table.='<a href="javascript:rekomsetujui(\''.$v->id_temuan.'\',\''.$v->id.'\',\''.$v->status_rekomendasi_id.'\')" class="btn btn-success btn-xs pull-right"><i class="fa fa-edit"></i> Setujui Rekomendasi</a>';
                    }
                }
                    $seniorTujuan = User::where('id', '=', $v->senior_user_id)->first();
                $table.='<u>Nomor Rekomendasi :</u><br><h5><span class="text-primary">'.$v->nomor_rekomendasi.'</span></h5>
                <u>Senior Yang Dituju :</u><br><h5><span class="text-primary">'.$seniorTujuan->name.'</span></h5>
                <u>Nilai Rekomendasi :</u><br><h5><span class="text-primary">Rp.'.number_format($v->nominal_rekomendasi,2,',','.').'</span></h5>
                    <br>
                    <u>Rekomendasi : </u><br><h4>'.$v->rekomendasi.'</h4><br>
                    <a href="#" class="btn btn-sm btn-'.($status).'">'.$v->statusrekomendasi->rekomendasi.'</a>
                    <br>
                    <div style="margin-top:10px;">
                        <a class="label label-primary fz-sm" href="javascript:detailtljunior('.$v->id.')">'.(isset($tl[$v->id]) ? count($tl[$v->id]) : 0).'&nbsp;Tindak Lanjut</a> &nbsp;&nbsp;';

                        if($v->rincian!='')
                        {
                            // $table.='<a class="label label-danger fz-sm" href="javascript:update_rincian('.$v->rekom_id.','.$idtemuan.')"><i class="fa fa-check"></i> Rincian : '.ucwords($v->rincian).'</a> &nbsp;<br>';
                            $rincianText = str_replace("Rincian Nilai", "",Config::get('constants.rincian.'.$v->rincian.''));
                            // $rincianText = str_replace('-', '',$rincianText);
                            $table.='<a class="label label-danger fz-sm" href="javascript:getrincainTables(\''.Config::get('constants.rincian.'.$v->rincian.'').'\',\''.$v->rincian.'\',\''.$idtemuan.'\',\''.$v->id.'\',\''.Auth::user()->level.'\',\''.$v->senior_publish.'\')"><i class="fa fa-check"></i> Rincian : '.ucwords(str_slug($rincianText, ' ')).'</a> &nbsp;<br>';
                        }
                        // else
                        // {
                        //     $table.='<a class="label label-success fz-sm" href="javascript:update_rincian('.$v->rekom_id.','.$idtemuan.')"><i class="fa fa-link"></i> Update Rincian</a> &nbsp;<br>';
                        // }
                        //<a style="color:#fff" href="javascript:formtindaklanjut('.$v->rekom_id.',-1)" class="label label-info fz-sm" data-value="0"><i class="fa fa-plus-circle"></i>&nbsp;Tambah Rincian</a>
                $table.='</div>
                </li>';
            }
        }
        else
        {
            // $table.='<tr><td style="background:#fff;font-weight:bold" colspan="7" class="text-center">Rekomendasi Masih Kosong</td></tr>';
        }
        $table.='</ol>';

        return $table;
    }
    public function rekomendasi_edit_data($idrekom)
    {
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')->where('id',$idrekom)
                ->with('dtemuan')
                ->with('jenistemuan')
                ->with('picunit1')
                ->with('picunit2')
                ->with('jangkawaktu')
                ->with('drekanan')
                ->with('statusrekomendasi')
                ->first();

        return $rekom;
    }
    public function rekomendasi_edit($idrekom)
    {
        // $picunit=
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')->where('id',$idrekom)
                ->with('dtemuan')
                ->with('jenistemuan')
                ->with('picunit1')
                ->with('picunit2')
                ->with('jangkawaktu')
                ->with('drekanan')
                ->with('statusrekomendasi')
                ->first();
        
        if(isset($rekom->picunit2->nama_pic))
            return $rekom;
        else
        {
            $picunit=PICUnit::all();
            $pic_unit=datauserpic($picunit);
            $rekm=$rekom;

            $idpicunit=explode(',',$rekom->pic_2_temuan_id);
            $d='';
            foreach($idpicunit as $k=>$c)
            {
                if($c!='')
                {
                    if(isset($pic_unit[(int)$c]))
                    {
                        $d.=$pic_unit[(int)$c]->nama_pic.', ';
                    }
                }
            }
            $rekm['picunit_2']=substr($d,0,-2);
            return $rekm;
        }
    }
    public function rekomendasi_update(Request $request,$idrekom,$idtemuan)
    {
        // return $request->all();
        $update=DataRekomendasi::find($idrekom);
        $update->no_temuan=$notemuan=$request->nomor_temuan;
        $update->id_temuan=$request->id_temuan;
        $update->jenis_temuan=$request->jenis_temuan;
        $update->nominal_rekomendasi=str_replace('.','',$request->nilai_rekomendasi);
        $update->rekomendasi=$request->rekomendasi;
        $update->pic_1_temuan_id=$request->pic_1;
        // $update->pic_2_temuan_id=$request->pic_2;
        $pic2='';
        foreach($request->pic_2 as $k=>$v)
        {
            $pic2.=$v.',';
        }   
        // $update->pic_2_temuan_id=substr($pic2,0,-1);
        $update->pic_2_temuan_id=$pic2;
        $update->jangka_waktu_id=$request->jangka_waktu;
        $update->status_rekomendasi_id=$request->status_rekomendasi;
        $update->review_auditor=$request->review_auditor;

        $rekanan=$request->rekanan;
        if($rekanan!='')
        {
            $cekrekanan=DaftarRekanan::where('nama',$rekanan)->first();
            if($cekrekanan)
            {
                $update->rekanan=$cekrekanan->id;
            }
            else
            {
                $new_rekan=new DaftarRekanan;
                $new_rekan->nama=$rekanan;
                $new_rekan->save();

                $update->rekanan=$new_rekan->id;
            }
        }

        $update->save();

        if(Auth::user()->level=='auditor-senior'){
            $temuanData = DataTemuan::where('id',$update->id_temuan)->first();
            $temuan=DaftarTemuan::where('id',$temuanData->id_lhp)->first();
            $this->createNotification($temuan->id, $rekomData->id, $temuan->user_input_id, $temuanData->id,
            Auth::user()->name .' telah memperbarui rekomendasi baru');
            $su = User::where('level', 'super-user')->get();
            $sorted = array();
            foreach($su as $k=>$v){
                if(!isset($sorted[$v->id])){
                    $sorted[$v->id][] = $v;
                    $this->createNotification($temuan->id, $rekomData->id, $v->id, $temuanData->id,
                    Auth::user()->name .' telah memperbarui rekomendasi baru');
                }else{
                    $sorted[$v->id] = array($v);
                }
            }
        }

        return $this->rekomendasi_edit($idrekom);
    }
    public function rekomendasi_delete($idrekom,$idtemuan)
    {
        // return $idtemuan;
        $rekom=DataRekomendasi::find($idrekom);
        $data=$rekom;
        $rekom->delete();
        // DataRekomendasi::destroy($idrekom);
        // $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();
        // $jlhrekom=isset($rekomendasi[$item->temuan_id]) ? count($rekomendasi[$item->temuan_id]) : 0;

        // $data['jlh']='<span style="cursor:pointer" class="label label-'.($rekom->count()==0 ? 'dark' : 'primary').' fz-sm">'.$rekom->count().'</span>';
        // return $data;
        if(Auth::user()->level=='auditor-senior'){
            $temuanData = DataTemuan::where('id',$idtemuan)->first();
            $temuan=DaftarTemuan::where('id',$temuanData->id_lhp)->first();
            $this->createNotification($temuan->id, $idrekom, $temuan->user_input_id, $idtemuan,
            Auth::user()->name .' telah menghapus rekomendasi baru');
            $su = User::where('level', 'super-user')->get();
            $sorted = array();
            foreach($su as $k=>$v){
                if(!isset($sorted[$v->id])){
                    $sorted[$v->id][] = $v;
                    $this->createNotification($temuan->id, $idrekom, $temuan->user_input_id, $idtemuan,
                    Auth::user()->name .' telah menghapus rekomendasi baru');
                }else{
                    $sorted[$v->id] = array($v);
                }
            }
        }
        return redirect('data-temuan-lhp/'.$data->dtemuan->id_lhp)->with('');
    }

    public function tabletindaklanjut($idtemuan,$idrekom,$data_tl)
    {
        $table='<table class="table table-bordered " id="table-tl-rincian">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">Tindak Lanjut</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center">Dokumen Pendukung</th>
                    <th class="text-center">Hasil Review PIC 1</th>
                    <th class="text-center">Status Review PIC 1</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';
            foreach($data_tl as $k=>$v)
            {
                $table.='<tr style="background:#fff !important;">
                    <td style="width:300px">'.$v->tindak_lanjut.'</td>
                    <td class="text-right">'.number_format($v->nilai,0,',','.').'</td>
                    <td class="text-center"><a href="">'.(isset($v->dokumen_tindak_lanjut->nama_dokumen) ? $v->dokumen_tindak_lanjut->nama_dokumen : '').'</a></td>
                    <td class="text-left"></td>
                    <td class="text-left"></td>
                    <td class="text-center">
                        <div style="width:80px;">
                            <a class="btn btn-xs btn-primary rounded" onclick="formtindaklanjut('.$v->rekomendasi_id.','.$v->tl_id.')">
                                <div class="tooltipcss"><i class="glyphicon glyphicon-edit"></i>
                                    <span class="tooltiptext">Edit Tindak Lanjut</span>
                                </div></a>
                            <a class="btn btn-xs btn-danger rounded btn-delete-rekomendasi" onclick="hapustindaklanjut('.$idtemuan.','.$v->tl_id.')">
                                <div class="tooltipcss"><i class="glyphicon glyphicon-trash"></i>
                                    <span class="tooltiptext">Hapus Tindak Lanjut</span>
                                </div></a>
                        </div>
                    </td>
                </tr>'; 
            }
        $table.='</tbody></table>';

        return $table;
    }

    public function update_jlh_rekomendasi($idtemuan,$st_rekom=null)
    {
        if($st_rekom!=null)
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->where('status_rekomendasi_id',$st_rekom)->get();
        else
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();
        $jlhrekom=$rekom->count();
        // echo '<span style="cursor:pointer" class="label label-'.($jlhrekom==0 ? 'dark' : 'primary').' fz-sm">'.$jlhrekom.'</span>';
        echo $jlhrekom;
    }

    public function load_tabel_rincian_unitkerja($jenis,$idtemuan=null,$statusrekomendasi=null,$view=null)
    {
        //Request PIC untuk tetap ditampilkan semua rincian tanggal: Kamis, 02 September 2020
        $status_rekom=StatusRekomendasi::all();
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        $idrekomendasi=$statusrekomendasi;
        $rinciantindaklanjut='';
        $table='';
        //|| $jenis != 'nonsetoranperjanjiankerjasama'
        if($jenis != 'penutupanrekening'){
            $rinciantindaklanjut=TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan',$idtemuan)
                        ->where('tindak_lanjut_rincian.id_rekomendasi',$idrekomendasi)
                        ->where('tindak_lanjut_rincian.jenis', $jenis)//->get();
                        ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                        ->groupBy('status_rekomendasi.rekomendasi')
                        ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                        ->get();
        }
        // return json_encode($rinciantindaklanjut);
        if($jenis=='sewa')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-sewa')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='uangmuka')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-uangmuka')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);

           
        }
        elseif($jenis=='listrik')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-listrik')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='piutang')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-piutang')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='piutangkaryawan')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-piutangkaryawan')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='hutangtitipan')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianHutangTitipan::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianHutangTitipan::where('rincian_hutang_titipan.id_temuan',$idtemuan)->where('rincian_hutang_titipan.id_rekomendasi',$idrekomendasi)
            //             ->join('data_rekomendasi', 'rincian_hutang_titipan.id_rekomendasi','=', 'data_rekomendasi.id')
            //             ->get(['rincian_hutang_titipan.*', 'data_rekomendasi.status_rekomendasi_id as rekom_id']);
            // }
            $rincian=RincianHutangTitipan::where('rincian_hutang_titipan.id_temuan',$idtemuan)->where('rincian_hutang_titipan.id_rekomendasi',$idrekomendasi)
                        ->join('data_rekomendasi', 'rincian_hutang_titipan.id_rekomendasi','=', 'data_rekomendasi.id')
                        ->get(['rincian_hutang_titipan.*', 'data_rekomendasi.status_rekomendasi_id as rekom_id']);
            $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-hutangtitipan')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='penutupanrekening')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            //     $rinciantindaklanjut=TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan',$idtemuan)
            //             ->where('tindak_lanjut_rincian.id_rekomendasi',$idrekomendasi)
            //             ->where('tindak_lanjut_rincian.jenis', $jenis)
            //             ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
            //             ->groupBy('status_rekomendasi.rekomendasi')
            //             ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.saldo_akhir) as sum')
            //             ->get();
            // }
            $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.saldo_akhir) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-penutupanrekening')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);

            
        }
        elseif($jenis=='umum')
        {
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }

            return view('backend.pages.data-lhp.rincian-table.table-umum')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='kontribusi'){
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianKontribusi::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianKontribusi::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianKontribusi::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-kontribusi')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }elseif($jenis=='nonsetoranperjanjiankerjasama'){
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranperjanjiankerjasama')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }elseif($jenis=='nonsetoran'){
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianNonSetoran::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianNonSetoran::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianNonSetoran::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();

                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoran')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }elseif($jenis=='nonsetoranumum'){
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianNonSetoranUmum::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianNonSetoranUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianNonSetoranUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();
                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranumum')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            // if(Auth::user()->level=='pic-unit')
            //     $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            // else
            // {
            //     $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
            //             ->get();
            // }
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)
                        ->get();

                        $arrayrinciantindaklanjut = array();
            foreach($rincian as $k=>$v){
                $arrayrinciantindaklanjut[$v->id] = TindakLanjutRincian::where('tindak_lanjut_rincian.id_temuan','=',$idtemuan)
                    ->where('tindak_lanjut_rincian.id_rekomendasi','=',$idrekomendasi)
                    ->where('jenis', $jenis)
                    ->leftjoin('mapping_rincian_tindak_lanjut_detail', 'tindak_lanjut_rincian.id', '=', 'mapping_rincian_tindak_lanjut_detail.id_tindak_lanjut_rincian')
                    ->join('status_rekomendasi', 'tindak_lanjut_rincian.status_rincian', '=', 'status_rekomendasi.id')
                    ->where('mapping_rincian_tindak_lanjut_detail.id_rincian', $v->id)
                    ->groupBy('status_rekomendasi.rekomendasi')
                    ->selectRaw('status_rekomendasi.rekomendasi, sum(tindak_lanjut_rincian.nilai) as sum')
                    ->get();
            }
            return view('backend.pages.data-lhp.rincian-table.table-nonsetoranpertanggungjawabanuangmuka')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('status_rekomendasi',$status_rekom)
                    ->with('rinciantindaklanjut',$arrayrinciantindaklanjut)
                    ->with('idrekomendasi',$idrekomendasi);
        }
    }
    public function load_tabel_rincian($jenis,$idtemuan=null,$statusrekomendasi=null,$view=null)
    {
        $idrekomendasi=$statusrekomendasi;
        $table='';
        
        $rekom=DataRekomendasi::find($idrekomendasi);
        // return 'hehehe '.$rekom;
        // return json_encode($rekom);

        if($jenis=='sewa')
        {
            $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('nilai_pekerjaan'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Pembayaran Sewa</h3>
            <h5 class="text-center">Total Rekomendasi: '. $totalNilaiTitle .'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Mitra</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tgl. PKS</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>
                    <th class="text-center">Masa Kontrak</th>';
                    if($rekom->senior_publish!=1){
                        $table.='<th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->mitra.'</td>
                    <td class="text-center">'.$v->no_pks.'</td>
                    <td class="text-center">'.($v->tgl_pks != '' ? date('d/m/Y',strtotime($v->tgl_pks)) : '-').'</td>
                    <td class="text-center">'.rupiah($v->nilai_pekerjaan).'</td>
                    <td class="text-center">'.$v->masa_berlaku.'</td>';
                    if($rekom->senior_publish!=1){
                        $table.='
                        <td class="text-center">
                            <a href="javascript:addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                            <a href="javascript:hapusrincian('.$v->id.',\'sewa\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                        </td>';
                    }
                $table.='</tr>';
                $no++;
                $totalnilai+=$v->nilai_pekerjaan;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';

            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                // $table.='<tr >
                //             <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                //         </tr>';
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,'.implode (",", $arrayPIC).')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    // $table.='<tr >
                    //         <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    //     </tr>';
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('jumlah_pum'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Pengembalian Sisa Uang Muka</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered" style="width:100%" id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja – PIC 2</th>
                    <th class="text-center">No. Invoice</th>
                    <th class="text-center">Tanggal UM</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah Sisa Uang Muka (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_invoice.'</td>
                    <td class="text-center">'.($v->tgl_pum != '' ? date('d/m/Y',strtotime($v->tgl_pum)) : '-').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_pum,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <div style="width:80px;">
                            <a href="javascript:addtindaklanjut(\'uangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                            <a href="javascript:hapusrincian('.$v->id.',\'uangmuka\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                        </div>
                    </td>';
                    }
                $table.='</tr>';
                $no++;
                $totalnilai+=$v->jumlah_pum;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';
            
            $table.='</tbody>';
            $table.='</table>';
            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <div class="text-center"><a href="#" onclick="addtindaklanjut(\'uangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></div>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <div class="text-center">
                    <a href="#" onclick="addtindaklanjut(\'uangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('tagihan'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Biaya Listrik</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja – PIC 2</th>
                    <th class="text-center">Lokasi</th>
                    <th class="text-center">Tanggal Invoice</th>
                    <th class="text-center">Jumlah Tagihan (Rp)</th>
                    <th class="text-center">Keterangan</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->lokasi.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_invoice)).'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'listrik\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'listrik\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->tagihan;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';
            $table.='</tbody>';
            $table.='</table>';
            if(Auth::user()->level=='pic-unit')
            {
                //<a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'listrik\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'listrik\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
        
            }
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('tagihan'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Piutang</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Pelanggan</th>
                    <th class="text-center">Jumlah Tagihan (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->pelanggan.'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'piutang\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'piutang\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->tagihan;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';
            
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'piutang\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'piutang\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = 0;//number_format((int)$rincian->sum('pinjaman'),0,',','.');
            foreach($rincian as $k=>$v){
                $totalNilaiTitle += (int)$v->pinjaman;
            }
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            else number_format($totalNilaiTitle,0,',','.');
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Piutang Karyawan</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja – PIC 2</th>
                    <th class="text-center">Karyawan</th>
                    <th class="text-center">Jumlah Pinjaman (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->karyawan.'</td>
                    <td class="text-center">'.number_format((int)$v->pinjaman,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'piutangkaryawan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'piutangkaryawan\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=(int)$v->pinjaman;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';

            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'piutangkaryawan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'piutangkaryawan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='hutangtitipan')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Hutang Titipan</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Saldo Hutang Titipan (Rp)</th>
                    <th class="text-center">Sisa Yang Harus Disetor (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianHutangTitipan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tanggal)).'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->saldo_hutang,0,',','.').'</td>
                    <td class="text-center">'.number_format($v->sisa_setor,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'hutangtitipan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'hutangtitipan\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->sisa_setor;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> eraseCookie('total_nilai'); </script>";
            echo "<script> eraseCookie('max_rekomendasi'); </script>";
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'hutangtitipan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'hutangtitipan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();   
            $totalNilaiTitle = number_format($rincian->sum('saldo_akhir'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai Non Setoran – Penutupan Rekening</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Jenis Rekening</th>
                    <th class="text-center">Nama Bank</th>
                    <th class="text-center">Nomor Rekening</th>
                    <th class="text-center">Nama Rekening</th>
                    <th class="text-center">Mata Uang</th>
                    <th class="text-center">Saldo Temuan</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->jenis_rekening.'</td>
                    <td class="text-center">'.$v->nama_bank.'</td>
                    <td class="text-center">'.$v->nomor_rekening.'</td>
                    <td class="text-center">'.$v->nama_rekening.'</td>
                    <td class="text-center">'.$v->mata_uang.'</td>
                    <td class="text-center">'.number_format($v->saldo_akhir,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'penutupanrekening\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'penutupanrekening\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->saldo_akhir;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> eraseCookie('total_nilai'); </script>";
            echo "<script> eraseCookie('max_rekomendasi'); </script>";
            // $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';

            $table.='</tbody>';
            $table.='</table>';
            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'penutupanrekening\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'penutupanrekening\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('jumlah_rekomendasi'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi (Umum - Setoran)</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja – PIC 2</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_rekomendasi,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'umum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'umum\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->jumlah_rekomendasi;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'umum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'umum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }else if ($jenis=='kontribusi'){
            $rincian=RincianKontribusi::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('nilai_penerimaan'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Kontribusi</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Tahun</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Rekomendasi Kontribusi (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->tahun.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->nilai_penerimaan,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'kontribusi\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'kontribusi\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->nilai_penerimaan;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="'.$totalnilai.'">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'kontribusi\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'kontribusi\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }else if($jenis == 'nonsetoranperjanjiankerjasama'){
            $table='<h3 class="text-center">Rincian Nilai Non Setoran – Perjanjian Kerjasama</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tanggal PKS</th>
                    <th class="text-center">Periode</th>
                    <th class="text-center">Keterangan</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                <td class="text-center">'.$no.'</td>
                <td class="text-center">'.$v->unit_kerja.'</td>
                <td class="text-center">'.$v->no_pks.'</td>
                <td class="text-center">'.($v->tgl_pks != '' ? date('d/m/Y',strtotime($v->tgl_pks)) : '-').'</td>
                <td class="text-center">'.($v->masa_berlaku != '' ? date('d/m/Y',strtotime($v->masa_berlaku)) : '-').'</td>
                <td class="text-center">'.$v->keterangan.'</td>';
                // <td class="text-center">'.rupiah($v->nilai_pekerjaan).'</td>
                if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'nonsetoranperjanjiankerjasama\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'nonsetoranperjanjiankerjasama\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->nilai_pekerjaan;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> eraseCookie('total_nilai'); </script>";
            echo "<script> eraseCookie('max_rekomendasi'); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'nonsetoranperjanjiankerjasama\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'nonsetoranperjanjiankerjasama\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }elseif($jenis=='nonsetoran'){
            $rincian=RincianNonSetoran::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $totalNilaiTitle = number_format($rincian->sum('nilai_rekomendasi'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Non Setoran</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->nilai_rekomendasi,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'nonsetoran\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'nonsetoran\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->nilai_rekomendasi;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> setCookie('total_nilai',$totalnilai,1); </script>";
            echo "<script> setCookie('max_rekomendasi', $rekom->nominal_rekomendasi, 1); </script>";
            $table.='<input type="hidden" id="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'nonsetoran\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'nonsetoran\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }elseif($jenis=='nonsetoranumum'){
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Umum (Non Setoran)</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Keterangan</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoranUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'nonsetoranumum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'nonsetoranumum\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->nilai_rekomendasi;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> eraseCookie('total_nilai'); </script>";
            echo "<script> eraseCookie('max_rekomendasi'); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'nonsetoranumum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'nonsetoranumum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Pertanggungjawaban Uang Muka</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekomendasi.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">No. Invoice</th>
                    <th class="text-center">Tanggal UM</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah UM (Rp)</th>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <th class="text-center">Aksi</th>';
                    }
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->get();
            $no=1;
            $totalnilai=0;
            $arrayPIC = array();
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_invoice.'</td>
                    <td class="text-center">'.$v->tgl_um.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_um,0,',','.').'</td>';
                    if($rekom->senior_publish!=1){
                    $table.='
                    <td class="text-center">
                        <a href="javascript:addtindaklanjut(\'nonsetoranpertanggungjawabanuangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\','.$v->id.','.$v->unit_kerja_id.')" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:hapusrincian('.$v->id.',\'nonsetoranpertanggungjawabanuangmuka\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>';
                    }
                $table.='</tr>';
                $no++;

                $totalnilai+=$v->jumlah_um;
                $arrayPIC[] = $v->unit_kerja_id;
            }
            echo "<script> eraseCookie('total_nilai'); </script>";
            echo "<script> eraseCookie('max_rekomendasi'); </script>";
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='
                <div style="text-align: center">
                <a href="#" onclick="addtindaklanjut(\'nonsetoranpertanggungjawabanuangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                </div>';
            }
            else
            {
                if($view==null)
                {
                    $table.='
                    <div style="text-align: center">
                    <a href="#" onclick="addtindaklanjut(\'nonsetoranpertanggungjawabanuangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1,\''.implode (",", $arrayPIC).'\')" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a>
                    </div>';
                }
            }
        }

        return $table;
    }

    public function hapus_rincian_old($jenis,$idtemuan=null,$statusrekomendasi=null,$view=null)
    {
        $idrekomendasi=$statusrekomendasi;
        $table='';
        
        $rekom=DataRekomendasi::find($idrekomendasi);
        // return 'hehehe '.$rekom;

        if($jenis=='sewa')
        {
            $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='hutangtitipan')
        {
            $rincian=RincianHutangTitipan::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }else if ($jenis=='kontribusi'){
            $rincian=RincianKontribusi::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }else if($jenis == 'nonsetoranperjanjiankerjasama'){
            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
            
        }elseif($jenis=='nonsetoran'){
            $rincian=RincianNonSetoran::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
            
        }elseif($jenis=='nonsetoranumum'){
            $rincian=RincianNonSetoranUmum::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_temuan',$idtemuan)->where('id_rekomendasi',$idrekomendasi)->delete();
        }

        if($rincian)
            echo 1;
        else
            echo 2;
    }

    public function rekomendasi_by_temuan($idtemuan,$status=null)
    {
        if($status==null)
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();
        else
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->where('status_rekomendasi_id',$status)->get();

        return $rekom;
    }

    public function rekomendasi_by_temuan_select($idtemuan,$userpic_id=null)
    {
        if($userpic_id!=null)
        {
            // $rekom=array();
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->where(function($query) use ($userpic_id){
                                    $query->where('pic_1_temuan_id', $userpic_id);
                                    $query->orWhere('pic_2_temuan_id','like', "%$userpic_id%,");
                                    // $query->orWhere('data_rekomendasi.pic_2_temuan_id', $user_pic->id);
                                })->get();
            
        }
        else
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();


        if(Auth::user()->level=='auditor-senior')
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();

        $select ='<select class="select2 form-control" name="no_rekomendasi" id="no_rekomendasi" onchange="loaddata()">';
        $select.='<option value="0">-Semua-</option>';
        foreach($rekom as $v)
        {
            $select.='<option value="'.$v->id.'">'.$v->nomor_rekomendasi.' - '.substr($v->rekomendasi,0,80).' ...</option>';
        }
        $select.='</select>';
        return $select;
    }

    public function rincian_nilai($idrekom)
    {
        $rekom=DataRekomendasi::find($idrekom);
        $jenis=$rincian=$rekom->rincian;
        $idtemuan=$rekom->id_temuan;
        $table='Data Rincian Belum Tersedia';
        if($jenis=='sewa')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Setoran – Sewa</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Mitra</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tgl. PKS</th>
                    <th class="text-center">Nilai Rekomendasi</th>
                    <th class="text-center">Masa Kontrak</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianSewa::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->mitra.'</td>
                    <td class="text-center">'.$v->no_pks.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_pks)).'</td>
                    <td class="text-center">'.rupiah($v->nilai_pekerjaan).'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->masa_berlaku)).'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='uangmuka')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Uang Muka</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">No. Invoice</th>
                    <th class="text-center">Tanggal PUM</th>
                    <th class="text-center">Jumlah UM</th>
                    <th class="text-center">Keterangan</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianUangMuka::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_invoice.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_pum)).'</td>
                    <td class="text-center">'.number_format($v->jumlah_pum,0,',','.').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='listrik')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Listrik</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Lokasi</th>
                    <th class="text-center">Tanggal Invoice</th>
                    <th class="text-center">Tagihan</th>
                    <th class="text-center">Keterangan</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianListrik::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->lokasi.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_invoice)).'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                </tr>';
                $no++;
            }
        
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='piutang')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Setoran – Piutang</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Pelanggan</th>
                    <th class="text-center">Jumlah Tagihan</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPiutang::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->pelanggan.'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='piutangkaryawan')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Setoran – Piutang Karyawan</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Karyawan</th>
                    <th class="text-center">Jumlah Pinjaman</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPiutangKaryawan::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->karyawan.'</td>
                    <td class="text-center">'.number_format($v->pinjaman,0,',','.').'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='hutangtitipan')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Hutang Titipan</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Saldo Hutang Titipan (Rp)</th>
                    <th class="text-center">Sisa Yang Harus Disetor (Rp)</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianHutangTitipan::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tanggal)).'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->saldo_hutang,0,',','.').'</td>
                    <td class="text-center">'.number_format($v->sisa_setor,0,',','.').'</td>
                </tr>';
                $no++;
            }

            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $totalNilaiTitle = number_format($rincian->sum('saldo_akhir'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Penutupa Rekening</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                <th class="text-center">No</th>
                <th class="text-center">Unit Kerja - PIC 2</th>
                <th class="text-center">Jenis Rekening</th>
                <th class="text-center">Nama Bank</th>
                <th class="text-center">Nomor Rekening</th>
                <th class="text-center">Nama Rekening</th>
                <th class="text-center">Mata Uang</th>
                <th class="text-center">Saldo Temuan</th>
                </tr>';
            $table.='</thead><tbody>';

            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                <td class="text-center">'.$no.'</td>
                <td class="text-center">'.$v->unit_kerja.'</td>
                <td class="text-center">'.$v->jenis_rekening.'</td>
                <td class="text-center">'.$v->nama_bank.'</td>
                <td class="text-center">'.$v->nomor_rekening.'</td>
                <td class="text-center">'.$v->nama_rekening.'</td>
                <td class="text-center">'.$v->mata_uang.'</td>
                <td class="text-center">'.number_format($v->saldo_akhir,0,',','.').'</td>
                </tr>';
                $no++;
            }

            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='umum')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Setoran - Umum</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianUmum::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_rekomendasi,0,',','.').'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }elseif($jenis=='kontribusi'){
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Kontribusi </h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Tahun</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Rekomendasi Kontribusi</th>
                    <th class="Aksi">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianKontribusi::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->tahun.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->nilai_penerimaan,0,',','.').'</td>
                </tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }elseif($jenis=='nonsetoranperjanjiankerjasama'){
            $table='<h3 class="text-center">Rincian Nilai Setoran – Sewa</h3>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tgl. PKS</th>
                    <th class="text-center">Periode</th>
                    <th class="text-center">Keterangan</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoranPerpanjanganPerjanjianKerjasama::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_pks.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_pks)).'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->masa_berlaku)).'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                </tr>';
                $no++;
                // <td class="text-center">'.rupiah($v->nilai_pekerjaan).'</td>
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='nonsetoran'){
            $table='<h3 class="text-center">Rincian Nilai Non Setoran</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nilai Rekomendasi (Rp)</th>';
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoran::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->nilai_rekomendasi,0,',','.').'</td>';
                $table.='</tr>';
                $no++;
            }
            $table.='</tbody>';
            $table.='</table>';
        }elseif($jenis=='nonsetoranumum'){
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Umum (Non Setoran)</h3><table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">Keterangan</th>';
                $table.='</tr>';
            $table.='</thead><tbody>';

            $rincian=RincianNonSetoranUmum::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>';
                $table.='</tr>';
                $no++;
            }
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            $rincian=RincianNonSetoranPertanggungjawabanUangMuka::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $totalNilaiTitle = number_format($rincian->sum('jumlah_um'),0,',','.');
            if($totalNilaiTitle == 0)
                $totalNilaiTitle = 'Rp 0,-';
            $table='<h3 class="text-center">Rincian Nilai – Rekomendasi Pertanggungjawaban Uang Muka</h3>
            <h5 class="text-center">Total Rekomendasi: '.$totalNilaiTitle.'</h5>
            <table class="table table-bordered " id="table-tl-rincian-'.$idrekom.'">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja - PIC 2</th>
                    <th class="text-center">No. Invoice</th>
                    <th class="text-center">Tanggal UM</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah UM (Rp)</th>';
                $table.='</tr>';
            $table.='</thead><tbody>';
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_invoice.'</td>
                    <td class="text-center">'.$v->tgl_um.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_um,0,',','.').'</td>';
                $table.='</tr>';
                $no++;
            }
            $table.='<input type="hidden" id="total_nilai" name="total_nilai" value="-1">';
            $table.='</tbody>';
            $table.='</table>';
        }

        echo $table;
    }

    public function publish_rekomendasi_to_auditor_senior($idrekomendasi)
    {
        $rekom=DataRekomendasi::find($idrekomendasi);
        $rekom->published=1;
        $c=$rekom->save();
        if($c)
            echo 1;
        else
            echo 0;
    }
    public function publish_rekomendasi($idrekomendasi)
    {
        $rekom=DataRekomendasi::find($idrekomendasi);
        $rekom->rekom_publish=1;
        $c=$rekom->save();
        if($c)
            echo 1;
        else
            echo 0;
    }
    public function publish_rekomendasi_to_auditor_junior($idrekomendasi)
    {
        $rekom=DataRekomendasi::find($idrekomendasi);
        $rekom->publish_pic_1=1;
        $c=$rekom->save();
        if($c){

            $temuanData = DataTemuan::where('id',$rekom->id_temuan)->first();
            $temuan=DaftarTemuan::where('id',$temuanData->id_lhp)->first();
            $this->createNotification($temuan->id, $idrekomendasi, $temuan->user_input_id, $temuanData->id,
            Auth::user()->name .' telah mempublish ke auditor');
            $su = User::where('level', 'super-user')->get();
            $sorted = array();
            $this->createNotification($temuan->id, $idrekomendasi, $rekom->senior_user_id, $temuanData->id, 
            Auth::user()->name .' telah mempublish ke auditor');
            foreach($su as $a=>$s){
                $this->createNotification($temuan->id, $idrekomendasi, $s->id, $temuanData->id,
                Auth::user()->name .' telah mempublish ke auditor');
            }

            echo 1;
        }
        else
            echo 0;
    }
    public function publish_rekomendasi_to_pic1($idrekomendasi)
    {
        $rekom=DataRekomendasi::find($idrekomendasi);
        $rekom->publish_pic_2=1;
        $c=$rekom->save();
        if($c)
            echo 1;
        else
            echo 0;
    }

    public function list_rangkuman($idrekomendasi)
    {
        $rekom=DataRekomendasi::where('id',$idrekomendasi)->with('picunit1')->with('picunit2')->first();
        // return $rekom;
        $picunit=PICUNit::all();
        $pic=$user_pic=array();
        foreach($picunit as $k=>$v){
            $pic[$v->id]=$v;
        }

        $d_tindaklanjut=TindakLanjutTemuan::where('rekomendasi_id',$idrekomendasi)->orderBy('tgl_tindaklanjut')->get();
        $pic1=$pic2=$arrayidtl=array();
        foreach($d_tindaklanjut as $k=>$v)
        {
            if($v->pic_1_id!=0)
            {
                $pic1['tindak_lanjut'][]=$v;
                $pic1['action_plan'][]=$v->action_plan;

            }
            if($v->pic_2_id!=0)
            {
                $pic2['tindak_lanjut'][]=$v;
                $pic2['action_plan'][]=$v->action_plan;
            }
            $arrayidtl[$v->id]=$v->id;
        }

        $dok=DokumenTindakLanjut::whereIn('id_tindak_lanjut_temuan',$arrayidtl)->get();
        $dokumen=array();
        foreach($dok as $k=>$v)
        {
            $dokumen[$v->id_tindak_lanjut_temuan]=$v;
        }
        return view('backend.pages.data-lhp.pic-unit.list-rangkuman')
        ->with('rekom',$rekom)
                ->with('dokumen',$dokumen)
                ->with('pic',$pic)
                ->with('pic1',$pic1)
                ->with('pic2',$pic2)
                ->with('idrekomendasi',$idrekomendasi);
    }

    public function rangkuman_simpan(Request $request)
    {
        $path='';
        if($request->hasFile('file_pendukung')){
            $file = $request->file('file_pendukung');
            $filenameWithExt = $request->file('file_pendukung')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file_pendukung')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('file_pendukung')->storeAs('public/dokumen',$fileNameToStore);
        }

        $idrekomendasi=$request->idrekomendasi;
        $simpan = DataRekomendasi::find($idrekomendasi);
        $simpan->rangkuman_rekomendasi=$request->txt_rangkuman_rekomendasi;
        $simpan->file_pendukung=$path;
        $c=$simpan->save();

        if($c)
            echo 1;
        else
            echo 0;
    }

    public function formupdaterincian($idtemuan,$idrekom)
    {
        $user_pic=PICUnit::get();
        $userpic=array();
        foreach($user_pic as $k=>$v)
        {
            $userpic[$v->id]=$v;
        }
        $rekomendasi=DataRekomendasi::where('id',$idrekom)->with('picunit1')->with('picunit2')->first();
        $temuan=DataTemuan::find($idtemuan);
        // return $userpic[$rekomendasi->pic_1_temuan_id];
        // return $user_pic;
        $form='<div class="form-group" style="margin-bottom:10px;">
                <label for="exampleTextInput1" class="col-sm-3 control-label text-right">Rincian Tindak Lanjut :
                </label>
                <div class="col-sm-9">
                    <select name="rincian_tl" class="form-control" id="rincian_tl" data-plugin="select2" onchange="pilihrincian(this.value,\''.$idtemuan.'\',\''.$idrekom.'\')">
                        <option value="">-- Pilih --</option>';
                        foreach (rinciantindaklanjut() as $key=>$item)
                        {
                            if($rekomendasi->rincian==$key)
                                $form.='<option value="'.$key.'" selected="selected">'.$item.'</option>';
                            else
                                $form.='<option value="'.$key.'">'.$item.'</option>';
                        }
            $form.='</select>
                </div>
            </div>';
        $form.='<input type="hidden" name="idlhp" id="idlhp" value="'.$temuan->id_lhp.'">';
        $form.='<input type="hidden" name="idrekom" id="idrekom" value="'.$idrekom.'">';
        $form.='<div class="form-group" style="margin-bottom:10px;">
                <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC 1 :
                </label>
                <div class="col-sm-9" style="padding-top:5px;"><span class="label label-info fz-md">'.(isset($userpic[$rekomendasi->pic_1_temuan_id]) ? $userpic[$rekomendasi->pic_1_temuan_id]->nama_pic : '') .'</span></div>
            </div>';
        if($rekomendasi->pic_2_temuan_id!='' && $rekomendasi->pic_2_temuan_id!=',')
        {
            $form.='<div class="form-group" style="margin-bottom:10px;">
                <label for="exampleTextInput1" class="col-sm-3 control-label text-right">PIC 2 :
                </label>
                <div class="col-sm-9" style="padding-top:5px;">';
                $pic2list=explode(',',$rekomendasi->pic_2_temuan_id);
                $form.='<ul>';
                foreach($pic2list as $kp=>$vp)
                {
                    $form.='<li style="margin-bottom:5px;"><span class="label label-default fz-sm">'.(isset($userpic[$vp]) ? $userpic[$vp]->nama_pic : '') .'</span></li>';
                }
                $form.='</ul>';
            $form.='</div>
            </div>';
        }
       
        // if($rekomendasi->rincian)
        $formm=$this->load_tabel_rincian($rekomendasi->rincian,$idtemuan,$idrekom,null);
        $form.='<hr>';
        $form.='<div id="det-update-rincian">'.$formm.'</div>';

        return $formm;
    }

    public function setujui_rekomendasi($idrekom)
    {
        $rekom=DataRekomendasi::find($idrekom);
        $rekom->senior_publish=1;
        $rekom->save();

        $temuanData = DataTemuan::where('id',$rekom->id_temuan)->first();
        $temuan=DaftarTemuan::where('id',$temuanData->id_lhp)->first();
        $this->createNotification($temuan->id, $idrekom, $temuan->user_input_id, $temuanData->id,
        Auth::user()->name .' telah menyetujui rekomendasi');
        $su = User::where('level', 'super-user')->get();
        $sorted = array();
        foreach($su as $k=>$v){
            if(!isset($sorted[$v->id])){
                $sorted[$v->id][] = $v;
                $this->createNotification($temuan->id, $idrekom, $v->id, $temuanData->id,
                Auth::user()->name .' telah menyetujui rekomendasi');
            }else{
                $sorted[$v->id] = array($v);
            }
        }
    }

    public function createNotification($idlhp, $idrekom, $userId, $idtemuan,$status=null, $navigate=null){
        $notification = new MappingRekomendasiNotifikasi();
        $notification->id_lhp = $idlhp;
        $notification->id_rekomendasi = $idrekom;
        $notification->user_id = $userId;
        $notification->id_temuan = $idtemuan;
        $notification->status = $status;
        $notification->navigate = $navigate;
        $notification->save();
    }
}
