<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComprehensiveController;


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
Route::post('/ComprehensiveExam/add', [ComprehensiveController::class, 'add']);

