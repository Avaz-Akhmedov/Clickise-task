<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->create([
            'name' => 'Avaz',
            'email' => 'test@test.com',
            'phone_number' => '+998 88 797 72 12',
            'password' => 'password',
        ]);

        UserSetting::query()->create([
            'user_id' => $user->id,
            'setting_key' => 'theme',
            'setting_value' => 'dark',
        ]);

        UserSetting::query()->create([
            'user_id' => $user->id,
            'setting_key' => 'background',
            'setting_value' => 'brown',
        ]);

        UserSetting::query()->create([
            'user_id' => $user->id,
            'setting_key' => 'interface language',
            'setting_value' => 'English',
        ]);
    }
}
