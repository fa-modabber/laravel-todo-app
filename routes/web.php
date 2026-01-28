<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TodoController::class, 'index'])->name('todos.index')->middleware('auth');



Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('forget.password');
Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPasswordPost'])->name('reset.password.post');



Route::prefix('todos')->name('todos.')->middleware('auth')->group(function () {
    Route::get('/create', [TodoController::class, 'create'])->name('create'); // todos.create
    Route::get('/{todo}', [TodoController::class, 'show'])->name('show'); // todos.show
    Route::post('/', [TodoController::class, 'store'])->name('store'); // todos.store
    Route::get('/{todo}/edit', [TodoController::class, 'edit'])->name('edit'); // todos.edit
    Route::put('/{todo}', [TodoController::class, 'update'])->name('update'); // todos.update
    Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('destroy'); // todos.update
    Route::get('/{todo}/complete', [TodoController::class, 'complete'])->name('complete'); // todos.complete

});



Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('', [CategoryController::class, 'index'])->name('index'); // categories.index
    Route::get('/create', [CategoryController::class, 'create'])->name('create'); // categories.create
    Route::post('/', [CategoryController::class, 'store'])->name('store'); // categories.store
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit'); // categories.edit
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update'); // categories.update
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy'); // categories.update
});

