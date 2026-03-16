<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',

            'vclaim.view',
            'vclaim.create',
            'vclaim.edit',
            'vclaim.delete',

            'antrean-rs.view',
            'antrean-rs.create',
            'antrean-rs.edit',
            'antrean-rs.delete',

            'apotek.view',
            'apotek.create',
            'apotek.edit',
            'apotek.delete',

            'user-management.view',
            'user-management.create',
            'user-management.edit',
            'user-management.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superadmin  = Role::firstOrCreate(['name' => 'superadmin']);
        $bpjs        = Role::firstOrCreate(['name' => 'bpjs']);
        $farmasi     = Role::firstOrCreate(['name' => 'farmasi']);
        $pendaftaran = Role::firstOrCreate(['name' => 'pendaftaran']);

        $superadmin->syncPermissions(Permission::all());

        $bpjs->syncPermissions([
            'dashboard.view',
            'vclaim.view',
            'vclaim.create',
            'vclaim.edit',
            'antrean-rs.view',
            'antrean-rs.create',
            'antrean-rs.edit',
        ]);

        $farmasi->syncPermissions([
            'dashboard.view',
            'apotek.view',
            'apotek.create',
            'apotek.edit',
        ]);

        $pendaftaran->syncPermissions([
            'dashboard.view',
            'antrean-rs.view',
            'antrean-rs.create',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'admin@simrs.local'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );

        $user->assignRole('superadmin');
    }
}
