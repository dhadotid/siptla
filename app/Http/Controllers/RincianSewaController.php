<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekomendasi;
use App\Models\RincianSewa;
use App\Models\RincianUangMuka;
use App\Models\RincianListrik;
use App\Models\RincianPiutang;
use App\Models\RincianPiutangKaryawan;
use App\Models\RincianHutangTitipan;
use App\Models\PICUnit;
use App\Models\RincianPenutupanRekening;
use App\Models\RincianUmum;
use App\Models\RincianKontribusi;
use App\Models\TindakLanjutTemuan;
use App\Models\RincianNonSetoranPerpanjanganPerjanjianKerjasama;
use App\Models\RincianNonSetoran;
use App\Models\RincianNonSetoranUmum;
use App\Models\RincianNonSetoranPertanggungjawabanUangMuka;
use Auth;
class RincianSewaController extends Controller
{
    public function form_rincian($jenis,$idtemuan,$idrekomendasi,$id=-1,$pic1=null,$pic2=null)
    {

        $dpic=$array_pic=array();
        if($pic1!=null)
            $dpic[]=$pic1;

        
        if($pic2!=null)
        {
            $array_pic=explode(',',$pic2);
            foreach($array_pic as $k=>$v)
            {
                $dpic[]=$v;
            }
        }

        $pic=PICUnit::whereIn('id',$dpic)->orderBy('nama_pic')->get();
        // return $pic;
        // if($id!=-1)
        //     $pic=PICUnit::where('id_user',Auth::user()->id)->get();
        // else
        //     $pic=PICUnit::orderBy('nama_pic')->get();

        // $pic=array();
        if($jenis=='sewa')
        {
            return view('backend.pages.data-lhp.rincian-form.form-sewa')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='uangmuka')
        {
            return view('backend.pages.data-lhp.rincian-form.form-uangmuka')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='listrik')
        {
            return view('backend.pages.data-lhp.rincian-form.form-listrik')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='piutang')
        {
            return view('backend.pages.data-lhp.rincian-form.form-piutang')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='piutangkaryawan')
        {
            return view('backend.pages.data-lhp.rincian-form.form-piutangkaryawan')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='hutangtitipan')
        {
            return view('backend.pages.data-lhp.rincian-form.form-hutangtitipan')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='penutupanrekening')
        {
            return view('backend.pages.data-lhp.rincian-form.form-penutupanrekening')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='umum')
        {
            return view('backend.pages.data-lhp.rincian-form.form-umum')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='kontribusi'){
            return view('backend.pages.data-lhp.rincian-form.form-kontribusi')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='nonsetoranperjanjiankerjasama'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranperjanjiankerjasama')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='nonsetoran'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoran')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='nonsetoranumum'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranumum')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranpertanggungjawabanuangmuka')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
    }
    public function form_rincian2($jenis,$idtemuan,$idrekomendasi,$id=-1)
    {

        $dpic=$array_pic=array();
        $rekom=DataRekomendasi::find($idrekomendasi);
        if($rekom->pic_1_temuan_id!=null)
            $dpic[]=$rekom->pic_1_temuan_id;

        
        if($rekom->pic_2_temuan_id!='' && $rekom->pic_2_temuan_id!=',')
        {
            $array_pic=explode(',',$rekom->pic_2_temuan_id);
            foreach($array_pic as $k=>$v)
            {
                $dpic[]=$v;
            }
        }

        $pic=PICUnit::whereIn('id',$dpic)->orderBy('nama_pic')->get();
       
        if($jenis=='sewa')
        {
            return view('backend.pages.data-lhp.rincian-form.form-sewa')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='uangmuka')
        {
            return view('backend.pages.data-lhp.rincian-form.form-uangmuka')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='listrik')
        {
            return view('backend.pages.data-lhp.rincian-form.form-listrik')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='piutang')
        {
            return view('backend.pages.data-lhp.rincian-form.form-piutang')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='piutangkaryawan')
        {
            return view('backend.pages.data-lhp.rincian-form.form-piutangkaryawan')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='hutangtitipan')
        {
            return view('backend.pages.data-lhp.rincian-form.form-hutangtitipan')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='penutupanrekening')
        {
            return view('backend.pages.data-lhp.rincian-form.form-penutupanrekening')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        elseif($jenis=='umum')
        {
            return view('backend.pages.data-lhp.rincian-form.form-umum')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }
        else if($jenis=='kontribusi'){
            return view('backend.pages.data-lhp.rincian-form.form-kontribusi')
                ->with('jenis',$jenis)
                ->with('idtemuan',$idtemuan)
                ->with('idrekomendasi',$idrekomendasi)
                ->with('idform',$idrekomendasi)
                ->with('id',$id)
                ->with('pic',$pic)
                ->with('jenis',$jenis);
        }else if($jenis=='nonsetoranperjanjiankerjasama'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranperjanjiankerjasama')
            ->with('jenis',$jenis)
            ->with('idtemuan',$idtemuan)
            ->with('idrekomendasi',$idrekomendasi)
            ->with('idform',$idrekomendasi)
            ->with('id',$id)
            ->with('pic',$pic)
            ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoran'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoran')
            ->with('jenis',$jenis)
            ->with('idtemuan',$idtemuan)
            ->with('idrekomendasi',$idrekomendasi)
            ->with('idform',$idrekomendasi)
            ->with('id',$id)
            ->with('pic',$pic)
            ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoranumum'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranumum')
            ->with('jenis',$jenis)
            ->with('idtemuan',$idtemuan)
            ->with('idrekomendasi',$idrekomendasi)
            ->with('idform',$idrekomendasi)
            ->with('id',$id)
            ->with('pic',$pic)
            ->with('jenis',$jenis);
        }elseif($jenis=='nonsetoranpertanggungjawabanuangmuka'){
            return view('backend.pages.data-lhp.rincian-form.form-nonsetoranpertanggungjawabanuangmuka')
            ->with('jenis',$jenis)
            ->with('idtemuan',$idtemuan)
            ->with('idrekomendasi',$idrekomendasi)
            ->with('idform',$idrekomendasi)
            ->with('id',$id)
            ->with('pic',$pic)
            ->with('jenis',$jenis);
        }
    }

    public function form_rincian_simpan(Request $request)
    {
        // return $request->all();
        $id=$request->id;
        $idtindaklanjut=0;
        if(isset($request->tindak_lanjut))
        {
            $user_pic=PICUnit::where('id_user',Auth::user()->id)->first();
            $rekom=DataRekomendasi::where('id',$request->idrekomendasi)->with('dtemuan')->first();

            $tindaklanjut=new TindakLanjutTemuan;
            $tindaklanjut->lhp_id = 
            $tindaklanjut->temuan_id = $request->idtemuan;
            $tindaklanjut->rekomendasi_id = $request->idrekomendasi;
            $tindaklanjut->rangkuman = $request->tindak_lanjut;
            $tindaklanjut->rincian = $request->jenis;

            if($user_pic)
            {
                if($rekom->pic_1_temuan_id==$user_pic->id)
                    $tindaklanjut->pic_1_id = $user_pic->id;
                
                if($rekom->pic_2_temuan_id==$user_pic->id)
                    $tindaklanjut->pic_2_id = $user_pic->id;
            }
            $tindaklanjut->save();
            
            $idtindaklanjut=$tindaklanjut->id;
        }

        if($id==-1)
        {
            if($request->jenis=='sewa')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianSewa;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->mitra=$request->mitra;
                $simpan->no_pks=$request->no_pks;
                $simpan->tgl_pks=$request->tgl_pks;
                $simpan->nilai_pekerjaan=str_replace(array(',','.'),'',$request->nilai_perjanjian);
                $simpan->masa_berlaku=$request->masa_berlaku;
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='sewa';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='uangmuka')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianUangMuka;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->no_invoice = $request->no_invoice;
                $simpan->tgl_pum = $request->tgl_pum;
                $simpan->jumlah_pum = str_replace(array(',','.'),'',$request->jumlah_um);
                $simpan->keterangan = $request->keterangan;
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='uangmuka';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='listrik')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianListrik;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->lokasi = $request->lokasi;
                $simpan->tgl_invoice = $request->tgl_invoice;
                $simpan->tagihan = str_replace(array(',','.'),'',$request->tagihan);
                $simpan->keterangan = $request->keterangan;
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='listrik';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='piutang')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianPiutang;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->pelanggan = $request->pelanggan;
                $simpan->tagihan = str_replace(array(',','.'),'',$request->tagihan);
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='piutang';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='piutangkaryawan')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianPiutangKaryawan;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->karyawan = $request->karyawan;
                $simpan->pinjaman = str_replace(array(',','.'),'',$request->pinjaman);
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='piutangkaryawan';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='hutangtitipan')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianHutangTitipan;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->tanggal = $request->tanggal;
                $simpan->saldo_hutang = str_replace(array(',','.'),'',$request->saldo_hutang);
                $simpan->sisa_setor = str_replace(array(',','.'),'',$request->sisa_setor);
                $save=$simpan->save();
                return $request->all();
            }
            elseif($request->jenis=='penutupanrekening')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianPenutupanRekening;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->nama_bank = $request->nama_bank;
                $simpan->nomor_rekening = $request->nomor_rekening;
                $simpan->nama_rekening = $request->nama_rekening;
                $simpan->jenis_rekening = $request->jenis_rekening;
                $simpan->saldo_akhir = str_replace(array(',','.'),'',$request->saldo_akhir);
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='penutupanrekening';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='umum')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianUmum;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->keterangan = $request->keterangan;
                $simpan->jumlah_rekomendasi = $request->jumlah_rekomendasi;
                $save=$simpan->save();

                // $rekom=DataRekomendasi::find($request->idrekomendasi);
                // $rekom->rincian='umum';
                // $rekom->save();

                return $request->all();
            }
            elseif($request->jenis=='kontribusi')
            {
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianKontribusi;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->keterangan = $request->keterangan;
                $simpan->tahun = $request->tahun;
                // $simpan->nilai_penerimaan = str_replace(array(',','.'),'',$request->jumlah_rekomendasi);
                $simpan->nilai_penerimaan = $request->jumlah_rekomendasi;
                $save=$simpan->save();

                return $request->all();
            }elseif($request->jenis=='nonsetoranperjanjiankerjasama'){
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianNonSetoranPerpanjanganPerjanjianKerjasama;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->no_pks=$request->no_pks;
                $simpan->tgl_pks=$request->tgl_pks;
                $simpan->keterangan = $request->keterangan;
                $simpan->masa_berlaku=$request->masa_berlaku;
                $save=$simpan->save();
                
                return $request->all();
            }elseif($request->jenis=='nonsetoran'){
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianNonSetoran;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->keterangan=$request->keterangan;
                $simpan->nilai_rekomendasi = $request->jumlah_rekomendasi;
                $save=$simpan->save();
                
                return $request->all();
            }elseif($request->jenis=='nonsetoranumum'){
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianNonSetoranUmum;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->keterangan=$request->keterangan;
                // $simpan->nilai_rekomendasi = $request->jumlah_rekomendasi;
                $save=$simpan->save();
                
                return $request->all();
            }elseif($request->jenis=='nonsetoranpertanggungjawabanuangmuka'){
                list($idunitkerja,$namaunitkerja)=explode('__',$request->unit_kerja);
                $simpan=new RincianNonSetoranPertanggungjawabanUangMuka;
                $simpan->id_tindak_lanjut=$idtindaklanjut;
                $simpan->id_temuan=$request->idtemuan;
                $simpan->id_rekomendasi=$request->idform;
                $simpan->unit_kerja_id=$idunitkerja;
                $simpan->unit_kerja=$namaunitkerja;
                $simpan->no_invoice = $request->no_invoice;
                $simpan->tgl_um = $request->tgl_um;
                $simpan->jumlah_um = str_replace(array(',','.'),'',$request->jumlah_um);
                $simpan->keterangan = $request->keterangan;
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
        elseif($jenis=='piutangkaryawan')
            $data=RincianPiutangKaryawan::find($id);
        elseif($jenis=='hutangtitipan')
            $data=RincianHutangTitipan::find($id);
        elseif($jenis=='penutupanrekening')
            $data=RincianPenutupanRekening::find($id);
        elseif($jenis=='umum')
            $data=RincianUmum::find($id);
        else if($jenis=='kontribusi')
            $data=RincianKontribusi::find($id);
        else if($jenis=='nonsetoranperjanjiankerjasama')
            $data=RincianNonSetoranPerpanjanganPerjanjianKerjasama::find($id);
        elseif($jenis=='nonsetoran')
            $data=RincianNonSetoran::find($id);
        elseif($jenis=='nonsetoranumum')
            $data=RincianNonSetoranUmum::find($id);
        elseif($jenis=='nonsetoranpertanggungjawabanuangmuka')
            $data=RincianNonSetoranPertanggungjawabanUangMuka::find($id);

        $dt['jenis']=$data->jenis;
        $dt['idtemuan']=$data->id_temuan;
        $dt['idrekomendasi']=$data->id_rekomendasi;
        $data->delete();
        return $dt;
    }

    public function update_rincian(Request $request)
    {
        // return $request->all();
        $rincian=$request->rincian_tl;
        $idlhp=$request->idlhp;
        $idrekom=$request->idrekom;

        $rekom=DataRekomendasi::find($idrekom);
        $rekom->rincian=$rincian;
        $c=$rekom->save();

        if($c)
        {
            $data['status']=1;
            $data['idtemuan']=$rekom->id_temuan;
            $data['statusrekom']=$rekom->status_rekomendasi_id;
        }
        else
        {
            $data['status']=0;
        }
        return $data;
    }
}
