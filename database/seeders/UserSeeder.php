<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer un administrateur
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('adminpassword')
        ]);
        $admin->assignRole('admin');

        // Créer un client
        $client = User::firstOrCreate([
            'email' => 'client@example.com'
        ], [
            'name' => 'Client',
            'password' => bcrypt('clientpassword')
        ]);
        $client->assignRole('client');
    }
}

