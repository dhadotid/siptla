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

        $insert->save();

        $idlhp=$request->id_lhp;
        return redirect('data-temuan-lhp/'.$idlhp)
            ->with('success', 'Anda telah memasukkan data rekomendasi baru untuk <B><u>Nomor Temuan : '.$notemuan.'</u></B>.');
    }
    public function rekomendasi_data($idtemuan)
    {
        $rekom=DataRekomendasi::where('id_temuan',$idtemuan)
                ->with('picunit1')
                ->with('picunit2')
                ->with('statusrekomendasi')
                ->get();
        $table='<table class="table table-bordered">';
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
                $table.='<tr>
                        <td style="background:#fff;">'.str_replace("\n",'<br>',$v->rekomendasi).'</td>
                        <td style="background:#fff;" class="text-right">'.number_format($v->nominal_rekomendasi,2,',','.').'</td>
                        <td style="background:#fff;" class="text-center">'.(isset($v->picunit1->nama_pic) ? $v->picunit1->nama_pic : 'n/a').'</td>
                        <td style="background:#fff;" class="text-center"></td>
                        <td style="background:#fff;" class="text-center">'.(isset($v->statusrekomendasi->rekomendasi) ? $v->statusrekomendasi->rekomendasi : 'n/a').'</td>
                        <td style="background:#fff;" class="text-center">'.$tindaklanjut.'</td>
                        <td style="background:#fff;" class="text-center">
                            <div style="width:90px;text-align:center;margin:0 auto;">
                                <a class="btn btn-xs btn-success rounded" data-toggle="tooltip" title="Detail Rekomendasi">
                                    <div class="tooltipcss"><i class="glyphicon glyphicon-list"></i>
                                        <span class="tooltiptext">Detail Rekomendasi</span>
                                    </div></a>
                                <a class="btn btn-xs btn-primary rounded btn-edit" data-toggle="modal" data-target="#modalubah" data-value="0">
                                    <div class="tooltipcss"><i class="glyphicon glyphicon-edit"></i>
                                        <span class="tooltiptext">Edit Rekomendasi</span>
                                    </div></a>
                                <a class="btn btn-xs btn-danger rounded btn-delete" data-toggle="modal" data-target="#modalhapus" data-value="0">
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

    }
    public function rekomendasi_update(Request $request,$idtemuan)
    {

    }
    public function rekomendasi_delete($idrekom)
    {

    }
}
