<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

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

// Route::get('/', function () {
//     return view('front.pages.example');
// });

Route::view('/', 'front.pages.home')->name('home');

Route::get('/article/{any}', [BlogController::class, 'readPost'])->name('read_post'); //display satu post/display detail post
Route::get('/category/{any}', [BlogController::class, 'categoryPosts'])->name('category_posts'); //display semua post berdasarkan category
Route::get('/posts/tag/{any}', [BlogController::class, 'tagPosts'])->name('tag_posts'); //display semua post berdasarkan tag
Route::get('/search', [BlogController::class, 'searchBlog'])->name('search_posts'); //display semua post berdasar pencarian

// ROUTE AUTHOR DIBUAT FILE BARU DI author.php + RouteServiceProvider, Authenticate, dan RedirectIfAuthenticate juga di edit