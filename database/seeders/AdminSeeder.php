<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrNew(['email' =>  'info@admin.com']);
        $admin->name = 'Admin';
        $admin->email = 'info@admin.com';
        $admin->password = Hash::make('123456');
        $admin->save();
        //Assign role
        $admin->assignRole('super_admin');
    }
}
