<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\CourseController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware(['auth:api', 'role:1'])->group(function () {
//     Route::get('/teacher/courses', [CourseController::class, 'getCoursesByTeacher']);
// });

// Route::middleware(['auth:api', 'role:2'])->group(function () {
//     Route::get('/course/{id}/members', [CourseController::class, 'getCourseMembers']);
// });

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::get('me', [AuthController::class, 'me'])->middleware('jwt.auth');
});

Route::middleware(['jwt.auth'])->group(function () {
    Route::controller(CourseController::class)->prefix('courses')->group(function () {
        Route::get('/my-course', 'index'); 
        Route::post('/store', 'store'); 
        Route::get('{id}', 'show'); 
        Route::put('{course}', 'update'); 
        Route::delete('{course}', 'destroy');
        Route::post('{id}/enroll', 'enroll');
    });

    Route::controller(CourseContentController::class)->prefix('courses/{course}/content')->group(function () {
        Route::post('/', 'store');
    });
});
