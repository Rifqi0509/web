<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\flutter\AuthController;
use App\Http\Controllers\flutter\DataController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\flutter\QuestionController;

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
Route::post('/login_flutter', [AuthController::class, 'login']);
Route::post('/register_flutter', [AuthController::class, 'register']);
Route::post('/feedback_flutter', [DataController::class, 'feedback']);
Route::post('/vip_flutter', [VipController::class, 'store']);
Route::get('/questions_flutter', [QuestionController::class, 'show']);
Route::post('/survey_flutter', [QuestionController::class, 'store']);