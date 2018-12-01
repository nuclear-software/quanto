<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        app()['cache']->forget('spatie.permission.cache');

        $sections= [
            ['display_name' => 'Sections', 'icon' => 'glyphicon glyphicon-link', 'route' => 'sections.index', 'permission'=>'sections_read'],
            ['display_name' => 'Users', 'icon' => 'glyphicon glyphicon-link', 'route' => 'users.index', 'permission'=>'users_read'],
            ['display_name' => 'Roles', 'icon' => 'glyphicon glyphicon-link', 'route' => 'roles.index', 'permission'=>'roles_read'],
            ['display_name' => 'Permissions', 'icon' => 'glyphicon glyphicon-link', 'route' => 'permissions.index', 'permission'=>'permissions_read'],
            ['display_name' => 'Companies','icon' => 'glyphicon glyphicon-link','route' => 'companies.index','permission' => 'companies_read','created_at' => '2018-12-01 00:58:27','updated_at' => '2018-12-01 00:58:27','deleted_at' => NULL],
            ['display_name' => 'Memberships','icon' => 'glyphicon glyphicon-link','route' => 'memberships.index','permission' => 'memberships_read','created_at' => '2018-12-01 01:12:15','updated_at' => '2018-12-01 01:12:15','deleted_at' => NULL],
            ['display_name' => 'Establishments','icon' => 'glyphicon glyphicon-link','route' => 'establishments.index','permission' => 'establishments_read','created_at' => '2018-12-01 01:27:29','updated_at' => '2018-12-01 01:27:29','deleted_at' => NULL],
            ['display_name' => 'Establishment Tables','icon' => 'glyphicon glyphicon-link','route' => 'establishment_tables.index','permission' => 'establishment_tables_read','created_at' => '2018-12-01 01:34:13','updated_at' => '2018-12-01 01:34:13','deleted_at' => NULL],
            ['display_name' => 'Products','icon' => 'glyphicon glyphicon-link','route' => 'products.index','permission' => 'products_read','created_at' => '2018-12-01 01:51:39','updated_at' => '2018-12-01 01:51:39','deleted_at' => NULL],
            ['display_name' => 'Accounts','icon' => 'glyphicon glyphicon-link','route' => 'accounts.index','permission' => 'accounts_read','created_at' => '2018-12-01 02:07:09','updated_at' => '2018-12-01 02:07:09','deleted_at' => NULL],
            ['display_name' => 'Account Components','icon' => 'glyphicon glyphicon-link','route' => 'account_components.index','permission' => 'account_components_read','created_at' => '2018-12-01 02:14:01','updated_at' => '2018-12-01 02:14:01','deleted_at' => NULL],
            ['display_name' => 'Product References','icon' => 'glyphicon glyphicon-link','route' => 'product_references.index','permission' => 'product_references_read','created_at' => '2018-12-01 02:18:33','updated_at' => '2018-12-01 02:18:33','deleted_at' => NULL],
        ];

        DB::table('sections')->insert($sections);

        // create roles and assign created permissions
        $roles= ['super-admin','admin','app-user'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);   
        }

        //Permission::create(['name' => 'sections']);
        foreach ($sections as $key => $section) {
            Permission::create(['name' => str_replace('.index','',$section['route']).'_create']);
            Permission::create(['name' => str_replace('.index','',$section['route']).'_read']);
            Permission::create(['name' => str_replace('.index','',$section['route']).'_update']);
            Permission::create(['name' => str_replace('.index','',$section['route']).'_delete']);
        }

        $role = Role::findByName('super-admin')->givePermissionTo(Permission::all());

        $user= User::create(
            ['name'=>'Oscar', 'email'=>'oscar.parra@mixedmedia-ad.com', 'password'=> bcrypt('m1x3dm3d14')]
        );

        $user->assignRole('super-admin');

        $user= User::create(
            ['name'=>'admin', 'email'=>'admin@admin.com', 'password'=> bcrypt('123456')]
        );

        DB::table('oauth_clients')->insert([
            [
                'user_id'=> NULL,
                'name' => 'Laravel Personal Access Client',
                'secret'=> '0LGR2ekrgiFQ546Oj8Y1ZQoFoRqSBrrgl1mzRisa',
                'redirect'=> 'http://localhost',
                'personal_access_client'=> 1,
                'password_client'=> 0,
                'revoked'=> 0,
                'created_at'=> '2017-11-16 01:49:33',
                'updated_at'=> '2017-11-16 01:49:33'
            ],
            [
                'user_id'=> NULL,
                'name' => 'Laravel Password Grant Client',
                'secret'=> '9WhIBueAWM075CvovXw1Sv0kT1TLzZoT9dE6uz6n',
                'redirect'=> 'http://localhost',
                'personal_access_client'=> 0,
                'password_client'=> 1,
                'revoked'=> 0,
                'created_at'=> '2017-11-16 01:49:33',
                'updated_at'=> '2017-11-16 01:49:33'
            ]
        ]);
        DB::table('oauth_personal_access_clients')->insert([
            [
                'client_id'=> 1,
                'created_at'=> '2017-11-16 01:49:33',
                'updated_at'=> '2017-11-16 01:49:33'
            ]
        ]);
    }
}
