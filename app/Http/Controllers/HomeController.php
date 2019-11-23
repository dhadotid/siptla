<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarTemuan;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('dashboard');
        if(Auth::user()->flag==0)
            return redirect('force-logout')->with('error','Anda Tidak Mendapatkan Akses Login');

        return redirect('dashboard');
    }
}
