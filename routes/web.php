<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\AdminController;


Route::controller(ViewController::class)->group(function () {
    Route::get('/','index')->name('index');
    Route::get('/login','login')->name('login');
    Route::get('/register','register')->name('register');
    Route::get('/admin_dashboard', 'adminDashboard')->name('admin_dashboard');
});

Route::controller(FunctionController::class)->group(function () {
    Route::post('/login', 'login')->name('loginFunction');
    Route::post('/register', 'register')->name('registerFunction');
    Route::post('/logout', 'logout')->name('logoutFunction');
});

Route::controller(AdminController::class)->group(function() {
    Route::get('/admin/courses', 'courses')->name('admin.courses');
    Route::post('/admin/courses', 'addCourse')->name('admin.addCourse');
    Route::get('/admin/courses/{course}/edit', 'editCourse')->name('admin.editCourse');
    Route::put('/admin/courses/{course}', 'updateCourse')->name('admin.updateCourse');
    Route::delete('/admin/courses/{course}', 'destroyCourse')->name('admin.destroyCourse');
    
    Route::get('/admin/student/register', 'registerStudentView')->name('admin.registerStudentView');
    Route::post('/admin/student/register', 'registerStudent')->name('admin.registerStudent');

    Route::get('/admin/users', 'users')->name('admin.users');
    Route::get('/admin/user/{user}/edit', 'editUser')->name('admin.editUser');
    Route::put('/admin/user/{user}', 'updateUser')->name('admin.updateUser');
    Route::delete('/admin/user/{user}', 'destroyUser')->name('admin.destroyUser');

    Route::get('/admin/assignments/select-course', 'selectCourseForAssignment')->name('admin.selectCourseForAssignment');
    Route::get('/admin/courses/{course}/assignments', 'viewCourseAssignments')->name('admin.assignments.view');
    Route::get('/admin/courses/{course}/assignments/add', 'addAssignmentToCourse')->name('admin.assignments.add');
    Route::post('/admin/courses/{course}/assignments', 'storeAssignmentToCourse')->name('admin.assignments.store');
});


