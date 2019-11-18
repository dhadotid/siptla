<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSebab;
use Validator;
class MasterSebabController extends Controller
{
    public function index()
    {
        $Sebab=MasterSebab::orderBy('code')->get();
        return view('backend.pages.master-data.sebab')->with('sebab',$Sebab);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'code' => 'required',
            'sebab' => 'required'
        ])->validate();

        $insert = new MasterSebab;
        $insert->code = $request->code;
        $insert->sebab = $request->sebab;
        $insert->desc = $request->desc;
        $insert->flag = $request->flag;
        $insert->save();

        return redirect()->route('data-penyebab.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterSebab::find($id);
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'code' => 'required',
            'sebab' => 'required'
        ])->validate();

        $update = MasterSebab::find($id);
        $update->code = $request->code;
        $update->sebab = $request->sebab;
        $update->desc = $request->desc;
        $update->flag = $request->flag;
        $update->save();

        return redirect()->route('data-penyebab.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        MasterSebab::destroy($id);
        return redirect()->route('data-penyebab.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
