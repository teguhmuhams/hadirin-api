<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected with sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('admins', AdminController::class);
    Route::apiResource('grades', GradeController::class);

    Route::post('/students/{student}/courses/{course}', [StudentCourseController::class, 'enrollStudentToCourse']);
    Route::post('/students/{student}/attendances/{attendance}', [StudentAttendanceController::class, 'enrollStudentToAttendance']);
});
