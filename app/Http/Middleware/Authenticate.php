<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('login');
    // }
    protected function redirectTo(Request $request)
    {
        if(!$request->expectsJson()) {
            
            if($request->routeIs('author.*')) {
                session()->flash('gagal', 'Login terlebih dahulu');
                return route('author.login',[
                    'gagal' => true,
                    'returnURL' => URL::current()
                ]);
            }
        }
    }
}
