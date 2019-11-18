<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailTemuan;
use App\Models\TindakLanjutTemuan;
use App\Models\DokumenTindakLanjut;

use Validator;
use DB;
use Auth;

class TindakLanjutTemuanController extends Controller
{
    public function index($id)
    {
        $temuan = DetailTemuan::with(['pengawasan', 'daftar'])->findOrFail($id);

        return view('backend.pages.tanggapan.index')->with('temuan', $temuan);
    }

    public function store(Request $request)
    {
        // dd($request);

        Validator::make($request->all(), [
            'detail_id' => 'required',
            'tindak_lanjut' => 'required',
            'nama_dokumen' => 'required',
            'path' => 'required',
        ])->validate();

        DB::transaction(function() use($request){
            $tindak_lanjut = TindakLanjutTemuan::create($request->all()); 

            $jumlah_file = collect($request->path)->count();
            $file = $request->file('path');

            for ($i=0; $i < $jumlah_file; $i++) { 
                $filename = time()."_tindaklanjut_"."authorid".Auth::user()->id."_".strtolower($file[$i]->getClientOriginalName());
                $file[$i]->storeAs('tindak-lanjut', $filename);
                
                $insert = new DokumenTindakLanjut;
                $insert->id_tindak_lanjut_temuan = $tindak_lanjut->id;
                $insert->nama_dokumen = $request->nama_dokumen[$i];
                $insert->path = $filename;
                $insert->save();
            }

            $detail = DetailTemuan::find($request->detail_id);
            $detail->flag = 3;
            $detail->save();
        });
        
        $detail=DetailTemuan::find($request->detail_id);
        // return redirect()->route('list-temuan.index')
        return redirect('temuan/'.$detail->daftar_id)
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        $temuan = DetailTemuan::with('pengawasan')->findOrFail($id);

        $tindaklanjut = TindakLanjutTemuan::where('detail_id', $id)->with('dokumen_tindak_lanjut')->first();

        return view('backend.pages.tanggapan.edit')
            ->with('tindaklanjut', $tindaklanjut)
            ->with('temuan', $temuan);
    }

    public function download($filename)
    {
        if (!auth()->check()) {
            return abort(404);
        }
        return response()->download(storage_path('app/tindak-lanjut/'.$filename));
    }

    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'detail_id' => 'required',
            'tindak_lanjut' => 'required',
        ])->validate();

        DB::transaction(function() use($request, $id){
            $tindak_lanjut = TindakLanjutTemuan::find($id)->update($request->all()); 

            $jumlah_file = collect($request->path)->count();
            $file = $request->file('path');

            for ($i=0; $i < $jumlah_file; $i++) { 
                $filename = time()."_tindaklanjut_"."authorid".Auth::user()->id."_".strtolower($file[$i]->getClientOriginalName());
                $file[$i]->storeAs('tindak-lanjut', $filename);
                
                $insert = new DokumenTindakLanjut;
                $insert->id_tindak_lanjut_temuan = $id;
                $insert->nama_dokumen = $request->nama_dokumen[$i];
                $insert->path = $filename;
                $insert->save();
            }
        });
        $tindak = TindakLanjutTemuan::find($id); 
        $detail=DetailTemuan::find($tindak->detail_id);
        // return redirect()->route('list-temuan.index')
        return redirect('temuan/'.$detail->daftar_id)
            ->with('success', 'Anda telah mengubah data tindak lanjut temuan.');
    }

    public function show($id)
    {
        $tindaklanjut = TindakLanjutTemuan::findOrFail($id);
        $temuan = DetailTemuan::with('pengawasan')->with('daftar')->findOrFail($tindaklanjut->detail_id);

        return view('backend.pages.tanggapan.show')
            ->with('temuan', $temuan)
            ->with('tindaklanjut', $tindaklanjut);        
    }

    public function selesai($id)
    {
        $temuan = DetailTemuan::with('tindak_lanjut_temuan')->find($id);
        $temuan->flag = 4;
        $temuan->save();

        return redirect()->route('tindak-lanjut.show', $temuan->tindak_lanjut_temuan->id);
    }
}
