<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\notifcationController;
use App\Http\Controllers\dashboard\OptionController;
use App\Http\Controllers\dashboard\PaymentWithContactUsController;
use App\Http\Controllers\dashboard\PollController;
use App\Http\Controllers\dashboard\QuestionController;
use App\Http\Controllers\dashboard\SlideShowController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\mainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'customLogin'])->name('customLogin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'customRegister'])->name('customRegister');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('user', [AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/profile', [AuthController::class, 'update'])->name('profile.update');
Route::get('forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('resetPassword', [AuthController::class, 'resetPassword'])->name('resetPassword');


Route::get('/', function () {
    return redirect()->route('home');
});

Route::group(['middleware' => ['auth', 'adminCheck'], 'prefix' => 'dashboard'], function () {

    //this route dashboard
    Route::get('/', [mainController::class, 'home'])->name('home');

    //this route user
    Route::resource('users', UserController::class);

    //this route poll
    Route::resource('polls', PollController::class);

    //this route question
    Route::resource('polls.questions', QuestionController::class);
    Route::get('polls/{poll}/questions', [QuestionController::class, 'index'])->name('polls.questions.index');

    //this route option
    Route::resource('questions.options', OptionController::class);

    //this route sliders
    Route::resource('sliders', SlideShowController::class);
    Route::delete('slide/delete/{id}', [SlideShowController::class, 'destroy'])->name('slide.delete');

    //this route notification
    Route::resource('notifications', notifcationController::class);

    //this route payment and contact us
    Route::get('contact-us', [PaymentWithContactUsController::class, 'contactUs'])->name('contact-us');
    Route::get('payment', [PaymentWithContactUsController::class, 'payment'])->name('payment');
    Route::patch('payment/update/{id}', [PaymentWithContactUsController::class, 'updateStatusPayment'])->name('payment.update');

});
