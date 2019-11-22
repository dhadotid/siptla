<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusRekomendasi;
use Validator;
class StatusRekomendasiController extends Controller
{
    public function index()
    {
        $levelpic=StatusRekomendasi::orderBy('rekomendasi')->get();
        return view('backend.pages.status-rekomendasi.index')
            ->with('levelpic',$levelpic);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'rekomendasi' => 'required|unique:status_rekomendasi,rekomendasi',
        ];

        $customMessages = [
            'rekomendasi.required' => 'Status Rekomendasi Harus Diisi',
            'rekomendasi.unique' => 'Status Rekomendasi Sudah Pernah Digunakan, Silahkan Gunakan Status yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new StatusRekomendasi;
        $insert->rekomendasi=$request->rekomendasi;
        $insert->save();

        return redirect()->route('status-rekomendasi.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return StatusRekomendasi::find($id);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'rekomendasi' => 'required|unique:status_rekomendasi,rekomendasi',
        ];

        $customMessages = [
            'rekomendasi.required' => 'Status Rekomendasi Harus Diisi',
            'rekomendasi.unique' => 'Status Rekomendasi Sudah Pernah Digunakan, Silahkan Gunakan Status yang lain'
        ];


        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = StatusRekomendasi::find($id);
        $update->rekomendasi=$request->rekomendasi;
        $update->save();

        return redirect()->route('status-rekomendasi.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        StatusRekomendasi::destroy($id);
        return redirect()->route('status-rekomendasi.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
