<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\User;

class DeviceSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        // Создаем устройства для каждого пользователя
        foreach ($users as $user) {
            Device::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
