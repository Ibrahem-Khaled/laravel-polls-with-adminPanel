<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\PollsController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('update', [AuthController::class, 'update']);
Route::get('user', [AuthController::class, 'user']);
Route::post('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['apiAuth']], function () {
    //this route get home
    Route::get('sliders', [HomeController::class, 'sliders']);
    Route::get('notifications', [HomeController::class, 'notifications']);

    //this route get polls
    Route::get('polls', [PollsController::class, 'polls']);
    Route::get('completePolls', [PollsController::class, 'completePolls']);
    Route::get('poll/{pollId}', [PollsController::class, 'poll']);
    Route::post('poll/{pollId}', [PollsController::class, 'setUserAnswers']);
    Route::get('checkUserAnswered/{pollId}', [PollsController::class, 'checkUserAnswered']);
});

