<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelResiko;
use Validator;
class LevelResikoController extends Controller
{
    public function index()
    {
        $levelpic=LevelResiko::orderBy('level_resiko')->get();
        return view('backend.pages.level-resiko.index')
            ->with('levelpic',$levelpic);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'level_resiko' => 'required|unique:level_resiko,level_resiko',
        ];

        $customMessages = [
            'level_resiko.required' => 'Level Resiko Belum Diisi',
            'level_resiko.unique' => 'Level Resiko <U><b>'.$request->level_resiko.'</b></U> Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new LevelResiko;
        $insert->level_resiko=$request->level_resiko;
        $insert->save();

        return redirect()->route('level-resiko.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return LevelResiko::find($id);
    }

    public function update(Request $request,$id)
    {
         $rules = [
            'level_resiko' => 'required|unique:level_resiko,level_resiko',
        ];

        $customMessages = [
            'level_resiko.required' => 'Level Resiko Belum Diisi',
            'level_resiko.unique' => 'Level Resiko <U><b>'.$request->level_resiko.'</b></U> Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = LevelResiko::find($id);
        $update->level_resiko=$request->level_resiko;
        $update->save();

        return redirect()->route('level-resiko.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        LevelResiko::destroy($id);
        return redirect()->route('level-resiko.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
