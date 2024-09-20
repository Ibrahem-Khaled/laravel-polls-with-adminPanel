<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\PaymentWithContactUsController;
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
Route::delete('deleteAccount', [AuthController::class, 'deleteAccount']);
Route::post('panUser', [AuthController::class, 'panUser']);
Route::get('user', [AuthController::class, 'user']);
Route::post('logout', [AuthController::class, 'logout']);

route::get('canBan', function () {
    return response()->json(false, 200);
});

Route::group(['middleware' => ['apiAuth']], function () {
    //this route get home
    Route::get('sliders', [HomeController::class, 'sliders']);
    Route::get('notifications', [HomeController::class, 'notifications']);

    //this route get polls
    Route::get('polls', [PollsController::class, 'polls']);
    Route::get('completePolls', [PollsController::class, 'completePolls']);
    Route::get('poll/{pollId}', [PollsController::class, 'poll']);
    Route::get('pollWithUserAnswers/{pollId}', [PollsController::class, 'pollWithUserAnswers']);
    Route::post('poll/{pollId}', [PollsController::class, 'setUserAnswers']);
    Route::get('checkUserAnswered/{pollId}', [PollsController::class, 'checkUserAnswered']);

    //this routes contact us  and payemnt
    Route::post('contact-us', [PaymentWithContactUsController::class, 'storeContactUs']);

    // payment
    Route::get('show-payment', [PaymentWithContactUsController::class, 'payment']);
    Route::post('payment/store', [PaymentWithContactUsController::class, 'storePayment']);
});

