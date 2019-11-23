<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailTemuan;

use Auth;
use App\Models\PICUnit;
use App\Models\JenisAudit;
use App\Models\Pemeriksa;
use App\Models\StatusRekomendasi;
use App\Models\MasterTemuan;
use App\Models\DaftarTemuan;
class DashboardController extends Controller
{
    public function index()
    {
        // echo Auth::user()->level;
        if(Auth::user()->level=='0')
        {
            $jenistemuan=MasterTemuan::get()->count();
            $pemeriksa=Pemeriksa::get()->count();
            $status=StatusRekomendasi::get()->count();
            $picunit=PICUnit::with('levelpic')->with('fak')->with('bid')->orderByRaw('RAND()')->limit(10)->get();
            $jenisaudit=JenisAudit::get()->count();
            return view('backend.pages.dashboard.admin')
                    ->with('jenistemuan',$jenistemuan)
                    ->with('pemeriksa',$pemeriksa)
                    ->with('status',$status)
                    ->with('picunit',$picunit)
                    ->with('jenisaudit',$jenisaudit);
        }
        elseif(Auth::user()->level=='auditor-junior')
        {
            $lhp=DaftarTemuan::with('dpemeriksa')->with('djenisaudit')->get();
            $datalhp=array();
            foreach($lhp as $k=>$v)
            {
                $datalhp[str_slug($v->status_lhp)][]=$v;
            }
            $status=StatusRekomendasi::get()->count();
            return view('backend.pages.dashboard.auditor-junior')
                    ->with('lhp',$lhp)
                    ->with('status',$status)
                    ->with('datalhp',$datalhp);
        }
    }
}
