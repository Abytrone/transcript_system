<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('code');
        $faculties = Faculty::all()->keyBy('code');
        $programs = Program::all()->keyBy('code');

        // Create faculty admins (4 users)
        $facultyAdminData = [
            ['name' => 'Dr. Sarah Mensah', 'email' => 'sarah.mensah@schoolofhygiene.edu.gh', 'phone' => '+233 24 123 4567', 'faculty_code' => 'FHS'],
            ['name' => 'Prof. Kwame Asante', 'email' => 'kwame.asante@schoolofhygiene.edu.gh', 'phone' => '+233 24 234 5678', 'faculty_code' => 'FEH'],
            ['name' => 'Dr. Ama Osei', 'email' => 'ama.osei@schoolofhygiene.edu.gh', 'phone' => '+233 24 345 6789', 'faculty_code' => 'FPH'],
            ['name' => 'Dr. Kofi Boateng', 'email' => 'kofi.boateng@schoolofhygiene.edu.gh', 'phone' => '+233 24 456 7890', 'faculty_code' => 'FND'],
        ];

        foreach ($facultyAdminData as $data) {
            $faculty = $faculties->get($data['faculty_code']);
            if ($faculty) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'faculty_id' => $faculty->id,
                    'status' => 'active',
                ]);
                $user->assignRole('faculty_admin');
            }
        }

        // Create department admins (7 users - one per department, plus extras)
        $departmentAdminData = [
            ['name' => 'Dr. Grace Adjei', 'email' => 'grace.adjei@schoolofhygiene.edu.gh', 'phone' => '+233 24 567 8901', 'department_code' => 'HIM'],
            ['name' => 'Dr. Comfort Nyarko', 'email' => 'comfort.nyarko@schoolofhygiene.edu.gh', 'phone' => '+233 24 678 9012', 'department_code' => 'HPR'],
            ['name' => 'Dr. Samuel Owusu', 'email' => 'samuel.owusu@schoolofhygiene.edu.gh', 'phone' => '+233 24 789 0123', 'department_code' => 'ENV'],
            ['name' => 'Dr. Mary Agyemang', 'email' => 'mary.agyemang@schoolofhygiene.edu.gh', 'phone' => '+233 24 890 1234', 'department_code' => 'WAS'],
            ['name' => 'Dr. Joseph Appiah', 'email' => 'joseph.appiah@schoolofhygiene.edu.gh', 'phone' => '+233 24 901 2345', 'department_code' => 'PUB'],
            ['name' => 'Dr. Akosua Bonsu', 'email' => 'akosua.bonsu@schoolofhygiene.edu.gh', 'phone' => '+233 24 012 3456', 'department_code' => 'EPI'],
            ['name' => 'Dr. Patience Asiedu', 'email' => 'patience.asiedu@schoolofhygiene.edu.gh', 'phone' => '+233 24 123 4567', 'department_code' => 'NUT'],
        ];

        foreach ($departmentAdminData as $data) {
            $department = $departments->get($data['department_code']);
            if ($department) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'faculty_id' => $department->faculty_id,
                    'department_id' => $department->id,
                    'status' => 'active',
                ]);
                $user->assignRole('department_admin');
            }
        }

        // Create lecturers (5 users) - assign to courses
        $lecturerData = [
            ['name' => 'Dr. Emmanuel Yeboah', 'email' => 'emmanuel.yeboah@schoolofhygiene.edu.gh', 'phone' => '+233 24 234 5678', 'department_code' => 'HIM', 'program_code' => 'DHM'],
            ['name' => 'Dr. Joyce Mensah', 'email' => 'joyce.mensah@schoolofhygiene.edu.gh', 'phone' => '+233 24 345 6789', 'department_code' => 'PUB', 'program_code' => 'DPH'],
            ['name' => 'Dr. Benjamin Asante', 'email' => 'benjamin.asante@schoolofhygiene.edu.gh', 'phone' => '+233 24 456 7890', 'department_code' => 'ENV', 'program_code' => 'DEH'],
            ['name' => 'Dr. Ruth Osei', 'email' => 'ruth.osei@schoolofhygiene.edu.gh', 'phone' => '+233 24 567 8901', 'department_code' => 'NUT', 'program_code' => 'DND'],
            ['name' => 'Dr. Daniel Boateng', 'email' => 'daniel.boateng@schoolofhygiene.edu.gh', 'phone' => '+233 24 678 9012', 'department_code' => 'WAS', 'program_code' => 'BWS'],
        ];

        foreach ($lecturerData as $data) {
            $department = $departments->get($data['department_code']);
            $program = $programs->get($data['program_code'] ?? null);

            if ($department) {
                $lecturer = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'faculty_id' => $department->faculty_id,
                    'department_id' => $department->id,
                    'program_id' => $program?->id,
                    'status' => 'active',
                ]);
                $lecturer->assignRole('lecturer');

                // Assign 3-5 courses from the same department/program
                $courses = Course::where('department_id', $department->id)
                    ->when($program, function ($query) use ($program) {
                        return $query->where('program_id', $program->id);
                    })
                    ->inRandomOrder()
                    ->limit(rand(3, 5))
                    ->pluck('id');

                if ($courses->isNotEmpty()) {
                    $lecturer->taughtCourses()->syncWithoutDetaching($courses);
                }
            }
        }
    }
}
