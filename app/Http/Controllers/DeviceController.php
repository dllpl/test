<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use app\Models\User;

class DeviceController extends Controller
{
    public function index()
    {
        // Получаем устройства текущего пользователя
        $devices = auth()->user()->devices;

        return view('devices.index', compact('devices'));
    }

    public function create(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));

        return view('admin.devices.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'serial_number' => 'required|string',
            'contract_number' => 'required|string',
        ]);

        Device::create($request->all());

        return back()->with('success', trans('admin.Device added successfully.'));
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return back()->with('success', trans('admin.Device deleted successfully.'));
    }
}
