<?php

// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create an admin with a hashed password
        $admin = new Admin;
        $admin->orgID = 1;
        $admin->first_name = 'Danica';
        $admin->middle_name = 'Echica';
        $admin->last_name = 'Dinaguit';
        $admin->phone_number = '09123456789';
        $admin->email = 'admin@gmail.com';
        $admin->password = Hash::make('123456789'); // Hash the password
        $admin->save();
    }
}
