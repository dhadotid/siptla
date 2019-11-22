<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTemuan;
use Validator;
class MasterTemuanController extends Controller
{
    public function index()
    {
        $temuan=MasterTemuan::orderBy('temuan')->get();
        return view('backend.pages.master-data.temuan')->with('temuan',$temuan);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'temuan' => 'required'
        ])->validate();

        $insert = new MasterTemuan;
        $insert->temuan = $request->temuan;
        $insert->desc = $request->desc;
        $insert->flag = $request->flag;
        $insert->save();

        return redirect()->route('jenis-temuan.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterTemuan::find($id);
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'temuan' => 'required'
        ])->validate();

        $update = MasterTemuan::find($id);
        $update->temuan = $request->temuan;
        $update->desc = $request->desc;
        $update->flag = $request->flag;
        $update->save();

        return redirect()->route('jenis-temuan.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        MasterTemuan::destroy($id);
        return redirect()->route('jenis-temuan.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
