<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WrongPinAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class PinController extends Controller
{
    public function check()
    {
        $user = User::first();

        if (Hash::check(request()->pin, $user->pin)) {
            if ($wrongPinAttempt = $user->wrongPinAttempt) {
                $wrongPinAttempt->attempt = 0;
                $wrongPinAttempt->save();
            }

            $token = $user->requestAccessToken()->token();

            return response(['data' => ['access_token' => $token]]);
        }
        
        if ($wrongPinAttempt = $user->wrongPinAttempt) {
            $wrongPinAttempt->attempt += 1;
        } else {
            $wrongPinAttempt = new WrongPinAttempt();
            $wrongPinAttempt->user_id = $user->id;
            $wrongPinAttempt->attempt = 1;
        }

        $maxAttempt = 3;
        $retry = $maxAttempt - $wrongPinAttempt->attempt;
        
        if ($retry == 0) {
            $wrongPinAttempt->attempt = 0;
        }
        
        $wrongPinAttempt->save();

        return response(['data' => compact('retry')], 401);
    }

    public function update(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:5',
        ]);

        $user = $request->user();
        $user->pin = bcrypt(request()->pin);
        $user->save();

        return response(['data' => new stdClass()]);
    }
}
