<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use \App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AccountController;

//v1
Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', function () {
            // Uses first & second middleware...
        });

        Route::apiResource('accounts', AccountController::class);
        Route::apiResource('category', CategoryController::class)->except([
            'show'
        ]);
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::put('/', [UserController::class, 'update'])->name('user.update');
            Route::delete('/', [UserController::class, 'destroy'])->name('user.destroy');
        });


        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    });

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
   // Route::get('/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
   // Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
    //ResetPassword
    Route::post('password/mail', [ForgotPasswordController::class, 'sendCodeMail'] )->name('password-forgot.send-code-mail');
    Route::post('password/code/check/', [ForgotPasswordController::class, 'codeCheck'] )->name('password-forgot.code.check');
    Route::post('password/update', [ForgotPasswordController::class, 'updatePassword'] )->name('password-forgot.update');
});





