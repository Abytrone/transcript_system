<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $programs = Program::all()->keyBy('department_id');

        $courses = [
            // Health Information Management
            [
                'code' => 'HIM101',
                'title' => 'Introduction to Health Information Systems',
                'description' => 'Fundamentals of health information management and systems.',
                'credits' => 3,
                'department_code' => 'HIM',
                'level' => 100,
                'semester' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'HIM201',
                'title' => 'Health Data Analytics',
                'description' => 'Analysis and interpretation of health data.',
                'credits' => 3,
                'department_code' => 'HIM',
                'level' => 200,
                'semester' => 1,
                'status' => 'active',
            ],

            // Health Promotion
            [
                'code' => 'HPR101',
                'title' => 'Health Promotion Principles',
                'description' => 'Basic principles of health promotion and education.',
                'credits' => 3,
                'department_code' => 'HPR',
                'level' => 100,
                'semester' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'HPR201',
                'title' => 'Community Health Programs',
                'description' => 'Design and implementation of community health programs.',
                'credits' => 3,
                'department_code' => 'HPR',
                'level' => 200,
                'semester' => 2,
                'status' => 'active',
            ],

            // Environmental Health
            [
                'code' => 'ENV101',
                'title' => 'Environmental Health Principles',
                'description' => 'Introduction to environmental health concepts.',
                'credits' => 3,
                'department_code' => 'ENV',
                'level' => 100,
                'semester' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'ENV201',
                'title' => 'Environmental Assessment',
                'description' => 'Methods for environmental health assessment.',
                'credits' => 3,
                'department_code' => 'ENV',
                'level' => 200,
                'semester' => 1,
                'status' => 'active',
            ],

            // Water and Sanitation
            [
                'code' => 'WAS101',
                'title' => 'Water Quality Management',
                'description' => 'Principles of water quality assessment and management.',
                'credits' => 3,
                'department_code' => 'WAS',
                'level' => 100,
                'semester' => 2,
                'status' => 'active',
            ],
            [
                'code' => 'WAS201',
                'title' => 'Sanitation Systems',
                'description' => 'Design and management of sanitation systems.',
                'credits' => 3,
                'department_code' => 'WAS',
                'level' => 200,
                'semester' => 2,
                'status' => 'active',
            ],

            // Public Health
            [
                'code' => 'PUB101',
                'title' => 'Introduction to Public Health',
                'description' => 'Fundamental concepts in public health.',
                'credits' => 3,
                'department_code' => 'PUB',
                'level' => 100,
                'semester' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'PUB201',
                'title' => 'Public Health Policy',
                'description' => 'Development and analysis of public health policies.',
                'credits' => 3,
                'department_code' => 'PUB',
                'level' => 200,
                'semester' => 1,
                'status' => 'active',
            ],

            // Epidemiology
            [
                'code' => 'EPI101',
                'title' => 'Introduction to Epidemiology',
                'description' => 'Basic epidemiological concepts and methods.',
                'credits' => 3,
                'department_code' => 'EPI',
                'level' => 100,
                'semester' => 2,
                'status' => 'active',
            ],
            [
                'code' => 'EPI201',
                'title' => 'Disease Surveillance',
                'description' => 'Methods and systems for disease surveillance.',
                'credits' => 3,
                'department_code' => 'EPI',
                'level' => 200,
                'semester' => 2,
                'status' => 'active',
            ],

            // Nutrition and Dietetics
            [
                'code' => 'NUT101',
                'title' => 'Introduction to Nutrition',
                'description' => 'Basic principles of human nutrition.',
                'credits' => 3,
                'department_code' => 'NUT',
                'level' => 100,
                'semester' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'NUT201',
                'title' => 'Clinical Nutrition',
                'description' => 'Nutritional therapy in clinical settings.',
                'credits' => 3,
                'department_code' => 'NUT',
                'level' => 200,
                'semester' => 1,
                'status' => 'active',
            ],
        ];

        foreach ($courses as $courseData) {
            $department = $departments->where('code', $courseData['department_code'])->first();
            if ($department) {
                $program = $programs->get($department->id);
                Course::create([
                    'code' => $courseData['code'],
                    'title' => $courseData['title'],
                    'description' => $courseData['description'],
                    'credits' => $courseData['credits'],
                    'department_id' => $department->id,
                    'program_id' => $program?->id,
                    'level' => $courseData['level'],
                    'semester' => $courseData['semester'],
                    'status' => $courseData['status'],
                ]);
            }
        }
    }
}
