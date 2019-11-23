<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        // return $next($request);
        if(auth()->user()->level == '0')
        {
            return $next($request);
        }
        return redirect('login')->with('error','Anda Tidak Mendapatkan Akses');
    }
}
