<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PICUnit;
use App\Models\Bidang;
use App\Models\MasterFakultas;
use App\Models\LevelPIC;
use Validator;
use Illuminate\Validation\Rule;
class PICUnitController extends Controller
{
    public function index()
    {
        $picunit=PICUnit::selectRaw('*,pic_unit.id as p_id')->with('bid')->with('fak')->with('levelpic')->orderBy('nama_pic')->get();
        $levelpic=LevelPIC::where('flag',1)->orderBy('nama_level')->get();
        $bidang=Bidang::where('flag',1)->orderBy('nama_bidang')->get();
        $fakultas=MasterFakultas::where('flag',1)->orderBy('nama_fakultas')->get();
        // return $picunit
        return view('backend.pages.pic-unit.index')
            ->with('levelpic',$levelpic)
            ->with('bidang',$bidang)
            ->with('fakultas',$fakultas)
            ->with('picunit',$picunit);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'level_pic' => 'required',
            'nama_pic' => 'unique:pic_unit,nama_pic|required',
        ];

        $customMessages = [
            'level_pic.required' => 'Level PIC Belum Dipilih',
            'nama_pic.required' => 'Nama PIC Belum Di Isi',
            'nama_pic.unique' => 'Nama PIC Sudah Pernah Digunakan, Silahkan Gunakan Nama PIC yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new PICUnit;
        $insert->level_pic=$request->level_pic;
        $insert->bidang=$request->bidang;
        $insert->fakultas=$request->fakultas;
        $insert->pic_1_flag=$request->pic_1_flag;
        $insert->pic_2_flag=$request->pic_2_flag;
        $insert->nama_pic=$request->nama_pic;
        $insert->save();

        return redirect()->route('pic-unit.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return PICUnit::find($id);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'level_pic' => 'required',
            'nama_pic' => 'unique:pic_unit,nama_pic|required',
        ];

        $customMessages = [
            'level_pic.required' => 'Level PIC Belum Dipilih',
            'nama_pic.required' => 'Nama PIC Belum Di Isi',
            'nama_pic.unique' => 'Nama PIC Sudah Pernah Digunakan, Silahkan Gunakan Nama PIC yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = PICUnit::find($id);
        $update->level_pic=$request->level_pic;
        $update->bidang=$request->bidang;
        $update->fakultas=$request->fakultas;
        $update->pic_1_flag=$request->pic_1_flag;
        $update->pic_2_flag=$request->pic_2_flag;
        $update->nama_pic=$request->nama_pic;
        $update->save();

        return redirect()->route('pic-unit.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        PICUnit::destroy($id);
        return redirect()->route('pic-unit.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
