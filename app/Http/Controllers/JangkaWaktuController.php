<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JangkaWaktu;
use Validator;
class JangkaWaktuController extends Controller
{
    public function index()
    {
        $jangkawaktu=JangkaWaktu::orderBy('jumlah_hari')->get();
        return view('backend.pages.jangka-waktu.index')
            ->with('jangkawaktu',$jangkawaktu);
    }

    public function store(Request $request)
    {
       
        $rules = [
            'mulai' => 'required',
            'akhir' => 'required',
        ];

        $customMessages = [
            'mulai.required' => 'Waktu Mulai Harus Diisi',
            'akhir.required' => 'Waktu Mulai Harus Diisi',
        ];

        Validator::make($request->all(),$rules,$customMessages)->validate();

        $mulai=$request->mulai;
        $akhir=$request->akhir;
        $waktu=$request->waktu;
        $jenis=$request->jenis;
        if($akhir==$mulai)
            $wkt=$mulai.' '.$jenis;
        else
            $wkt=$mulai.$waktu.$akhir.' '.$jenis;

        $jlh=hitunghari($mulai,$akhir,$jenis);

        $insert = new JangkaWaktu;
        $insert->jangka_waktu=$wkt;
        $insert->jumlah_hari=$jlh;
        $insert->save();

        return redirect()->route('jangka-waktu.index')
            ->with('success', 'Anda telah memasukkan data baru.');
    }

    public function edit($id)
    {
        $jangka=JangkaWaktu::find($id);
        list($bln,$jns)=explode(' ',$jangka->jangka_waktu);
        if(strpos($bln,'-')!==false)
        {
            list($mulai,$akhir)=explode('-',$bln);
        }
        else
        {
            $mulai=$akhir=$bln;
        }
        $data['id']=$jangka->id;
        $data['mulai']=$mulai;
        $data['akhir']=$akhir;
        $data['jenis']=$jns;
        return $data;
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'mulai' => 'required',
            'akhir' => 'required',
        ];

        $customMessages = [
            'mulai.required' => 'Waktu Mulai Harus Diisi',
            'akhir.required' => 'Waktu Mulai Harus Diisi',
        ];


        Validator::make($request->all(),$rules,$customMessages)->validate();

        $mulai=$request->mulai;
        $akhir=$request->akhir;
        $waktu=$request->waktu;
        $jenis=$request->jenis;
        $jlh=hitunghari($mulai,$akhir,$jenis);
        if($akhir==$mulai)
            $wkt=$mulai.' '.$jenis;
        else
            $wkt=$mulai.$waktu.$akhir.' '.$jenis;

        $update = JangkaWaktu::find($id);
        $update->jangka_waktu=$wkt;
        $update->jumlah_hari=$jlh;
        $update->save();

        return redirect()->route('jangka-waktu.index')
            ->with('success', 'Anda telah memperbaharui data.');
    }

    public function destroy($id)
    {
        JangkaWaktu::destroy($id);
        return redirect()->route('jangka-waktu.index')
            ->with('success', 'Anda telah menghapus data.');
    }
}
