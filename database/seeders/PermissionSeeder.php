<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Supprimer le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Définir les permissions
        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-content',
            'access-site',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer et attribuer les permissions aux rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $client = Role::firstOrCreate(['name' => 'client']);
        $client->givePermissionTo(['access-site']);
    }
}

