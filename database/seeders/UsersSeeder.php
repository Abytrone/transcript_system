<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
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

		// Create a couple of lecturers and assign courses + org structure
		$lecturers = User::factory()->count(2)->create([
			'status' => 'active',
		]);
		foreach ($lecturers as $lecturer) {
			$lecturer->assignRole('lecturer');

			// Pick a department and its program
			$course = Course::inRandomOrder()->first();
			if ($course) {
				$departmentId = $course->department_id;
				$programId = $course->program_id;

				$lecturer->department_id = $departmentId;
				$lecturer->faculty_id = optional(Department::find($departmentId))->faculty_id;
				$lecturer->program_id = $programId;
				$lecturer->save();

				// Attach up to 3 courses from same department/program
				$courses = Course::where('department_id', $departmentId)
					->where('program_id', $programId)
					->inRandomOrder()
					->limit(3)
					->pluck('id');
				if ($courses->isNotEmpty()) {
					$lecturer->taughtCourses()->syncWithoutDetaching($courses);
				}
			}
		}
	}
}
