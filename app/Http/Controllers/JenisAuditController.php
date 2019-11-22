<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisAudit;
use Validator;
class JenisAuditController extends Controller
{
    public function index()
    {
        $jenisaudit=JenisAudit::orderBy('jenis_audit')->get();
        return view('backend.pages.jenis-audit.index')
            ->with('jenisaudit',$jenisaudit);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'jenis_audit' => 'required|unique:jenis_audit,jenis_audit',
        ];

        $customMessages = [
            'jenis_audit.required' => 'Jenis Audit Belum Diisi',
            'jenis_audit.unique' => 'Jenis Audit <b><u>'.$request->jenis_audit.'</u></b> Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new JenisAudit;
        $insert->jenis_audit=$request->jenis_audit;
        $insert->flag=$request->flag;
        $insert->save();

        return redirect()->route('jenis-audit.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return JenisAudit::find($id);
    }

    public function update(Request $request,$id)
    {
         $rules = [
            'jenis_audit' => 'required|unique:jenis_audit,jenis_audit',
        ];

        $customMessages = [
            'jenis_audit.required' => 'Jenis Audit Belum Diisi',
            'jenis_audit.unique' => 'Jenis Audit <b><u>'.$request->jenis_audit.'</u></b> Sudah Pernah Digunakan, Silahkan Gunakan Nama yang lain'
        ];


        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = JenisAudit::find($id);
        $update->jenis_audit=$request->jenis_audit;
        $update->flag=$request->flag;
        $update->save();

        return redirect()->route('jenis-audit.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        JenisAudit::destroy($id);
        return redirect()->route('jenis-audit.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
