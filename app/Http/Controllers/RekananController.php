<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarRekanan;
use Validator;
class RekananController extends Controller
{
    public function index()
    {
        $rekanan=DaftarRekanan::orderBy('nama')->get();
       
        return view('backend.pages.rekanan.index')
            ->with('rekanan',$rekanan);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'nama' => 'unique:daftar_rekanan,nama|required',
        ];

        $customMessages = [
            'nama.required' => 'Nama DaftarRekanan Belum Di Isi',
            'nama.unique' => 'Nama DaftarRekanan Sudah Pernah Digunakan, Silahkan Gunakan Nama PIC yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new DaftarRekanan;
        $insert->nama=$request->nama;
        $insert->alamat=$request->alamat;
        $insert->no_telp=$request->telepon;
        $insert->pekerjaan=$request->pekerjaan;
        $insert->save();

        return redirect()->route('rekanan.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return DaftarRekanan::find($id);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'nama' => 'unique:daftar_rekanan,nama|required',
        ];

        $customMessages = [
            'nama.required' => 'Nama DaftarRekanan Belum Di Isi',
            'nama.unique' => 'Nama DaftarRekanan Sudah Pernah Digunakan, Silahkan Gunakan Nama PIC yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = DaftarRekanan::find($id);
        $update->nama=$request->nama;
        $update->no_telp=$request->telepon;
        $update->alamat=$request->alamat;
        $update->pekerjaan=$request->pekerjaan;
        $update->save();

        return redirect()->route('rekanan.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        DaftarRekanan::destroy($id);
        return redirect()->route('rekanan.index')
            ->with('success', 'Anda telah menghapus data.');
    }

    public function data_rekanan()
    {
        $rekanan=DaftarRekanan::select('nama')->get();
        $rekan=array();
        foreach($rekanan as $item)
        {
            if($item->nama!=null)
                $rekan[]=$item->nama;
        }
        return $rekan;
    }
}
