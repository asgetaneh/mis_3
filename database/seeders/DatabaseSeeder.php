<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Office;
use App\Models\Language;
use Illuminate\Database\Seeder;
use App\Models\OfficeTranslation;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Permission Seeder
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default permissions
        Permission::updateOrCreate(['name' => 'list genders']);
        Permission::updateOrCreate(['name' => 'view genders']);
        Permission::updateOrCreate(['name' => 'create genders']);
        Permission::updateOrCreate(['name' => 'update genders']);
        Permission::updateOrCreate(['name' => 'delete genders']);

        Permission::updateOrCreate(['name' => 'list gendertranslations']);
        Permission::updateOrCreate(['name' => 'view gendertranslations']);
        Permission::updateOrCreate(['name' => 'create gendertranslations']);
        Permission::updateOrCreate(['name' => 'update gendertranslations']);
        Permission::updateOrCreate(['name' => 'delete gendertranslations']);

        Permission::updateOrCreate(['name' => 'list goals']);
        Permission::updateOrCreate(['name' => 'view goals']);
        Permission::updateOrCreate(['name' => 'create goals']);
        Permission::updateOrCreate(['name' => 'update goals']);
        Permission::updateOrCreate(['name' => 'delete goals']);

        Permission::updateOrCreate(['name' => 'list goaltranslations']);
        Permission::updateOrCreate(['name' => 'view goaltranslations']);
        Permission::updateOrCreate(['name' => 'create goaltranslations']);
        Permission::updateOrCreate(['name' => 'update goaltranslations']);
        Permission::updateOrCreate(['name' => 'delete goaltranslations']);

        Permission::updateOrCreate(['name' => 'list inititives']);
        Permission::updateOrCreate(['name' => 'view inititives']);
        Permission::updateOrCreate(['name' => 'create inititives']);
        Permission::updateOrCreate(['name' => 'update inititives']);
        Permission::updateOrCreate(['name' => 'delete inititives']);

        Permission::updateOrCreate(['name' => 'list inititivetranslations']);
        Permission::updateOrCreate(['name' => 'view inititivetranslations']);
        Permission::updateOrCreate(['name' => 'create inititivetranslations']);
        Permission::updateOrCreate(['name' => 'update inititivetranslations']);
        Permission::updateOrCreate(['name' => 'delete inititivetranslations']);

        Permission::updateOrCreate(['name' => 'list keypeformanceindicators']);
        Permission::updateOrCreate(['name' => 'view keypeformanceindicators']);
        Permission::updateOrCreate(['name' => 'create keypeformanceindicators']);
        Permission::updateOrCreate(['name' => 'update keypeformanceindicators']);
        Permission::updateOrCreate(['name' => 'delete keypeformanceindicators']);

        Permission::updateOrCreate(['name' => 'list keypeformanceindicatorts']);
        Permission::updateOrCreate(['name' => 'view keypeformanceindicatorts']);
        Permission::updateOrCreate(['name' => 'create keypeformanceindicatorts']);
        Permission::updateOrCreate(['name' => 'update keypeformanceindicatorts']);
        Permission::updateOrCreate(['name' => 'delete keypeformanceindicatorts']);

        Permission::updateOrCreate(['name' => 'list languages']);
        Permission::updateOrCreate(['name' => 'view languages']);
        Permission::updateOrCreate(['name' => 'create languages']);
        Permission::updateOrCreate(['name' => 'update languages']);
        Permission::updateOrCreate(['name' => 'delete languages']);

        Permission::updateOrCreate(['name' => 'list objectives']);
        Permission::updateOrCreate(['name' => 'view objectives']);
        Permission::updateOrCreate(['name' => 'create objectives']);
        Permission::updateOrCreate(['name' => 'update objectives']);
        Permission::updateOrCreate(['name' => 'delete objectives']);

        Permission::updateOrCreate(['name' => 'list objectivetranslations']);
        Permission::updateOrCreate(['name' => 'view objectivetranslations']);
        Permission::updateOrCreate(['name' => 'create objectivetranslations']);
        Permission::updateOrCreate(['name' => 'update objectivetranslations']);
        Permission::updateOrCreate(['name' => 'delete objectivetranslations']);

        Permission::updateOrCreate(['name' => 'list offices']);
        Permission::updateOrCreate(['name' => 'view offices']);
        Permission::updateOrCreate(['name' => 'create offices']);
        Permission::updateOrCreate(['name' => 'update offices']);
        Permission::updateOrCreate(['name' => 'delete offices']);

        Permission::updateOrCreate(['name' => 'list officetranslations']);
        Permission::updateOrCreate(['name' => 'view officetranslations']);
        Permission::updateOrCreate(['name' => 'create officetranslations']);
        Permission::updateOrCreate(['name' => 'update officetranslations']);
        Permission::updateOrCreate(['name' => 'delete officetranslations']);

        Permission::updateOrCreate(['name' => 'list perspectives']);
        Permission::updateOrCreate(['name' => 'view perspectives']);
        Permission::updateOrCreate(['name' => 'create perspectives']);
        Permission::updateOrCreate(['name' => 'update perspectives']);
        Permission::updateOrCreate(['name' => 'delete perspectives']);

        Permission::updateOrCreate(['name' => 'list perspectivetranslations']);
        Permission::updateOrCreate(['name' => 'view perspectivetranslations']);
        Permission::updateOrCreate(['name' => 'create perspectivetranslations']);
        Permission::updateOrCreate(['name' => 'update perspectivetranslations']);
        Permission::updateOrCreate(['name' => 'delete perspectivetranslations']);

        Permission::updateOrCreate(['name' => 'list planaccomplishments']);
        Permission::updateOrCreate(['name' => 'view planaccomplishments']);
        Permission::updateOrCreate(['name' => 'create planaccomplishments']);
        Permission::updateOrCreate(['name' => 'update planaccomplishments']);
        Permission::updateOrCreate(['name' => 'delete planaccomplishments']);

        Permission::updateOrCreate(['name' => 'list planingyears']);
        Permission::updateOrCreate(['name' => 'view planingyears']);
        Permission::updateOrCreate(['name' => 'create planingyears']);
        Permission::updateOrCreate(['name' => 'update planingyears']);
        Permission::updateOrCreate(['name' => 'delete planingyears']);

        Permission::updateOrCreate(['name' => 'list planingyeartranslations']);
        Permission::updateOrCreate(['name' => 'view planingyeartranslations']);
        Permission::updateOrCreate(['name' => 'create planingyeartranslations']);
        Permission::updateOrCreate(['name' => 'update planingyeartranslations']);
        Permission::updateOrCreate(['name' => 'delete planingyeartranslations']);

        Permission::updateOrCreate(['name' => 'list reportingperiods']);
        Permission::updateOrCreate(['name' => 'view reportingperiods']);
        Permission::updateOrCreate(['name' => 'create reportingperiods']);
        Permission::updateOrCreate(['name' => 'update reportingperiods']);
        Permission::updateOrCreate(['name' => 'delete reportingperiods']);

        Permission::updateOrCreate(['name' => 'list reportingperiodts']);
        Permission::updateOrCreate(['name' => 'view reportingperiodts']);
        Permission::updateOrCreate(['name' => 'create reportingperiodts']);
        Permission::updateOrCreate(['name' => 'update reportingperiodts']);
        Permission::updateOrCreate(['name' => 'delete reportingperiodts']);

        Permission::updateOrCreate(['name' => 'list reportingperiodtypes']);
        Permission::updateOrCreate(['name' => 'view reportingperiodtypes']);
        Permission::updateOrCreate(['name' => 'create reportingperiodtypes']);
        Permission::updateOrCreate(['name' => 'update reportingperiodtypes']);
        Permission::updateOrCreate(['name' => 'delete reportingperiodtypes']);

        Permission::updateOrCreate(['name' => 'list reportingperiodtypets']);
        Permission::updateOrCreate(['name' => 'view reportingperiodtypets']);
        Permission::updateOrCreate(['name' => 'create reportingperiodtypets']);
        Permission::updateOrCreate(['name' => 'update reportingperiodtypets']);
        Permission::updateOrCreate(['name' => 'delete reportingperiodtypets']);

        Permission::updateOrCreate(['name' => 'list strategies']);
        Permission::updateOrCreate(['name' => 'view strategies']);
        Permission::updateOrCreate(['name' => 'create strategies']);
        Permission::updateOrCreate(['name' => 'update strategies']);
        Permission::updateOrCreate(['name' => 'delete strategies']);

        Permission::updateOrCreate(['name' => 'list strategytranslations']);
        Permission::updateOrCreate(['name' => 'view strategytranslations']);
        Permission::updateOrCreate(['name' => 'create strategytranslations']);
        Permission::updateOrCreate(['name' => 'update strategytranslations']);
        Permission::updateOrCreate(['name' => 'delete strategytranslations']);

        Permission::updateOrCreate(['name' => 'list suitablekpis']);
        Permission::updateOrCreate(['name' => 'view suitablekpis']);
        Permission::updateOrCreate(['name' => 'create suitablekpis']);
        Permission::updateOrCreate(['name' => 'update suitablekpis']);
        Permission::updateOrCreate(['name' => 'delete suitablekpis']);

        // Create user role and assign existing permissions
        $currentPermissions = Permission::all();
        $userRole = Role::updateOrCreate(['name' => 'staff']);
        $userRole->givePermissionTo($currentPermissions);

        // Create admin exclusive permissions
        Permission::updateOrCreate(['name' => 'list roles']);
        Permission::updateOrCreate(['name' => 'view roles']);
        Permission::updateOrCreate(['name' => 'create roles']);
        Permission::updateOrCreate(['name' => 'update roles']);
        Permission::updateOrCreate(['name' => 'delete roles']);

        Permission::updateOrCreate(['name' => 'list permissions']);
        Permission::updateOrCreate(['name' => 'view permissions']);
        Permission::updateOrCreate(['name' => 'create permissions']);
        Permission::updateOrCreate(['name' => 'update permissions']);
        Permission::updateOrCreate(['name' => 'delete permissions']);

        Permission::updateOrCreate(['name' => 'list users']);
        Permission::updateOrCreate(['name' => 'view users']);
        Permission::updateOrCreate(['name' => 'create users']);
        Permission::updateOrCreate(['name' => 'update users']);
        Permission::updateOrCreate(['name' => 'delete users']);

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::updateOrCreate(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $user = \App\Models\User::whereEmail('admin@admin')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }else{

            // Admin User Seeder
            $adminUser = User::create([
                'username' => 'admin',
                'name' => 'Super Admin',
                'email' => 'admin@admin',
                'is_admin'=> '1',
                'password' => bcrypt('admin')
            ]);

            $adminUser->assignRole($adminRole);

        }

        // Default System Language
        $englishLanguage = Language::updateOrCreate([
            'name' => 'English',
            'description' => 'English Language',
            'locale' => 'en',
        ]);

        // create imaginary office and assign admin
        $officeExists = Office::find(1);
        if($officeExists) {
            // nothing to do
        }else{
            $office = new Office;
            $office->parent_office_id = null;
            $office->level = 0;
            $office->created_at = new \DateTime();
            $office->updated_at = new \DateTime();
            $office->save();

            $office_translation = new OfficeTranslation;
            $office_translation->translation_id = $office->id;
            $office_translation->name = 'Jimma University';
            $office_translation->locale = 'en';
            $office_translation->description = 'This office will be used as a starting point to relate and create other sub offices. There is nothing to operate on this office.';
            $office_translation->save();

            $assignOfficeToAdmin = DB::insert('insert into manager (user_id, office_id) values (?, ?)', [$adminUser->id, $office->id]);
        }
    }
}
