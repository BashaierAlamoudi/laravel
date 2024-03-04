<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComprehensiveController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\PublicationController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/ComprehensiveExam', [ComprehensiveController::class, 'fetchData']);
Route::post('/ComprehensiveExam/edit/{id}', [ComprehensiveController::class, 'update']);
Route::post('/ComprehensiveExam/delete/{id}', [ComprehensiveController::class, 'delete']);
Route::post('/ComprehensiveExam/add/', [ComprehensiveController::class, 'add']);

Route::get('/Seminars', [SeminarController::class, 'fetchData']);
Route::post('/Seminars/edit/{id}', [SeminarController::class, 'update']);
Route::post('/Seminars/delete/{id}', [SeminarController::class, 'delete']);
Route::post('/Seminars/add/', [SeminarController::class, 'add']);

Route::get('/publications', [PublicationController::class, 'fetchData']);
Route::post('/publications/edit/{id}', [PublicationController::class, 'update']);
Route::post('/publications/delete/{id}', [PublicationController::class, 'delete']);
Route::post('/publications/add/', [PublicationController::class, 'add']);
Route::get('/publications/pdf/{filename}', [PublicationController::class, 'getPdf']);
