<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);



        if(Auth::check() && Auth::user()->flag != 1){

            Auth::logout();
            $request->session()->flash('error', 'Akun Anda Sudah Tidak Aktif, Silahkan hubungi Admin SPI');
            return redirect('/login')->with('error', 'Akun Anda Sudah Tidak Aktif, Silahkan hubungi Admin SPI');

        }

        return $response;
    }
}
