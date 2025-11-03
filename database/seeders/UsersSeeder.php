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

        // Create faculty admins (4 users) - Using Northern Ghana names
        $facultyAdminData = [
            [
                'name' => 'Dr. Alhassan Mumuni',
                'email' => 'alhassan.mumuni@schoolofhygiene.edu.gh',
                'phone' => '+233 24 123 4567',
                'faculty_code' => 'FHS',
                'department_code' => 'HIM', // Assign to a department in their faculty
            ],
            [
                'name' => 'Prof. Abukari Salifu',
                'email' => 'abukari.salifu@schoolofhygiene.edu.gh',
                'phone' => '+233 24 234 5678',
                'faculty_code' => 'FEH',
                'department_code' => 'ENV',
            ],
            [
                'name' => 'Dr. Fuseina Yakubu',
                'email' => 'fuseina.yakubu@schoolofhygiene.edu.gh',
                'phone' => '+233 24 345 6789',
                'faculty_code' => 'FPH',
                'department_code' => 'PUB',
            ],
            [
                'name' => 'Dr. Ibrahim Seidu',
                'email' => 'ibrahim.seidu@schoolofhygiene.edu.gh',
                'phone' => '+233 24 456 7890',
                'faculty_code' => 'FND',
                'department_code' => 'NUT',
            ],
        ];

        foreach ($facultyAdminData as $data) {
            $faculty = $faculties->get($data['faculty_code']);
            $department = $departments->get($data['department_code']);

            if ($faculty) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'faculty_id' => $faculty->id,
                    'department_id' => $department?->id,
                    'program_id' => null, // Faculty admins oversee multiple programs
                    'status' => 'active',
                ]);
                $user->assignRole('faculty_admin');
            }
        }

        // Create department admins (7 users - one per department) - Using Northern Ghana names
        $departmentAdminData = [
            ['name' => 'Dr. Hamza Abdullah', 'email' => 'hamza.abdullah@schoolofhygiene.edu.gh', 'phone' => '+233 24 567 8901', 'department_code' => 'HIM', 'program_code' => 'DHM'],
            ['name' => 'Dr. Rahinatu Iddrisu', 'email' => 'rahinatu.iddrisu@schoolofhygiene.edu.gh', 'phone' => '+233 24 678 9012', 'department_code' => 'HPR', 'program_code' => 'DHP'],
            ['name' => 'Dr. Mohammed Sulemana', 'email' => 'mohammed.sulemana@schoolofhygiene.edu.gh', 'phone' => '+233 24 789 0123', 'department_code' => 'ENV', 'program_code' => 'DEH'],
            ['name' => 'Dr. Ayishetu Haruna', 'email' => 'ayishetu.haruna@schoolofhygiene.edu.gh', 'phone' => '+233 24 890 1234', 'department_code' => 'WAS', 'program_code' => 'BWS'],
            ['name' => 'Dr. Dawuda Issah', 'email' => 'dawuda.issah@schoolofhygiene.edu.gh', 'phone' => '+233 24 901 2345', 'department_code' => 'PUB', 'program_code' => 'DPH'],
            ['name' => 'Dr. Salamatu Nashiru', 'email' => 'salamatu.nashiru@schoolofhygiene.edu.gh', 'phone' => '+233 24 012 3456', 'department_code' => 'EPI', 'program_code' => 'DEN'],
            ['name' => 'Dr. Musah Alidu', 'email' => 'musah.alidu@schoolofhygiene.edu.gh', 'phone' => '+233 24 123 4568', 'department_code' => 'NUT', 'program_code' => 'DND'],
        ];

        foreach ($departmentAdminData as $data) {
            $department = $departments->get($data['department_code']);
            $program = $programs->get($data['program_code'] ?? null);

            if ($department) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'faculty_id' => $department->faculty_id,
                    'department_id' => $department->id,
                    'program_id' => $program?->id,
                    'status' => 'active',
                ]);
                $user->assignRole('department_admin');
            }
        }

        // Create lecturers (10 users) - Using Northern Ghana names and assign to courses
        $lecturerData = [
            // Health Information Management
            ['name' => 'Dr. Zakaria Abdul-Razak', 'email' => 'zakaria.razak@schoolofhygiene.edu.gh', 'phone' => '+233 24 234 5678', 'department_code' => 'HIM', 'program_code' => 'DHM'],
            ['name' => 'Dr. Fati Amadu', 'email' => 'fati.amadu@schoolofhygiene.edu.gh', 'phone' => '+233 24 245 6789', 'department_code' => 'HIM', 'program_code' => 'BHM'],

            // Public Health
            ['name' => 'Dr. Yahaya Masawudu', 'email' => 'yahaya.masawudu@schoolofhygiene.edu.gh', 'phone' => '+233 24 345 6789', 'department_code' => 'PUB', 'program_code' => 'DPH'],
            ['name' => 'Dr. Lariba Mohammed', 'email' => 'lariba.mohammed@schoolofhygiene.edu.gh', 'phone' => '+233 24 356 7890', 'department_code' => 'PUB', 'program_code' => 'BPH'],

            // Environmental Health
            ['name' => 'Dr. Sumani Bawa', 'email' => 'sumani.bawa@schoolofhygiene.edu.gh', 'phone' => '+233 24 456 7890', 'department_code' => 'ENV', 'program_code' => 'DEH'],
            ['name' => 'Dr. Asana Yakubu', 'email' => 'asana.yakubu@schoolofhygiene.edu.gh', 'phone' => '+233 24 467 8901', 'department_code' => 'ENV', 'program_code' => 'BEH'],

            // Nutrition and Dietetics
            ['name' => 'Dr. Mahama Rashid', 'email' => 'mahama.rashid@schoolofhygiene.edu.gh', 'phone' => '+233 24 567 8901', 'department_code' => 'NUT', 'program_code' => 'DND'],
            ['name' => 'Dr. Memuna Iddrisu', 'email' => 'memuna.iddrisu@schoolofhygiene.edu.gh', 'phone' => '+233 24 578 9012', 'department_code' => 'NUT', 'program_code' => 'BND'],

            // Water and Sanitation
            ['name' => 'Dr. Haruna Iddris', 'email' => 'haruna.iddris@schoolofhygiene.edu.gh', 'phone' => '+233 24 678 9012', 'department_code' => 'WAS', 'program_code' => 'BWS'],

            // Health Promotion
            ['name' => 'Dr. Ziblim Adam', 'email' => 'ziblim.adam@schoolofhygiene.edu.gh', 'phone' => '+233 24 689 0123', 'department_code' => 'HPR', 'program_code' => 'DHP'],

            // Epidemiology
            ['name' => 'Dr. Azuma Fatawu', 'email' => 'azuma.fatawu@schoolofhygiene.edu.gh', 'phone' => '+233 24 790 1234', 'department_code' => 'EPI', 'program_code' => 'DEN'],
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

                // Assign at least 3 courses from the same department/program
                $courses = Course::where('department_id', $department->id)
                    ->when($program, function ($query) use ($program) {
                        return $query->where('program_id', $program->id);
                    })
                    ->inRandomOrder()
                    ->limit(rand(3, 6)) // Between 3 and 6 courses
                    ->pluck('id');

                if ($courses->isEmpty()) {
                    // Fallback to department courses only
                    $courses = Course::where('department_id', $department->id)
                        ->inRandomOrder()
                        ->limit(rand(3, 6))
                        ->pluck('id');
                }

                if ($courses->isNotEmpty()) {
                    $lecturer->taughtCourses()->syncWithoutDetaching($courses);
                    $this->command->info("✓ Assigned {$courses->count()} courses to {$lecturer->name}");
                } else {
                    $this->command->warn("✗ No courses found for {$lecturer->name} in department {$department->code}");
                }
            }
        }

        $this->command->info("\n=== Users Seeding Summary ===");
        $this->command->info("Faculty Admins: 4");
        $this->command->info("Department Admins: 7");
        $lecturerCount = User::role('lecturer')->count();
        $lecturersWithCourses = User::role('lecturer')->has('taughtCourses')->count();
        $totalCoursesAssigned = User::role('lecturer')->withCount('taughtCourses')->get()->sum('taught_courses_count');
        $this->command->info("Lecturers: {$lecturerCount}");
        $this->command->info("Lecturers with courses: {$lecturersWithCourses}");
        $this->command->info("Total courses assigned: {$totalCoursesAssigned}");
    }
}
