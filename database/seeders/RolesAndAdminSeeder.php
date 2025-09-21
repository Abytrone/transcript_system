<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['super_admin', 'faculty_admin', 'department_admin', 'verifier'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('filicino'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Faculty Admin
        $facultyAdmin = User::firstOrCreate(
            ['email' => 'facultyadmin@schoolofhygiene.edu.gh'],
            [
                'name' => 'Faculty Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $facultyAdmin->assignRole('faculty_admin');

        // Create Department Admin
        $departmentAdmin = User::firstOrCreate(
            ['email' => 'deptadmin@schoolofhygiene.edu.gh'],
            [
                'name' => 'Department Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $departmentAdmin->assignRole('department_admin');

        // Create Verifier
        $verifier = User::firstOrCreate(
            ['email' => 'verifier@schoolofhygiene.edu.gh'],
            [
                'name' => 'Transcript Verifier',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $verifier->assignRole('verifier');
    }
}
