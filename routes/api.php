<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use \App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AccountController;

//v1
Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', function () {
            // Uses first & second middleware...
        });

        Route::apiResource('accounts', AccountController::class);

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

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





