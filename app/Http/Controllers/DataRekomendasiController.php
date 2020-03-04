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
use Auth;
class DataRekomendasiController extends Controller
{
    public function rekomendasi_simpan(Request $request)
    {
        // return $request->all();
        $insert=new DataRekomendasi;
        $insert->nomor_rekomendasi=$notemuan=$request->no_rekomendasi;
        $insert->no_temuan=$notemuan=$request->nomor_temuan;
        $insert->id_temuan=$request->id_temuan;
        $insert->jenis_temuan=$request->jenis_temuan;
        $insert->nominal_rekomendasi=str_replace('.','',$request->nilai_rekomendasi);
        $insert->rekomendasi=$request->rekomendasi;
        $insert->senior_user_id=$request->senior_auditor;
        $insert->senior_publish=0;
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
            $insert->pic_2_temuan_id=substr($pic2,0,-1);
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
                $tl=RincianSewa::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='uangmuka')
                $tl=RincianUangMuka::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='listrik')
                $tl=RincianListrik::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='piutang')
                $tl=RincianPiutang::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='piutangkaryawan')
                $tl=RincianPiutangKaryawan::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='hutangtitipan')
                $tl=RincianHutangTitipan::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='penutupanrekening')
                $tl=RincianPenutupanRekening::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();
            elseif($rinciantl=='umum')
                $tl=RincianUmum::where('id_rekomendasi',$rekomid_tl)->where('id_temuan',$request->id_temuan)->get();

            foreach($tl as $k=>$v)
            {
                $v->id_rekomendasi=$idrekomendasi;
                $v->save();
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
        // $table='<table class="table table-bordered" id="table-rekom">';
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

                $table.='<li style="margin-bottom:10px;padding:10px 0;border-bottom:1px solid #bbb;">
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
    public function rekomendasi_data_new($idtemuan,$status_rekom)
    {
        $table='<ol style="list-style-type:upper-roman !important;padding-left:20px;">';
        $picunit=PICUnit::all();
        $pic_unit=datauserpic($picunit);
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')
                ->where('id_temuan',$idtemuan)
                ->where('status_rekomendasi_id',$status_rekom)
                ->with('picunit1')
                ->with('picunit2')
                ->with('statusrekomendasi')
                ->get();
        

        $tl=$this->tindaklanjut();
        // return $tl[3];
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

                $table.='<li style="margin-bottom:10px;padding:10px 0;border-bottom:1px solid #bbb;">
                    <a href="javascript:rekomedit(\''.$v->id_temuan.'\',\''.$v->id.'\')" class="btn btn-info btn-xs pull-right"><i class="fa fa-edit"></i> Edit Rekomendasi</a>
                    <u>Nilai Rekomendasi :</u><br><h5><span class="text-primary">Rp.'.number_format($v->nominal_rekomendasi,2,',','.').'</span></h5>
                    <br>
                    <u>Rekomendasi : </u><br><h4>'.$v->rekomendasi.'</h4><br>
                    <a href="#" class="btn btn-sm btn-'.($status).'">'.$v->statusrekomendasi->rekomendasi.'</a>
                    <br>
                    <div style="margin-top:10px;">
                        <a class="label label-primary fz-sm" href="'.url('data-tindak-lanjut/'.$v->rekom_id.'/'.$idtemuan.'').'" target="_blank">'.(isset($tl[$v->rekom_id]) ? count($tl[$v->rekom_id]) : 0).'&nbsp;Tindak Lanjut</a> &nbsp;';
                        //<a style="color:#fff" href="javascript:formtindaklanjut('.$v->rekom_id.',-1)" class="label label-info fz-sm" data-value="0"><i class="fa fa-plus-circle"></i>&nbsp;Tambah Rincian</a>
                $table.='</div>
                </li>';
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
    public function rekomendasi_edit($idrekom)
    {
        $picunit=
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
        $update->pic_2_temuan_id=substr($pic2,0,-1);
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

        return $this->rekomendasi_edit($idrekom);
    }
    public function rekomendasi_delete($idrekom,$idtemuan)
    {
        DataRekomendasi::destroy($idrekom);
        // return $idtemuan;

        $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();
        // $jlhrekom=isset($rekomendasi[$item->temuan_id]) ? count($rekomendasi[$item->temuan_id]) : 0;

        $data['jlh']='<span style="cursor:pointer" class="label label-'.($rekom->count()==0 ? 'dark' : 'primary').' fz-sm">'.$rekom->count().'</span>';
        return $data;
    }

    public function tabletindaklanjut($idtemuan,$idrekom,$data_tl)
    {
        $table='<table class="table table-bordered">';
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
        $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
        $idrekomendasi=$statusrekomendasi;
        $table='';
        if($jenis=='sewa')
        {
            $rincian=RincianSewa::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-sewa')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
        elseif($jenis=='uangmuka')
        {
            $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-uangmuka')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);

           
        }
        elseif($jenis=='listrik')
        {
            $rincian=RincianListrik::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-listrik')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='piutang')
        {
            $rincian=RincianPiutang::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-piutang')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='piutangkaryawan')
        {
            $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-piutangkaryawan')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='hutangtitipan')
        {
            $rincian=RincianHutangTitipan::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-hutangtitipan')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
            
        }
        elseif($jenis=='penutupanrekening')
        {
            $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-penutupanrekening')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);

            
        }
        elseif($jenis=='umum')
        {
            $rincian=RincianUmum::where('id_temuan',$idtemuan)->where('unit_kerja_id',$user_pic->id)->get();
            return view('backend.pages.data-lhp.rincian-table.table-umum')
                    ->with('rincian',$rincian)
                    ->with('idtemuan',$idtemuan)
                    ->with('jenis',$jenis)
                    ->with('idrekomendasi',$idrekomendasi);
        }
    }
    public function load_tabel_rincian($jenis,$idtemuan=null,$statusrekomendasi=null,$view=null)
    {
        $idrekomendasi=$statusrekomendasi;
        $table='';
        if($jenis=='sewa')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Sewa</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Mitra</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tgl. PKS</th>
                    <th class="text-center">Nilai Pekerjaan</th>
                    <th class="text-center">Masa Berlaku</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianSewa::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
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
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'sewa\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;
                $totalnilai+=$v->nilai_pekerjaan;
                
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='uangmuka')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Uang Muka</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">No. Invoice</th>
                    <th class="text-center">Tanggal PUM</th>
                    <th class="text-center">Jumlah UM</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianUangMuka::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->no_invoice.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_pum)).'</td>
                    <td class="text-center">'.number_format($v->jumlah_pum,0,',','.').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'uangmuka\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;
                $totalnilai+=$v->jumlah_pum;
                
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'uangmuka\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='listrik')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Listrik</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Lokasi</th>
                    <th class="text-center">Tanggal Invoice</th>
                    <th class="text-center">Tagihan</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianListrik::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->lokasi.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tgl_invoice)).'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'listrik\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->tagihan;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';
            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'listrik\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle" ></i> Tambah Rincian</a></td>
                    </tr>';
                }
        
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='piutang')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Piutang</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Pelanggan</th>
                    <th class="text-center">Jumlah Tagihan</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPiutang::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->pelanggan.'</td>
                    <td class="text-center">'.number_format($v->tagihan,0,',','.').'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'piutang\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->tagihan;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';
            
            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'piutang\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='piutangkaryawan')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Piutang Karyawan</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Karyawan</th>
                    <th class="text-center">Jumlah Pinjaman</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPiutangKaryawan::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->karyawan.'</td>
                    <td class="text-center">'.number_format($v->pinjaman,0,',','.').'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'piutangkaryawan\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->pinjaman;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'piutangkaryawan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='hutangtitipan')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Hutang Titipan</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Saldo Hutang Titipan (Rp)</th>
                    <th class="text-center">Sisa Yang Harus Disetor (Rp)</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianHutangTitipan::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.date('d/m/Y',strtotime($v->tanggal)).'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->saldo_hutang,0,',','.').'</td>
                    <td class="text-center">'.number_format($v->sisa_setor,0,',','.').'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'hutangtitipan\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->sisa_setor;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'hutangtitipan\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='penutupanrekening')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Penutupan Rekening</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Nama Bank</th>
                    <th class="text-center">Nomor Rekening</th>
                    <th class="text-center">Nama Rekening</th>
                    <th class="text-center">Jenis Rekening</th>
                    <th class="text-center">Saldo Akhir</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPenutupanRekening::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->nama_bank.'</td>
                    <td class="text-center">'.$v->nomor_rekening.'</td>
                    <td class="text-center">'.$v->nama_rekening.'</td>
                    <td class="text-center">'.$v->jenis_rekening.'</td>
                    <td class="text-center">'.number_format($v->saldo_akhir,0,',','.').'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'penutupanrekening\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->saldo_akhir;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'penutupanrekening\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='umum')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Umum</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah Rekomendasi</th>
                    <th class="text-center">Aksi</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianUmum::where('id_temuan',$idtemuan)->get();
            $no=1;
            $totalnilai=0;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->keterangan.'</td>
                    <td class="text-center">'.number_format($v->jumlah_rekomendasi,0,',','.').'</td>
                    <td class="text-center">
                        <a href="javascript:hapusrincian('.$v->id.',\'umum\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $no++;

                $totalnilai+=$v->jumlah_rekomendasi;
            }
            $table.='<input type="hidden" id="total_nilai" value="'.$totalnilai.'">';

            if(Auth::user()->level=='pic-unit')
            {
                $table.='<tr >
                            <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'sewa\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                        </tr>';
            }
            else
            {
                if($view==null)
                {
                    $table.='<tr >
                        <td class="text-center" colspan="8"><a href="#" onclick="addtindaklanjut(\'umum\',\''.$idtemuan.'\',\''.$idrekomendasi.'\',-1)" class="label label-info" id="tombol-add-rincian" style="display:inline"><i class="fa fa-plus-circle"></i> Tambah Rincian</a></td>
                    </tr>';
                }
            }
            $table.='</tbody>';
            $table.='</table>';
        }

        return $table;
    }

    public function rekomendasi_by_temuan($idtemuan,$status=null)
    {
        if($status==null)
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->get();
        else
            $rekom=DataRekomendasi::where('id_temuan',$idtemuan)->where('status_rekomendasi_id',$status)->get();

        return $rekom;
    }

    public function rincian_nilai($idrekom)
    {
        $rekom=DataRekomendasi::find($idrekom);
        $jenis=$rincian=$rekom->rincian;
        $idtemuan=$rekom->id_temuan;
        if($jenis=='sewa')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Sewa</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Mitra</th>
                    <th class="text-center">No. PKS</th>
                    <th class="text-center">Tgl. PKS</th>
                    <th class="text-center">Nilai Pekerjaan</th>
                    <th class="text-center">Masa Berlaku</th>
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
            
            $table='<h3 class="text-center">Rincian Nilai Uang Muka</h3><table class="table table-bordered">';
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
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Listrik</h3><table class="table table-bordered">';
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
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Piutang</h3><table class="table table-bordered">';
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
            
            $table='<h3 class="text-center">Rincian Nilai Pembayaran Piutang Karyawan</h3><table class="table table-bordered">';
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
            
            $table='<h3 class="text-center">Rincian Nilai Hutang Titipan</h3><table class="table table-bordered">';
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
            
            $table='<h3 class="text-center">Rincian Nilai Penutupan Rekening</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Nama Bank</th>
                    <th class="text-center">Nomor Rekening</th>
                    <th class="text-center">Nama Rekening</th>
                    <th class="text-center">Jenis Rekening</th>
                    <th class="text-center">Saldo Akhir</th>
                </tr>';
            $table.='</thead><tbody>';

            $rincian=RincianPenutupanRekening::where('id_rekomendasi',$idrekom)->where('id_temuan',$idtemuan)->get();
            $no=1;
            foreach($rincian as $k=>$v)
            {
                $table.='<tr>
                    <td class="text-center">'.$no.'</td>
                    <td class="text-center">'.$v->unit_kerja.'</td>
                    <td class="text-center">'.$v->nama_bank.'</td>
                    <td class="text-center">'.$v->nomor_rekening.'</td>
                    <td class="text-center">'.$v->nama_rekening.'</td>
                    <td class="text-center">'.$v->jenis_rekening.'</td>
                    <td class="text-center">'.number_format($v->saldo_akhir,0,',','.').'</td>
                </tr>';
                $no++;
            }

            $table.='</tbody>';
            $table.='</table>';
        }
        elseif($jenis=='umum')
        {
            
            $table='<h3 class="text-center">Rincian Nilai Umum</h3><table class="table table-bordered">';
            $table.='<thead>';
                $table.='<tr class="inverse">
                    <th class="text-center">No</th>
                    <th class="text-center">Unit Kerja</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah Rekomendasi</th>
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
        }

        echo $table;
    }
}
