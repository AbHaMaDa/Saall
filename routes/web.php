<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/index', function () {
    return view('index');
});


Route::get('/register', function () {
    return view('register');
})->middleware('guest');

Route::get('/login', function () {
    return view('login');
})->middleware('guest');



Route::post('Auth/register', [AuthController::class, 'register'])->name('Auth.register')->middleware('guest');

Route::post('Auth/login', [AuthController::class, 'login'])->name('Auth.login')->middleware('guest');

Route::get('Auth/logout', [AuthController::class, 'logout'])->name('Auth.logout');


Route::get('/index', [QuestionController::class, 'index'])->name('questions.index');

Route::post('questions/store', [QuestionController::class, 'store'])->name('questions.store');

Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');

Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');


Route::get('/search', [QuestionController::class,'search'])->name('questions.search');
