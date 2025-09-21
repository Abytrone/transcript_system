<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Create additional faculty admins
		$facultyAdmins = User::factory()->count(3)->create([
			'status' => 'active',
		]);
		foreach ($facultyAdmins as $user) {
			$user->assignRole('faculty_admin');
		}

		// Create additional department admins
		$departmentAdmins = User::factory()->count(5)->create([
			'status' => 'active',
		]);
		foreach ($departmentAdmins as $user) {
			$user->assignRole('department_admin');
		}

		// Create additional verifiers
		$verifiers = User::factory()->count(2)->create([
			'status' => 'active',
		]);
		foreach ($verifiers as $user) {
			$user->assignRole('verifier');
		}
	}
}
