<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeviceRequest;
use App\Models\Device;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS1DFacade;
use PDF;

class DeviceController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $devices = Device::with('user');

            return  DataTables::of($devices)
                // ->addColumn('barcode', function($device){
                //     return "<span class='d-sm-block'>
                //         <img src='data:image/png;base64," . DNS1DFacade::getBarcodePNG($device->device_id, 'C128') . "' alt='barcode' class='pop-img'/>
                //         </span>";
                // })
                ->editColumn('created_at', function ($device){
                    return "<span style='display:none;'>" . strtotime($device->created_at) . "</span>" 
                        . date('g:i A / d-M-Y', strtotime($device->created_at));
                })
                ->addColumn('action', function($device) {
                    return view('admin.partials.edit-button', [
                        'get' => route('admin.devices.edit', $device->device_id),
                        'patch' => route('admin.devices.update', $device->device_id),
                        'target' => '#modal-edit-device',
                    ]) . view('admin.partials.delete-button', [
                        'action' => route('admin.devices.destroy', $device->device_id),
                    ]) . view('admin.partials.print-button', [
                        'link' => route('admin.devices.print', $device->device_id),
                        'title' => 'Print Barcode',
                    ]);
                })
                ->rawColumns(['barcode', 'created_at', 'action'])
                ->toJson();
        }
        
        return view('admin.devices');
    }

    public function store(DeviceRequest $request)
    {
        $data = $request->only(['name', 'license', 'type']);
        $device = new Device($data);
        $device->device_id = Device::generateDeviceId();
        $device->save();

        DB::connection('mongodb')->createCollection($device->device_id);

        return response(['data' => ['message' => 'Data is successfully updated!']]);
    }

    public function edit(Device $device)
    {
        $data = [
            'name'      => $device->name,
            'license'   => $device->license,
            'type'      => $device->type,
        ];

        return response(compact('data'));
    }

    public function update(DeviceRequest $request, Device $device)
    {
        $data = $request->only(['name', 'license', 'type']);
        $device = $device->fill($data);
        $device->save();

        return response(['data' => ['message' => 'Data is successfully updated!']]);
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->to(url()->previous())->with('user', 'Data is successfully deleted!');
    }

    public function print(Device $device)
    {
        $barcode = DNS1DFacade::getBarcodePNG($device->device_id, 'C128');

        return PDF::loadView("admin.pdf.barcode", compact('device', 'barcode'))
            ->setPaper('a4')->stream(); 
    }
}
