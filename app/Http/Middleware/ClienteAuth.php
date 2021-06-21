<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClienteAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->path()=="login" && $request->session()->has('usuario')) {
            $mail = Session::get('usuario')['email'];
            if($mail == "administradores@tutienda.com") {
                return redirect('/administradores');
            } else {
                return redirect('/principal');
            }
        }
        if($request->path()=="/" && $request->session()->has('usuario')) {
            $mail = Session::get('usuario')['email'];
            if($mail == "administradores@tutienda.com") {
                return redirect('/administradores');
            } else {
                return redirect('/principal');
            }
    }
        return $next($request);
    }
}
