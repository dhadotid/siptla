<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterRekomendasi;
use Validator;
class MasterRekomendasiController extends Controller
{
    public function index()
    {
        $rekomendasi=MasterRekomendasi::orderBy('code')->get();
        return view('backend.pages.master-data.rekomendasi')->with('rekomendasi',$rekomendasi);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'code' => 'required',
            'rekomendasi' => 'required'
        ])->validate();

        $insert = new MasterRekomendasi;
        $insert->code = $request->code;
        $insert->rekomendasi = $request->rekomendasi;
        $insert->desc = $request->desc;
        $insert->flag = $request->flag;
        $insert->save();

        return redirect()->route('data-rekomendasi.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterRekomendasi::find($id);
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'code' => 'required',
            'rekomendasi' => 'required'
        ])->validate();

        $update = MasterRekomendasi::find($id);
        $update->code = $request->code;
        $update->rekomendasi = $request->rekomendasi;
        $update->desc = $request->desc;
        $update->flag = $request->flag;
        $update->save();

        return redirect()->route('data-rekomendasi.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        MasterRekomendasi::destroy($id);
        return redirect()->route('data-rekomendasi.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
