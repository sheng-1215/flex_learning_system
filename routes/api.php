<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TopicProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::apiResource('/user', UserController::class);

// Topic Progress API routes - using web middleware for session-based auth
Route::middleware('web')->group(function () {
    Route::post('/topic-progress', [TopicProgressController::class, 'store']);
    Route::get('/topic-progress/{topic}', [TopicProgressController::class, 'show']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
