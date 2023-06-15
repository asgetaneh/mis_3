<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default permissions
        Permission::create(['name' => 'list genders']);
        Permission::create(['name' => 'view genders']);
        Permission::create(['name' => 'create genders']);
        Permission::create(['name' => 'update genders']);
        Permission::create(['name' => 'delete genders']);

        Permission::create(['name' => 'list gendertranslations']);
        Permission::create(['name' => 'view gendertranslations']);
        Permission::create(['name' => 'create gendertranslations']);
        Permission::create(['name' => 'update gendertranslations']);
        Permission::create(['name' => 'delete gendertranslations']);

        Permission::create(['name' => 'list goals']);
        Permission::create(['name' => 'view goals']);
        Permission::create(['name' => 'create goals']);
        Permission::create(['name' => 'update goals']);
        Permission::create(['name' => 'delete goals']);

        Permission::create(['name' => 'list goaltranslations']);
        Permission::create(['name' => 'view goaltranslations']);
        Permission::create(['name' => 'create goaltranslations']);
        Permission::create(['name' => 'update goaltranslations']);
        Permission::create(['name' => 'delete goaltranslations']);

        Permission::create(['name' => 'list inititives']);
        Permission::create(['name' => 'view inititives']);
        Permission::create(['name' => 'create inititives']);
        Permission::create(['name' => 'update inititives']);
        Permission::create(['name' => 'delete inititives']);

        Permission::create(['name' => 'list inititivetranslations']);
        Permission::create(['name' => 'view inititivetranslations']);
        Permission::create(['name' => 'create inititivetranslations']);
        Permission::create(['name' => 'update inititivetranslations']);
        Permission::create(['name' => 'delete inititivetranslations']);

        Permission::create(['name' => 'list keypeformanceindicators']);
        Permission::create(['name' => 'view keypeformanceindicators']);
        Permission::create(['name' => 'create keypeformanceindicators']);
        Permission::create(['name' => 'update keypeformanceindicators']);
        Permission::create(['name' => 'delete keypeformanceindicators']);

        Permission::create(['name' => 'list keypeformanceindicatorts']);
        Permission::create(['name' => 'view keypeformanceindicatorts']);
        Permission::create(['name' => 'create keypeformanceindicatorts']);
        Permission::create(['name' => 'update keypeformanceindicatorts']);
        Permission::create(['name' => 'delete keypeformanceindicatorts']);

        Permission::create(['name' => 'list languages']);
        Permission::create(['name' => 'view languages']);
        Permission::create(['name' => 'create languages']);
        Permission::create(['name' => 'update languages']);
        Permission::create(['name' => 'delete languages']);

        Permission::create(['name' => 'list objectives']);
        Permission::create(['name' => 'view objectives']);
        Permission::create(['name' => 'create objectives']);
        Permission::create(['name' => 'update objectives']);
        Permission::create(['name' => 'delete objectives']);

        Permission::create(['name' => 'list objectivetranslations']);
        Permission::create(['name' => 'view objectivetranslations']);
        Permission::create(['name' => 'create objectivetranslations']);
        Permission::create(['name' => 'update objectivetranslations']);
        Permission::create(['name' => 'delete objectivetranslations']);

        Permission::create(['name' => 'list offices']);
        Permission::create(['name' => 'view offices']);
        Permission::create(['name' => 'create offices']);
        Permission::create(['name' => 'update offices']);
        Permission::create(['name' => 'delete offices']);

        Permission::create(['name' => 'list officetranslations']);
        Permission::create(['name' => 'view officetranslations']);
        Permission::create(['name' => 'create officetranslations']);
        Permission::create(['name' => 'update officetranslations']);
        Permission::create(['name' => 'delete officetranslations']);

        Permission::create(['name' => 'list perspectives']);
        Permission::create(['name' => 'view perspectives']);
        Permission::create(['name' => 'create perspectives']);
        Permission::create(['name' => 'update perspectives']);
        Permission::create(['name' => 'delete perspectives']);

        Permission::create(['name' => 'list perspectivetranslations']);
        Permission::create(['name' => 'view perspectivetranslations']);
        Permission::create(['name' => 'create perspectivetranslations']);
        Permission::create(['name' => 'update perspectivetranslations']);
        Permission::create(['name' => 'delete perspectivetranslations']);

        Permission::create(['name' => 'list planaccomplishments']);
        Permission::create(['name' => 'view planaccomplishments']);
        Permission::create(['name' => 'create planaccomplishments']);
        Permission::create(['name' => 'update planaccomplishments']);
        Permission::create(['name' => 'delete planaccomplishments']);

        Permission::create(['name' => 'list planingyears']);
        Permission::create(['name' => 'view planingyears']);
        Permission::create(['name' => 'create planingyears']);
        Permission::create(['name' => 'update planingyears']);
        Permission::create(['name' => 'delete planingyears']);

        Permission::create(['name' => 'list planingyeartranslations']);
        Permission::create(['name' => 'view planingyeartranslations']);
        Permission::create(['name' => 'create planingyeartranslations']);
        Permission::create(['name' => 'update planingyeartranslations']);
        Permission::create(['name' => 'delete planingyeartranslations']);

        Permission::create(['name' => 'list reportingperiods']);
        Permission::create(['name' => 'view reportingperiods']);
        Permission::create(['name' => 'create reportingperiods']);
        Permission::create(['name' => 'update reportingperiods']);
        Permission::create(['name' => 'delete reportingperiods']);

        Permission::create(['name' => 'list reportingperiodts']);
        Permission::create(['name' => 'view reportingperiodts']);
        Permission::create(['name' => 'create reportingperiodts']);
        Permission::create(['name' => 'update reportingperiodts']);
        Permission::create(['name' => 'delete reportingperiodts']);

        Permission::create(['name' => 'list reportingperiodtypes']);
        Permission::create(['name' => 'view reportingperiodtypes']);
        Permission::create(['name' => 'create reportingperiodtypes']);
        Permission::create(['name' => 'update reportingperiodtypes']);
        Permission::create(['name' => 'delete reportingperiodtypes']);

        Permission::create(['name' => 'list reportingperiodtypets']);
        Permission::create(['name' => 'view reportingperiodtypets']);
        Permission::create(['name' => 'create reportingperiodtypets']);
        Permission::create(['name' => 'update reportingperiodtypets']);
        Permission::create(['name' => 'delete reportingperiodtypets']);

        Permission::create(['name' => 'list strategies']);
        Permission::create(['name' => 'view strategies']);
        Permission::create(['name' => 'create strategies']);
        Permission::create(['name' => 'update strategies']);
        Permission::create(['name' => 'delete strategies']);

        Permission::create(['name' => 'list strategytranslations']);
        Permission::create(['name' => 'view strategytranslations']);
        Permission::create(['name' => 'create strategytranslations']);
        Permission::create(['name' => 'update strategytranslations']);
        Permission::create(['name' => 'delete strategytranslations']);

        Permission::create(['name' => 'list suitablekpis']);
        Permission::create(['name' => 'view suitablekpis']);
        Permission::create(['name' => 'create suitablekpis']);
        Permission::create(['name' => 'update suitablekpis']);
        Permission::create(['name' => 'delete suitablekpis']);

        // Create user role and assign existing permissions
        $currentPermissions = Permission::all();
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo($currentPermissions);

        // Create admin exclusive permissions
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $user = \App\Models\User::whereEmail('assefa.getaneh@ju.edu.et')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
