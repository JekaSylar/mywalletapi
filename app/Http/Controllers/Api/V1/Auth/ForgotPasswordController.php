<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\SendMailForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\Api\V1\Auth\CodeCheckForgotPasswordRequest;
use App\Http\Requests\Api\V1\Auth\UpdatePasswordForgotPasswordRequest;

class ForgotPasswordController extends Controller {
    public function sendCodeMail(SendMailForgotPasswordRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();
        if(!$user) {
            return response()->json([
                'message' => 'Email не знайдено',
            ], 404);
        }
        $code = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $user->resetCode()->delete();
        $user->resetCode()->create([
            'code' =>   $code,
            'token' => Str::random(32),
            'expires_at' => Carbon::now()->addHour()
        ]);
        Mail::to($request->email)->queue(new SendCodeResetPassword($code));

        return response()->json([
            'message' => 'Повідомлення з кодом відправлено',
            'token' => $user->resetCode->token,
        ], 200);
    }

    public function codeCheck(CodeCheckForgotPasswordRequest $request): JsonResponse
    {
        $resetCode = ResetCodePassword::query()->where('token',  $request->token)->first();

        if (!$resetCode) {
            return response()->json([
                'message' => 'Код не існує'
            ], 404);
        }

       if($resetCode->code === $request->code) {
           $currentDateTime = Carbon::now();
           $expiresAt = $resetCode->expires_at;
           if($currentDateTime->greaterThan($expiresAt)) {
               $resetCode->delete();
               return response()->json([
                   'message' => 'Час дії минув.'
               ], 410);
           }

           return response()->json([
                'token' => $resetCode->token,
           ]);
       }

        return response()->json([
            'message' => 'Код не існує'
        ], 404);
    }

    public function updatePassword(UpdatePasswordForgotPasswordRequest $request): JsonResponse
    {
        $resetCodePassword = ResetCodePassword::query()->where('token',  $request->token)->first();
        if(!$resetCodePassword) {
            return response()->json([
                'message' => 'Сторінку не знайдено'
            ], 404);
        }

        $user = $resetCodePassword->user;
        $user->update([
            'password' => $request->password
        ]);
        $resetCodePassword->delete();
        return response()->json([
            'message' => 'Пароль змінено',
            'emailUser' => $user->email
        ]);

    }
}
