<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\TindakLanjutTemuan;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function tindaklanjut()
    {
        $tindaklanjut=TindakLanjutTemuan::with('pic1')->with('pic2')->with('dokumen_tindak_lanjut')->get();
        $data=array();
        foreach($tindaklanjut as $k=>$v)
        {
            $data[$v->rekomendasi_id][]=$v;
        }
        return $data;
    }
}
