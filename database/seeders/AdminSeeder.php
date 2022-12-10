<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Spatie\Permission\Traits\HasRoles;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::updateOrCreate(
            ['id' => 1, 'email' => 'admin@minispireloan.com', ],
            [
                'name' => 'Admin', 
                'email' => 'admin@minispireloan.com', 
                'email_verified_at' => null, 
                'password' => Hash::make('pass1234'), 
                'remember_token' => null,
            ]);

        $admin->assignRole('admin', 'admin');
    }
}