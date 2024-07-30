<?php

use App\Http\Controllers\dashboard\notifcationController;
use App\Http\Controllers\dashboard\OptionController;
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

Route::get('/', [mainController::class, 'home'])->name('home');

Route::group([], function () {

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

    //this route notification
    Route::resource('notifications', notifcationController::class);


});
