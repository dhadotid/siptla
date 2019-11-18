<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterBidangPengawasan;
use Validator;
class MasterBidangPengawasanController extends Controller
{
    public function index()
    {
        $bidang=MasterBidangPengawasan::orderBy('code')->get();
        return view('backend.pages.master-data.bidang-pengawasan')->with('bidang',$bidang);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            
            'code' => 'required',
            'bidang' => 'required'
        ])->validate();

        $insert = new MasterBidangPengawasan;
        $insert->code = $request->code;
        $insert->bidang = $request->bidang;
        $insert->flag = $request->flag;
        $insert->save();

        return redirect()->route('bidang-pengawasan.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        return MasterBidangPengawasan::find($id);
    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            
            'code' => 'required',
            'bidang' => 'required'
        ])->validate();

        $update = MasterBidangPengawasan::find($id);
        $update->code = $request->code;
        $update->bidang = $request->bidang;
        $update->flag = $request->flag;
        $update->save();

        return redirect()->route('bidang-pengawasan.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        MasterBidangPengawasan::destroy($id);
        return redirect()->route('bidang-pengawasan.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
