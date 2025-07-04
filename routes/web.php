<?php

use App\Http\Middleware\checkauth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ajaxController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FunctionController;



Route::controller(ViewController::class)->group(function () {
    Route::get('/','login')->name('login');
    Route::get('/register','register')->name('register');
    Route::get('/register/studentVerify','register_studentVerify')->name('register.studentVerify');
});

Route::controller(FunctionController::class)->group(function () {
    Route::post('/login', 'login')->name('loginFunction');
    Route::post('/register', 'register')->name('registerFunction');
    Route::post('/logout', 'logout')->name('logoutFunction');
    Route::post('/register/studentVerify','register_studentVerify')->name('register.studentVerify.function');
});



Route::middleware(['auth'])->group(function () {
    Route::prefix("student")->group(function () {
    Route::controller(ViewController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('student.dashboard');
        Route::get('/CUActivity/{id}', 'CUActivity')->name('student.CUActivity');
        
        Route::get('/profile',"profile")->name('student.profile');
        Route::get('/profile/edit',"profile_edit")->name('student.profile.edit');
        Route::get('/assignment','assignment')->name('student.assignment');
        Route::get('/assignmentDetail/{id}','assignmentSubmit')->name('student.assignment.detail');
        ROute::get('/assignment/{id}/dowmload','assignmentDownload')->name('student.assignment.download');

    });
    Route::controller(FunctionController::class)->group(function () {
        Route::post('/login', 'login')->name('student.loginFunction');
        Route::post('/logout', 'logout')->name('student.logoutFunction');
        Route::post('/assignmentSubmit/{id}', 'assignmentSubmit')->name('student.assignment.submit');
        Route::get('/downloadAssignment/{id}', 'downloadAssignment')->name('student.assignment.download');
        Route::delete('/assignmentDelete/{id}', 'assignmentDelete')->name('student.assignment.delete');
        
    });

    Route::controller(ajaxController::class)->group(function () {
        Route::post('/topic_progress/update', 'topic_progress_update')->name('student.topic.progress.update');
    });

    // Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
    
});
});
