<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekomendasi;
use App\Models\DaftarRekanan;
class DataRekomendasiController extends Controller
{
    public function rekomendasi_simpan(Request $request)
    {
        // return $request->all();
        $insert=new DataRekomendasi;
        $insert->no_temuan=$notemuan=$request->nomor_temuan;
        $insert->id_temuan=$request->id_temuan;
        $insert->jenis_temuan=$request->jenis_temuan;
        $insert->nominal_rekomendasi=str_replace('.','',$request->nilai_rekomendasi);
        $insert->rekomendasi=$request->rekomendasi;
        $insert->pic_1_temuan_id=$request->pic_1;
        $insert->pic_2_temuan_id=$request->pic_2;
        $insert->jangka_waktu_id=$request->jangka_waktu;
        $insert->status_rekomendasi_id=$request->status_rekomendasi;
        $insert->review_auditor=$request->review_auditor;

        $rekanan=$request->rekanan;
        if($rekanan!='')
        {
            $cekrekanan=DaftarRekanan::where('nama',$rekanan)->first();
            if($cekrekanan)
            {
                $insert->rekanan=$cekrekanan->id;
            }
            else
            {
                $new_rekan=new DaftarRekanan;
                $new_rekan->nama=$rekanan;
                $new_rekan->save();

                $insert->rekanan=$new_rekan->id;
            }
        }

        $insert->save();

        $idlhp=$request->id_lhp;
        return redirect('data-temuan-lhp/'.$idlhp)
            ->with('success', 'Anda telah memasukkan data rekomendasi baru untuk <B><u>Nomor Temuan : '.$notemuan.'</u></B>.');
    }
    public function rekomendasi_data($idtemuan)
    {
        $rekom=DataRekomendasi::selectRaw('*,data_rekomendasi.id as rekom_id')->where('id_temuan',$idtemuan)
                ->with('picunit1')
                ->with('picunit2')
                ->with('statusrekomendasi')
                ->get();
        $table='<table class="table table-bordered" id="table-rekom">';
        $table.='<thead>
                    <tr>
                        <th class="text-center">Rekomendasi</th>
                        <th class="text-center">Nilai<br>Rekomendasi</th>
                        <th class="text-center">PIC 1</th>
                        <th class="text-center">PIC 2</th>
                        <th class="text-center">Status<br>Rekomendasi</th>
                        <th class="text-center">Tindak Lanjut</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>';
        $table.='<tbody>';
        if($rekom->count()!=0)
        {
            foreach($rekom as $k=>$v)
            {
                $tindaklanjut='<div style="width:150px;text-align:center;margin:0 auto;">
                                <span style="cursor:pointer" class="label label-primary fz-sm">0</span>
                                <span style="cursor:pointer" class="label label-success fz-sm">Tindak Lanjut</span>
                                <span style="cursor:pointer" class="label label-info fz-sm" data-toggle="modal" data-target="#modaltambahrekomendasi"> 
                                    <a style="color:#fff" data-value="0">
                                        <div class="tooltipcss"><i class="fa fa-plus-circle"></i>
                                            <span class="tooltiptext">Tambah Tindak Lanjut</span>
                                        </div></a>
                                </span>
                            </div>';
                $table.='<tr id="data_rekom_'.$v->rekom_id.'">
                        <td style="background:#fff;" id="rekom_'.$v->id_temuan.'_'.$v->rekom_id.'">
                            '.str_replace("\n",'<br>',$v->rekomendasi).'
                        </td>
                        <td style="background:#fff;" class="text-right" id="nominal_'.$v->id_temuan.'_'.$v->rekom_id.'">
                            '.number_format($v->nominal_rekomendasi,2,',','.').'
                        </td>
                        <td style="background:#fff;" class="text-center" id="pic1_'.$v->id_temuan.'_'.$v->rekom_id.'">
                            '.(isset($v->picunit1->nama_pic) ? $v->picunit1->nama_pic : 'n/a').'
                        </td>
                        <td style="background:#fff;" class="text-center" id="pic2_'.$v->id_temuan.'_'.$v->rekom_id.'"></td>
                        <td style="background:#fff;" class="text-center" id="status_'.$v->id_temuan.'_'.$v->rekom_id.'">
                            '.(isset($v->statusrekomendasi->rekomendasi) ? $v->statusrekomendasi->rekomendasi : 'n/a').'
                        </td>
                        <td style="background:#fff;" class="text-center" id="tindak_'.$v->id_temuan.'_'.$v->rekom_id.'">
                            '.$tindaklanjut.'
                        </td>
                        <td style="background:#fff;" class="text-center">
                            <div style="width:90px;text-align:center;margin:0 auto;">
                                <a class="btn btn-xs btn-success rounded" onclick="detailrekomendasi('.$v->rekom_id.')">
                                    <div class="tooltipcss"><i class="glyphicon glyphicon-list"></i>
                                        <span class="tooltiptext">Detail Rekomendasi</span>
                                    </div></a>
                                <a class="btn btn-xs btn-primary rounded" onclick="editrekomendasi('.$v->rekom_id.')">
                                    <div class="tooltipcss"><i class="glyphicon glyphicon-edit"></i>
                                        <span class="tooltiptext">Edit Rekomendasi</span>
                                    </div></a>
                                <a class="btn btn-xs btn-danger rounded btn-delete-rekomendasi" onclick="hapusrekomendasi('.$v->rekom_id.','.$idtemuan.')">
                                    <div class="tooltipcss"><i class="glyphicon glyphicon-trash"></i>
                                        <span class="tooltiptext">Hapus Rekomendasi</span>
                                    </div></a>
                            </div>
                        </td>
                    </tr>';
            }
        }
        else
        {
            $table.='<tr><td style="background:#fff;font-weight:bold" colspan="7" class="text-center">Rekomendasi Masih Kosong</td></tr>';
        }
        $table.='</tbody>';
        $table.='</table>';

        return $table;
    }
    public function rekomendasi_edit($idrekom)
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
        $update->pic_2_temuan_id=$request->pic_2;
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
}
