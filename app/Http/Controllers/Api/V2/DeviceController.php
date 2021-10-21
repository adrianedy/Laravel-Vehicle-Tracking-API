<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\DeviceResource;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V2\DeviceRequest;
use App\Http\Resources\Api\V2\DeviceHistoryResource;
use App\Models\DeviceHistory;
use App\Models\DeviceLocation;
use Carbon\Carbon;
use stdClass;

class DeviceController extends Controller
{
    public function index()
    {
        $type = request()->type ?? [];

        $devices = Device::with('location')
            ->whereIn('type', $type)
            ->when($search = request()->q, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('license', 'like', "%{$search}%");
            })
            ->when(!auth()->user()->is_admin, function ($q) {
                $q->where('user_id', auth()->user()->id);
            })
            ->when(auth()->user()->is_admin && request()->user_id, function ($q) use ($search) {
                $q->where('user_id', request()->user_id);
            })
            ->latest()->paginate(10);

        return DeviceResource::collection($devices);
    }

    public function allDevices()
    {
        $devices = Device::with('location')->latest()->get();

        return DeviceResource::collection($devices);
    }

    public function show(Device $device)
    {
        return response(['data' => new DeviceResource($device)]);
    }

    public function store(DeviceRequest $request)
    {
        $data               = $request->only(['name', 'license', 'type']);
        $device             = Device::find($request->id);
        $device             = $device->fill($data);
        $device->user_id    = $request->user()->id;
        $device->save();

        return response(['data' => new stdClass()]);
    }

    public function update(DeviceRequest $request, Device $device)
    {
        $data   = $request->only(['name', 'license', 'type']);
        $device = $device->fill($data);
        $device->save();

        return response(['data' => new stdClass()]);
    }

    public function destroy(Device $device)
    {
        $device->user_id = null;
        $device->save();

        return response(['data' => new stdClass()]);
    }

    public function location(Request $request, Device $device)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
            'heading' => 'required',
        ]);

        $data = $request->only(['lat', 'lng', 'heading']);
        
        if ($location = $device->location) {
            $location = $location->fill($data);
        } else {
            $location = new DeviceLocation($data);
            $location->device_id = $device->id;
        }
        $location->save();

        $history = DeviceHistory::getCollection($device->device_id);
        $history->id = $device->device_id;
        $history = $history->fill($data);
        $history->timestamp = now();
        $history->save();
  
        return response(['data' => ['device_type' => $device->type, 'user_id' => $device->user->id]]);
    }

    public function history(Device $device)
    {
        $date = request()->date ?? date('Y-m-d');
        $timezone = request()->timezone ?? 'UTC';
        $date = Carbon::parse($date, $timezone);

        $histories = DeviceHistory::getCollection($device->device_id)
            ->whereTimestampDate($date)
            ->get();

        return DeviceHistoryResource::collection($histories);
    }
}
 