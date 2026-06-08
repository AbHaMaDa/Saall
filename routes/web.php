<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/register', function () {
    return view('register');
})->middleware('guest');

Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post(
    '/forgot-password',
    [ResetPassword::class, 'PasswordEmail']
)->middleware('guest')->name('password.email');


Route::get(
    '/reset-password/{token}',
    [ResetPassword::class, 'passwordReset']
)->middleware('guest')->name('password.reset');



Route::post(
    '/reset-password',
    [ResetPassword::class, 'passwordUpdate']
)->middleware('guest')->name('password.update');




Route::post('Auth/register', [AuthController::class, 'register'])->name('Auth.register')->middleware('guest');

Route::post('Auth/login', [AuthController::class, 'login'])->name('Auth.login')->middleware('guest');

Route::get('Auth/logout', [AuthController::class, 'logout'])->name('Auth.logout');


Route::get('/', [QuestionController::class, 'index'])->name('home');
Route::get('/index', [QuestionController::class, 'index'])->name('questions.index');

Route::post('questions/store', [QuestionController::class, 'store'])->middleware('throttle:questions')->name('questions.store');

Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');

Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');


Route::get('/search', [QuestionController::class, 'search'])->name('questions.search');

Route::get('/search/visitor', [QuestionController::class, 'visitorSearch'])->name('questions.visitorSearch');

Route::patch('admin/users/{user}/promote', [UserController::class, 'promote'])->name('users.promote');
Route::patch('admin/users/{user}/demote', [UserController::class, 'demote'])->name('users.demote');
