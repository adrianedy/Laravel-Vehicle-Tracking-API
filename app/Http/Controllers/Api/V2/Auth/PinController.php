<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Models\WrongPinAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class PinController extends Controller
{
    public function check()
    {
        $user = auth()->user();

        if (Hash::check(request()->pin, $user->pin)) {
            if ($wrongPinAttempt = $user->wrongPinAttempt) {
                $wrongPinAttempt->attempt = 0;
                $wrongPinAttempt->save();
            }

            return response(['data' => new stdClass()]);
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
