<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ReCaptcha\ReCaptcha;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        /**
         * Проверка капчи
         */
        // $recaptcha = new ReCaptcha(config('captcha.secret'));
        // $resp = $recaptcha->verify($request->token, @$_SERVER['HTTP_X_REAL_IP']);
        // if (! $resp->isSuccess()) {
        //     return response()->json('captcha error', 403);
        // }

        $response = User::login($request->credentials);
        return response()->json($response->data, $response->status);
    }

    public function logout()
    {
        User::logout();
        return redirect('/');
    }
}
