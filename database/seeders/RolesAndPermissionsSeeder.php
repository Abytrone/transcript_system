<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'super_admin',
            'faculty_admin',
            'department_admin',
            'lecturer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Define resources for permissions
        $resources = [
            'course',
            'department',
            'faculty',
            'program',
            'role',
            'student',
            'transcript',
            'transcript::request',
            'user',
        ];

        // Define widget permissions
        $widgets = [
            'SystemStatsWidget',
            'QuickActionsWidget',
            'TranscriptRequestsChartWidget',
            'ComprehensiveAnalyticsWidget',
            'RequestStatusChartWidget',
            'RecentActivityWidget',
            'TranscriptStatusChartWidget',
            'FacultyStatsWidget',
            'DeliveryAnalyticsWidget',
            'DepartmentStatsWidget',
            'MonthlyAnalyticsWidget',
            'RecentActivityTableWidget',
            'PerformanceMetricsWidget',
            'TopDepartmentsWidget',
            'RecentTranscriptRequestsTableWidget',
            'RecentTranscriptsTableWidget',
        ];

        // Create resource permissions
        $permissionActions = [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ];

        $permissions = [];

        // Generate resource permissions
        foreach ($resources as $resource) {
            foreach ($permissionActions as $action) {
                $permissionName = "{$action}_{$resource}";
                $permissions[$permissionName] = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Generate widget permissions
        foreach ($widgets as $widget) {
            $permissionName = "widget_{$widget}";
            $permissions[$permissionName] = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $facultyAdminRole = Role::where('name', 'faculty_admin')->first();
        $departmentAdminRole = Role::where('name', 'department_admin')->first();
        $lecturerRole = Role::where('name', 'lecturer')->first();

        // Assign all permissions to super_admin
        if ($superAdminRole) {
            $superAdminRole->syncPermissions(Permission::all());
        }

        // Assign permissions to lecturer based on SQL data
        // Lecturer has: course permissions (1-12), program permissions (37-48), student permissions (55-58)
        if ($lecturerRole) {
            $lecturerPermissions = [
                // Course permissions (all 12 actions)
                'view_course',
                'view_any_course',
                'create_course',
                'update_course',
                'restore_course',
                'restore_any_course',
                'replicate_course',
                'reorder_course',
                'delete_course',
                'delete_any_course',
                'force_delete_course',
                'force_delete_any_course',
                // Program permissions (all 12 actions)
                'view_program',
                'view_any_program',
                'create_program',
                'update_program',
                'restore_program',
                'restore_any_program',
                'replicate_program',
                'reorder_program',
                'delete_program',
                'delete_any_program',
                'force_delete_program',
                'force_delete_any_program',
                // Student permissions (view, view_any, create, update only)
                'view_student',
                'view_any_student',
                'create_student',
                'update_student',
            ];

            $lecturerPermissionsToSync = Permission::whereIn('name', $lecturerPermissions)->get();
            if ($lecturerPermissionsToSync->isNotEmpty()) {
                $lecturerRole->syncPermissions($lecturerPermissionsToSync);
            }
        }

        // Note: Faculty admin and department admin permissions would be assigned
        // based on your specific business logic. For now, they have no explicit
        // permissions assigned (they might inherit from policies or other logic)

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Total roles: ' . Role::count());
        $this->command->info('Total permissions: ' . Permission::count());
    }
}

