<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FunctionController;


Route::controller(ViewController::class)->group(function () {
    Route::get('/','index')->name('index');
    Route::get('/login','login')->name('login');
    Route::get('/register','register')->name('register');
    Route::get('/register/studentVerify','register_studentVerify')->name('register.studentVerify');
});

Route::controller(FunctionController::class)->group(function () {
    Route::post('/login', 'login')->name('loginFunction');
    Route::post('/register', 'register')->name('registerFunction');
    Route::post('/logout', 'logout')->name('logoutFunction');
    Route::post('/register/studentVerify','register_studentVerify')->name('register.studentVerify.function');
});