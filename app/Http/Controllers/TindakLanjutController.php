<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;
use App\Models\DataRekomendasi;
class TindakLanjutController extends Controller
{
    public function simpan(Request $request,$idrekom)
    {
        // dd($request);
        $rekom=DataRekomendasi::where('id',$idrekom)->with('dtemuan')->first();
        $idlhp=0;
        if(isset($rekom->dtemuan->id_lhp))
            $idlhp=$rekom->dtemuan->id_lhp;

        $idtemuan=$rekom->id_temuan;
        $tindaklanjut=$request->tindaklanjut;
        $nilai_tindaklanjut =str_replace('.','',$request->nilai_tindaklanjut);

        if(isset($request->idtindaklanjut))
        {
            $idtindaklanjut=$request->idtindaklanjut;
            $tindak=TindakLanjutTemuan::find($idtindaklanjut);
        }
        else
            $tindak=new TindakLanjutTemuan;
        
        $tindak->lhp_id = $idlhp;
        $tindak->temuan_id = $idtemuan;
        $tindak->rekomendasi_id = $idrekom;
        $tindak->pic_1_id = $rekom->pic_1_temuan_id;
        $tindak->pic_2_id = $rekom->pic_2_temuan_id;
        $tindak->tindak_lanjut = $tindaklanjut;
        $tindak->nilai = $nilai_tindaklanjut;
        $c=$tindak->save();

        $tindak_id=$tindak->id;

        if($request->hasFile('file')){
            $file = $request->file('file');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension(); 
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // $fileNameToStore = rand() . '.' . $file->getClientOriginalExtension(); 
            $path = $request->file('file')->storeAs('public/dokumen',$fileNameToStore);

            $dokumen=new DokumenTindakLanjut;
            $dokumen->id_tindak_lanjut_temuan=$tindak_id;
            $dokumen->nama_dokumen=$fileNameToStore;
            $dokumen->path=$path;
            $dokumen->save();
        }
        if($c)
            echo $idtemuan;
        else
            echo 0;
    }
    
    public function edit($id)
    {
        $edit=TindakLanjutTemuan::selectRaw('*,tindak_lanjut_temuan.id as tl_id')->where('id',$id)->with('pic1')->with('pic2')->with('dokumen_tindak_lanjut')->first();
        return $edit;
    }

    public function destroy($id)
    {
        $d=TindakLanjutTemuan::destroy($id);
        return $d;
    }
}
