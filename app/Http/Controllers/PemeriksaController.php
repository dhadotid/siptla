<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksa;
use Validator;
class PemeriksaController extends Controller
{
    public function index()
    {
        $levelpic=Pemeriksa::orderBy('pemeriksa')->get();
        return view('backend.pages.pemeriksa.index')
            ->with('levelpic',$levelpic);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'code' => 'required|unique:pemeriksa,code',
            'pemeriksa' => 'required|unique:pemeriksa,pemeriksa',
        ];

        $customMessages = [
            'code.required' => 'Code Pemeriksa Belum Diisi',
            'code.unique' => 'Code Sudah Pernah Digunakan, Silahkan Gunakan Code yang lain',
            'pemeriksa.required' => 'Pemeriksa Belum Diisi',
            'pemeriksa.unique' => 'Nama Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new Pemeriksa;
        $insert->code=$request->code;
        $insert->pemeriksa=$request->pemeriksa;
        $insert->save();

        return redirect()->route('pemeriksa.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return Pemeriksa::find($id);
    }

    public function update(Request $request,$id)
    {
         $rules = [
            'code' => 'required',
            'pemeriksa' => 'required',
        ];

        $customMessages = [
            'code.required' => 'Code Pemeriksa Belum Diisi',
            'pemeriksa.required' => 'Pemeriksa Belum Diisi',
        ];


        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = Pemeriksa::find($id);
        $update->code=$request->code;
        $update->pemeriksa=$request->pemeriksa;
        $update->save();

        return redirect()->route('pemeriksa.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        Pemeriksa::destroy($id);
        return redirect()->route('pemeriksa.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
