<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDinas;
use Validator;
class MasterDinasController extends Controller
{
    public function index()
    {
        $opds=MasterDinas::orderBy('nama_dinas')->get();
        return view('backend.pages.opd.index')->with('opds',$opds);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'nama_dinas' => 'required',
            'singkatan' => 'required'
        ])->validate();

        $insert = new MasterDinas;
        $insert->nama_dinas = $request->nama_dinas;
        $insert->singkatan = $request->singkatan;
        $insert->alamat = $request->alamat;
        $insert->flag = $request->flag;
        $insert->nama_slug = str_slug($request->nama_dinas);
        $insert->save();

        return redirect()->route('data-opd.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterDinas::find($id);
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'nama_dinas' => 'required',
            'singkatan' => 'required'
        ])->validate();

        $update = MasterDinas::find($id);
        $update->nama_dinas = $request->nama_dinas;
        $update->singkatan = $request->singkatan;
        $update->alamat = $request->alamat;
        $update->flag = $request->flag;
        $update->nama_slug = str_slug($request->nama_dinas);
        $update->save();

        return redirect()->route('data-opd.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        MasterDinas::destroy($id);
        return redirect()->route('data-opd.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
