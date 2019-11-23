<?php

namespace App\Http\Middleware;

use Closure;

class UserFlag
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
        if(auth()->user()->flag == 1)
        {
            return $next($request);
        }
        return redirect('force-logout')->with('error','Anda Tidak Mendapatkan Akses Login');
    }
}
