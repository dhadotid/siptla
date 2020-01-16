<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PejabatTandaTangan;
use Validator;
class PejabatTandatanganController extends Controller
{
    public function index()
    {
        $levelpic=PejabatTandaTangan::orderBy('nama')->get();
        return view('backend.pages.pejabat.index')
            ->with('levelpic',$levelpic);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'nip' => 'required|unique:pejabat_tandatangan,nip',
            'nama' => 'required',
            'jabatan' => 'required',
        ];

        $customMessages = [
            'nip.required' => 'NIP Belum Diisi',
            'nip.unique' => 'NIP Sudah Pernah Digunakan, Silahkan Gunakan NIP yang lain',
            'nama.required' => 'Nama Belum Diisi',
            'nama.unique' => 'Nama Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain',
            'jabatan.required' => 'Jabatan Belum Diisi',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new PejabatTandaTangan;
        $insert->nip=$request->nip;
        $insert->nama=$request->nama;
        $insert->jabatan=$request->jabatan;
        $insert->save();

        return redirect()->route('pejabat-penandatangan.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return PejabatTandaTangan::find($id);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'nip' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
        ];

        $customMessages = [
            'nip.required' => 'NIP Belum Diisi',
            'nama.required' => 'Nama Belum Diisi',
            'nama.unique' => 'Nama Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain',
            'jabatan.required' => 'Jabatan Belum Diisi',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = PejabatTandaTangan::find($id);
        $update->nip=$request->nip;
        $update->nama=$request->nama;
        $update->jabatan=$request->jabatan;
        $update->save();

        return redirect()->route('pejabat-penandatangan.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        PejabatTandaTangan::destroy($id);
        return redirect()->route('pejabat-penandatangan.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
