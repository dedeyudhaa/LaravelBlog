<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front.pages.example');
});

// ROUTE AUTHOR DIBUAT FILE BARU DI author.php + RouteServiceProvider, Authenticate, dan RedirectIfAuthenticate juga di edit