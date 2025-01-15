<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseCommentController;
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
        Route::get('/total', 'countCoursesByTeacher'); 
        Route::get('{id}', 'show'); 
        Route::put('{course}', 'update'); 
        Route::delete('{course}', 'destroy');
        Route::post('{id}/enroll', 'enrollBatch'); 
        Route::get('members/count', 'countMembersByTeacher');
        Route::get('comments/count', 'countCommentsByTeacher');
        Route::get('content/count', 'countCourseContentByTeacher');
    });

    Route::controller(CourseController::class)->prefix('student')->group(function () {
        Route::get('my-courses', 'getCoursesByStudent'); 
        Route::get('courses/count', 'countCoursesByStudent');
        Route::get('comments/count', 'countCommentsByStudent');
        Route::post('courses/{id}/enroll', 'enrollByStudent'); 
    });

    Route::controller(CourseCommentController::class)->prefix('courses')->group(function () {
        Route::post('{id}/content/{content_id}', 'store'); 
        Route::put('{id}/content/{content_id}/comments/{comment_id}', 'updateCommentVisibility');
    });

    Route::controller(CourseContentController::class)->prefix('courses/{course}/content')->group(function () {
        Route::post('/', 'store');
        Route::get('{content}', 'show'); 
    });
});

Route::controller(CourseController::class)->prefix('global')->group(function () {
    Route::get('courses', 'allCourses');
    
});
