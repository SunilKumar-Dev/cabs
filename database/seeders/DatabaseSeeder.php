<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

use App\Models\Company;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Role Permissions Configuration
        |--------------------------------------------------------------------------
        */

        $rolePermissions = [
            'Superadmin' => ['*'],

            'Admin' => [
                'users' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'roles' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'permissions' => ['viewAny', 'view', 'create', 'edit','update', 'delete', 'status'],
                'vehicles' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'brands' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'drivers' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'fuels' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'gpstrackers' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'fuels' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'fleets' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'loans' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'modals' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'owners' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'variants' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
                'documents' => ['viewAny', 'view', 'create', 'edit', 'update', 'delete', 'status'],
            ],

            'User' => [
                // 'vehicles' => ['viewAny'],
                // 'drivers' => ['viewAny'],
            ],
        ];

        

        /*
        |--------------------------------------------------------------------------
        | Create All Permissions
        |--------------------------------------------------------------------------
        */

        $allPermissions = [];

        foreach ($rolePermissions as $role => $modules) {

            // Skip Superadmin because it uses "*"
            if ($modules === ['*']) {
                continue;
            }

            foreach ($modules as $module => $actions) {
                foreach ($actions as $action) {

                    $permission = "{$action}_{$module}";

                    Permission::firstOrCreate([
                        'name' => $permission,
                    ]);

                    $allPermissions[] = $permission;
                }
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */

        foreach (array_keys($rolePermissions) as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($rolePermissions as $roleName => $modules) {

            $role = Role::findByName($roleName);

            // Superadmin gets every permission
            if ($modules === ['*']) {
                $role->syncPermissions(Permission::all());
                continue;
            }

            $permissions = [];

            foreach ($modules as $module => $actions) {
                foreach ($actions as $action) {
                    $permissions[] = "{$action}_{$module}";
                }
            }

            $role->syncPermissions($permissions);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Super Admin User
        |--------------------------------------------------------------------------
        */

        $superAdmin = User::firstOrCreate(
            [
                'email' => 'sunilyphpdev@gmail.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123'),
                'uuid' => (string) Str::uuid(),
                'role' => 'Superadmin',
            ]
        );

        $superAdmin->assignRole('Superadmin');



        
        

        /*
        |--------------------------------------------------------------------------
        | Call Other Seeders
        |--------------------------------------------------------------------------
        */

        // $this->call([
        //     DriverSeeder::class,
        // ]);
    }

   
    
}

