<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use GuzzleHttp\Middleware;

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
    return view('auth.register');
});

Route::group(['middleware'=>'guest'],function(){
    Route::get('/login',[AuthController::class,'index'])->name('loginpage');
    Route::get('/register',[AuthController::class,'registerform'])->name('registerpage');
    Route::post('/login',[AuthController::class,'login'])->name('login');
    Route::post('/register',[AuthController::class,'register'])->name('register');
});    

Route::group(['middleware'=>'auth'],function(){
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/home',[AuthController::class,'home'])->name('homepage');
    Route::post('/delete',[TaskController::class,'delete'])->name('delete');
});
