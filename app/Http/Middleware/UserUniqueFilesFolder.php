<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserUniqueFilesFolder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if( Auth::check() ){
            // Nama folder unik -> myfiles
            $folder_name = 'my'.Auth::user()->id.'files';
            if( !Storage::disk('public')->exists($folder_name) ){
                Storage::disk('public')->makeDirectory($folder_name, 0755, true, true);
            }
            Config::set('elfinder.dir',["storage/$folder_name"]); 
            // middlewarenya diregister ke kernel.php
        }

        return $next($request);
    }
}
