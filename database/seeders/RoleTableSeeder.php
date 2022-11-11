<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\Log;


class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['super_admin', 'employee', 'company'];
        
        foreach($roles as $role) {
            try {
                $role = Role::create(['name' => $role, 'guard_name' => 'api']);
                echo $role->name . ' Created' . PHP_EOL;
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
