<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelPIC;
use Validator;
class LevelPicController extends Controller
{
    public function index()
    {
        $levelpic=LevelPIC::orderBy('nama_level')->get();
        return view('backend.pages.level-pic.index')
            ->with('levelpic',$levelpic);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'nama_level' => 'required|unique:level_pic,nama_level',
        ];

        $customMessages = [
            'nama_level.required' => 'Level PIC Belum Dipilih',
            'nama_level.unique' => 'Nama Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new LevelPIC;
        $insert->nama_level=$request->nama_level;
        $insert->flag=$request->flag;
        $insert->save();

        return redirect()->route('level-pic.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return LevelPIC::find($id);
    }

    public function update(Request $request,$id)
    {
         $rules = [
            'nama_level' => 'required|unique:level_pic,nama_level,'.$id,
        ];

        $customMessages = [
            'nama_level.required' => 'Level PIC Belum Dipilih',
            'nama_level.unique' => 'Nama Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];


        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = LevelPIC::find($id);
        $update->nama_level=$request->nama_level;
        $update->flag=$request->flag;
        $update->save();

        return redirect()->route('level-pic.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        LevelPIC::destroy($id);
        return redirect()->route('level-pic.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
