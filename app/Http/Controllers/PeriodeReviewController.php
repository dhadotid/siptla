<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeReview;
use Validator;
class PeriodeReviewController extends Controller
{
    public function index()
    {
        $periodereview=PeriodeReview::all();
        return view('backend.pages.periode-review.index')
            ->with('periodereview',$periodereview);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];

        $customMessages = [
            'tanggal_mulai.required' => 'Tanggal Mulai Belum Dipilih',
            'tanggal_selesai.required' => 'Tanggal Mulai Belum Dipilih',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();
        $insert = new PeriodeReview;
        $insert->tanggal_mulai=$request->tanggal_mulai;
        $insert->tanggal_selesai=$request->tanggal_selesai;
        $insert->status=$request->status;
        $insert->keterangan=$request->keterangan;
        $insert->save();

        return redirect()->route('periode-review.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return PeriodeReview::find($id);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];

        $customMessages = [
            'tanggal_mulai.required' => 'Tanggal Mulai Belum Dipilih',
            'tanggal_selesai.required' => 'Tanggal Mulai Belum Dipilih',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $update = PeriodeReview::find($id);
        $update->tanggal_mulai=$request->tanggal_mulai;
        $update->tanggal_selesai=$request->tanggal_selesai;
        $update->status=$request->status;
        $update->keterangan=$request->keterangan;
        $update->save();

        return redirect()->route('periode-review.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        PeriodeReview::destroy($id);
        return redirect()->route('periode-review.index')
            ->with('success', 'Anda telah menghapus data.');
    }

    public function ubah_status($id,$st)
    {
        $update = PeriodeReview::find($id);
        $update->status=$st;
        $update->save();
    }
}
