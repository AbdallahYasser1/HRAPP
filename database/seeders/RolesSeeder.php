<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'create_account_Admin',
            'create_account',
            'update_account',
            'create_holiday',
            'update_holiday',
            'view_holiday',
            'delete_holiday',
            'create_shift',
            'update_shift',
            'view_shift',
            'delete_shift',
            'Show_Wfh_Request',
            'Show_Mission_Request',
            'Show_Task_Request',
            'create_mission',
            'create_update_mission',
            'Show_Mission_Update_Request',
            'Create_Vacationday',
            'Delete_Vacationday',
            'Update_Vacationday',
            'View_Vacationday',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $role_admin =  Role::create(['name' => 'Admin']);

        $role_employee = Role::create(['name' => 'Normal']);
        $role_HR = Role::create(['name' => 'HR']);
        $role_Accountant = Role::create(['name' => 'Accountant']);

        $NormalPermissions = [
            // create user permission
            'view_holiday',
            'create_mission',
            'create_update_mission',
            'Show_Mission_Request'
        ];
        $HRPermissions = [
            'create_account',
            'update_account',
            'create_holiday',
            'update_holiday',
            'view_holiday',
            'delete_holiday',
            'create_shift',
            'update_shift',
            'view_shift',
            'delete_shift',
            'Show_Wfh_Request',
            'Show_Mission_Request',
            'Show_Mission_Update_Request',
            'Show_Task_Request',
            'Create_Vacationday',
            'Delete_Vacationday',
            'Update_Vacationday',
            'View_Vacationday',
        ];
        $AccountantPermissions = [

            'Show_Mission_Request',
            'Show_Mission_Update_Request'
        ];

        foreach ($NormalPermissions as $permission) {
            $role_employee->givePermissionTo($permission);
        }
        foreach ($HRPermissions as $permission) {
            $role_HR->givePermissionTo($permission);
        }

        foreach ($permissions as $permission) {
            $role_admin->givePermissionto($permission);
        }

        foreach ($AccountantPermissions as $permission) {
            $role_Accountant->givePermissionto($permission);
        }
    }
}
