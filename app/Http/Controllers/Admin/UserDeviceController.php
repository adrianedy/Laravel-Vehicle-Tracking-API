<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    public function index(User $user)
    {
        if (request()->ajax()) {
            $devices = $user->devices();;

            return  \DataTables::of($devices)
                ->editColumn('created_at', function ($device){
                    return "<span style='display:none;'>" . strtotime($device->created_at) . "</span>" 
                        . date('g:i A / d-M-Y', strtotime($device->created_at));
                })
                ->addColumn('action', function($device) use ($user) {
                    return view('admin.partials.revoke-button', [
                        'action' => route('admin.users.devices.destroy', [$user->id, $device->device_id]),
                    ]);
                })
                ->rawColumns(['created_at', 'action'])
                ->toJson();
        }
        
        return view('admin.user-devices', compact('user'));
    }

    public function destroy($user, Device $device)
    {
        $device->user_id = null;
        $device->save();

        return redirect()->to(url()->previous())->with('device', 'Data is successfully revoked!');
    }
}
