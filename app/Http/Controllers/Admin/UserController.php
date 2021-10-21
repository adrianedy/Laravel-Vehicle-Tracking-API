<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\UserRequest as Request;
use DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = User::query();

            return  DataTables::of($users)
                ->editColumn('is_active', function ($user){
                    return $user->is_active ? 'Active' : 'Deactive';
                })
                ->editColumn('created_at', function ($user){
                    return "<span style='display:none;'>" . strtotime($user->created_at) . "</span>" 
                        . date('g:i A / d-M-Y', strtotime($user->created_at));
                })
                ->addColumn('action', function($user) {
                    return view('admin.partials.edit-button', [
                        'get' => route('admin.users.edit', $user->id),
                        'patch' => route('admin.users.update', $user->id),
                        'target' => '#modal-edit-user',
                    ]) . view('admin.partials.delete-button', [
                        'action' => route('admin.users.destroy', $user->id),
                    ]) . view('admin.partials.detail-button', [
                        'link' => route('admin.users.devices.index', $user->id),
                        'title' => 'Devices',
                    ]);
                })
                ->rawColumns(['created_at', 'action'])
                ->toJson();
            }
            
            return view('admin.users');
    }

    public function store(Request $request)
    {
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_code   = $request->phone_code;
        $user->phone        = $request->phone_number;
        $user->password     = bcrypt($request->pin);
        $user->pin          = bcrypt($request->pin);
        $user->is_active    = $request->active ? 1 : 0;
        $user->save();

        return response()->json(['data' => ['message' => 'Data is successfully updated!']]);
    }

    public function edit(User $user)
    {
        $data = [
            'name'              => $user->name,
            'email'             => $user->email,
            'phone_code'        => $user->phone_code,
            'phone_number'      => $user->phone,
            // 'password'          => null,
            // 'comfirm_password'  => null,
            'pin'               => null,
            'comfirm_pin'       => null,
            'active'            => $user->is_active,
        ];

        return response(compact('data'));
    }

    public function update(Request $request, User $user)
    {
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_code   = $request->phone_code;
        $user->phone        = $request->phone_number;
        $user->password     = bcrypt($request->pin);
        $user->pin          = bcrypt($request->pin);
        $user->is_active    = $request->active ? 1 : 0;
        $user->save();

        return response()->json(['data' => ['message' => 'Data is successfully updated!']]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->to(url()->previous())->with('user', 'Data is successfully deleted!');
    }
}
