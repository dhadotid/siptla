<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RincianSewa;
use App\Models\RincianUangMuka;
use App\Models\RincianListrik;
use App\Models\RincianPiutang;
use App\Models\PICUnit;
class RincianSewaController extends Controller
{
    public function form_rincian($jenis,$idtemuan,$idrekomendasi,$id=-1)
    {
        if($jenis=='sewa')
        {
            $pic=PICUnit::all();
            return view('backend.pages.data-lhp.rincian-form.form-sewa')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='uangmuka')
        {
            $pic=PICUnit::all();
            return view('backend.pages.data-lhp.rincian-form.form-uangmuka')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='listrik')
        {
            $pic=PICUnit::all();
            return view('backend.pages.data-lhp.rincian-form.form-listrik')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='piutang')
        {
            $pic=PICUnit::all();
            return view('backend.pages.data-lhp.rincian-form.form-piutang')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
    }

    public function form_rincian_simpan(Request $request)
    {
        // return $request->all();
        $id=$request->id;
        if($id==-1)
        {
            if($request->jenis=='sewa')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianSewa;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idrekomendasi;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->mitra=$request->mitra;
                $simpan->no_pks=$request->no_pks;
                $simpan->tgl_pks=$request->tgl_pks;
                $simpan->nilai_pekerjaan=str_replace(array(',','.'),'',$request->nilai_perjanjian);
                $simpan->masa_berlaku=$request->masa_berlaku;
                $save=$simpan->save();
                return $request->all();
            }
            elseif($request->jenis=='uangmuka')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianUangMuka;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idrekomendasi;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->no_invoice = $request->no_invoice;
                $simpan->tgl_pum = $request->tgl_pum;
                $simpan->jumlah_pum = str_replace(array(',','.'),'',$request->jumlah_um);
                $simpan->keterangan = $request->keterangan;
                $save=$simpan->save();
                return $request->all();
            }
            elseif($request->jenis=='listrik')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianListrik;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idrekomendasi;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->lokasi = $request->lokasi;
                $simpan->tgl_invoice = $request->tgl_invoice;
                $simpan->tagihan = str_replace(array(',','.'),'',$request->tagihan);
                $simpan->keterangan = $request->keterangan;
                $save=$simpan->save();
                return $request->all();
            }
            elseif($request->jenis=='piutang')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianPiutang;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idrekomendasi;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->pelanggan = $request->pelanggan;
                $simpan->tagihan = str_replace(array(',','.'),'',$request->tagihan);
                $save=$simpan->save();
                return $request->all();
            }
            // if($save)
            //     return 1;
            // else
            //     return 0;
        }
    }

    public function form_rincian_hapus($id,$jenis)
    {
        if($jenis=='sewa')
            $data=RincianSewa::find($id);
        elseif($jenis=='uangmuka')
            $data=RincianUangMuka::find($id);
        elseif($jenis=='listrik')
            $data=RincianListrik::find($id);
        elseif($jenis=='piutang')
            $data=RincianPiutang::find($id);

        $dt['jenis']=$data->jenis;
        $dt['idtemuan']=$data->id_temuan;
        $dt['idrekomendasi']=$data->id_rekomendasi;
        $data->delete();
        return $dt;
    }
}
