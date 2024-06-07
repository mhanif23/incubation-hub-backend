<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MentorshipController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubmissionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'signIn']);
Route::post('logout', [UserController::class, 'signOut'])->middleware('auth:sanctum');
Route::post('forgot-password', [UserController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('submissions', [SubmissionController::class, 'store']); // Untuk membuat submission baru
    Route::get('submissions', [SubmissionController::class, 'index']); // Untuk mendapatkan semua submission
    Route::get('submissions/{id}', [SubmissionController::class, 'show']); // Untuk mendapatkan detail submission
    Route::post('submissions/{id}/delete', [SubmissionController::class, 'destroy']); // Untuk menghapus submission
    Route::post('submissions/{id}/approve', [SubmissionController::class, 'approve']); // Untuk menyetujui submission
    Route::post('submissions/{id}/reject', [SubmissionController::class, 'reject']); // Untuk menolak submission
});
