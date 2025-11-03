<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('code');
        $programs = Program::all()->keyBy('code');

        $courses = [
            // Health Information Management (HIM) - Multiple programs
            ['code' => 'HIM101', 'title' => 'Introduction to Health Information Systems', 'description' => 'Fundamentals of health information management and systems.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'DHM', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'HIM102', 'title' => 'Medical Terminology', 'description' => 'Understanding medical language and terminology.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'DHM', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'HIM103', 'title' => 'Health Records Management', 'description' => 'Management and organization of health records.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'DHM', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'HIM201', 'title' => 'Health Data Analytics', 'description' => 'Analysis and interpretation of health data.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'DHM', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'HIM202', 'title' => 'Healthcare Information Technology', 'description' => 'IT systems in healthcare settings.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'DHM', 'level' => 200, 'semester' => 2, 'status' => 'active'],
            ['code' => 'HIM301', 'title' => 'Advanced Health Information Systems', 'description' => 'Advanced concepts in health information management.', 'credits' => 3, 'department_code' => 'HIM', 'program_code' => 'BHM', 'level' => 300, 'semester' => 1, 'status' => 'active'],

            // Health Promotion (HPR)
            ['code' => 'HPR101', 'title' => 'Health Promotion Principles', 'description' => 'Basic principles of health promotion and education.', 'credits' => 3, 'department_code' => 'HPR', 'program_code' => 'CHP', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'HPR102', 'title' => 'Health Communication', 'description' => 'Effective communication in health promotion.', 'credits' => 3, 'department_code' => 'HPR', 'program_code' => 'CHP', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'HPR201', 'title' => 'Community Health Programs', 'description' => 'Design and implementation of community health programs.', 'credits' => 3, 'department_code' => 'HPR', 'program_code' => 'DHP', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'HPR202', 'title' => 'Health Behavior Change', 'description' => 'Theories and models of health behavior change.', 'credits' => 3, 'department_code' => 'HPR', 'program_code' => 'DHP', 'level' => 200, 'semester' => 2, 'status' => 'active'],

            // Environmental Health (ENV)
            ['code' => 'ENV101', 'title' => 'Environmental Health Principles', 'description' => 'Introduction to environmental health concepts.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'CEH', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'ENV102', 'title' => 'Environmental Pollution', 'description' => 'Types and sources of environmental pollution.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'CEH', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'ENV201', 'title' => 'Environmental Assessment', 'description' => 'Methods for environmental health assessment.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'DEH', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'ENV202', 'title' => 'Waste Management', 'description' => 'Principles and practices of waste management.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'DEH', 'level' => 200, 'semester' => 2, 'status' => 'active'],
            ['code' => 'ENV301', 'title' => 'Environmental Policy and Legislation', 'description' => 'Environmental laws and regulatory frameworks.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'BEH', 'level' => 300, 'semester' => 1, 'status' => 'active'],
            ['code' => 'ENV302', 'title' => 'Climate Change and Health', 'description' => 'Impact of climate change on public health.', 'credits' => 3, 'department_code' => 'ENV', 'program_code' => 'BEH', 'level' => 300, 'semester' => 2, 'status' => 'active'],

            // Water and Sanitation (WAS)
            ['code' => 'WAS101', 'title' => 'Water Quality Management', 'description' => 'Principles of water quality assessment and management.', 'credits' => 3, 'department_code' => 'WAS', 'program_code' => 'BWS', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'WAS102', 'title' => 'Introduction to Sanitation', 'description' => 'Basic sanitation concepts and practices.', 'credits' => 3, 'department_code' => 'WAS', 'program_code' => 'BWS', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'WAS201', 'title' => 'Sanitation Systems', 'description' => 'Design and management of sanitation systems.', 'credits' => 3, 'department_code' => 'WAS', 'program_code' => 'BWS', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'WAS202', 'title' => 'Water Treatment Technologies', 'description' => 'Methods and technologies for water treatment.', 'credits' => 3, 'department_code' => 'WAS', 'program_code' => 'BWS', 'level' => 200, 'semester' => 2, 'status' => 'active'],
            ['code' => 'WAS301', 'title' => 'Advanced Water Management', 'description' => 'Advanced concepts in water resource management.', 'credits' => 3, 'department_code' => 'WAS', 'program_code' => 'BWS', 'level' => 300, 'semester' => 1, 'status' => 'active'],

            // Public Health (PUB)
            ['code' => 'PUB101', 'title' => 'Introduction to Public Health', 'description' => 'Fundamental concepts in public health.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'CPH', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'PUB102', 'title' => 'Health Determinants', 'description' => 'Factors affecting population health.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'CPH', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'PUB201', 'title' => 'Public Health Policy', 'description' => 'Development and analysis of public health policies.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'DPH', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'PUB202', 'title' => 'Health Systems Management', 'description' => 'Management of health systems and services.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'DPH', 'level' => 200, 'semester' => 2, 'status' => 'active'],
            ['code' => 'PUB301', 'title' => 'Global Health', 'description' => 'International health issues and challenges.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'BPH', 'level' => 300, 'semester' => 1, 'status' => 'active'],
            ['code' => 'PUB302', 'title' => 'Health Program Planning and Evaluation', 'description' => 'Planning and evaluating public health programs.', 'credits' => 3, 'department_code' => 'PUB', 'program_code' => 'BPH', 'level' => 300, 'semester' => 2, 'status' => 'active'],

            // Epidemiology (EPI)
            ['code' => 'EPI101', 'title' => 'Introduction to Epidemiology', 'description' => 'Basic epidemiological concepts and methods.', 'credits' => 3, 'department_code' => 'EPI', 'program_code' => 'DEN', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'EPI102', 'title' => 'Health Statistics', 'description' => 'Statistical methods in health research.', 'credits' => 3, 'department_code' => 'EPI', 'program_code' => 'DEN', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'EPI201', 'title' => 'Disease Surveillance', 'description' => 'Methods and systems for disease surveillance.', 'credits' => 3, 'department_code' => 'EPI', 'program_code' => 'DEN', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'EPI202', 'title' => 'Epidemiological Research Methods', 'description' => 'Research methodologies in epidemiology.', 'credits' => 3, 'department_code' => 'EPI', 'program_code' => 'DEN', 'level' => 200, 'semester' => 2, 'status' => 'active'],

            // Nutrition and Dietetics (NUT)
            ['code' => 'NUT101', 'title' => 'Introduction to Nutrition', 'description' => 'Basic principles of human nutrition.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'CNU', 'level' => 100, 'semester' => 1, 'status' => 'active'],
            ['code' => 'NUT102', 'title' => 'Food Science', 'description' => 'Science of food composition and processing.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'CNU', 'level' => 100, 'semester' => 2, 'status' => 'active'],
            ['code' => 'NUT201', 'title' => 'Clinical Nutrition', 'description' => 'Nutritional therapy in clinical settings.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'DND', 'level' => 200, 'semester' => 1, 'status' => 'active'],
            ['code' => 'NUT202', 'title' => 'Community Nutrition', 'description' => 'Nutrition programs for communities.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'DND', 'level' => 200, 'semester' => 2, 'status' => 'active'],
            ['code' => 'NUT301', 'title' => 'Advanced Nutrition Therapy', 'description' => 'Advanced nutritional interventions.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'BND', 'level' => 300, 'semester' => 1, 'status' => 'active'],
            ['code' => 'NUT302', 'title' => 'Nutrition Research', 'description' => 'Research methods in nutrition science.', 'credits' => 3, 'department_code' => 'NUT', 'program_code' => 'BND', 'level' => 300, 'semester' => 2, 'status' => 'active'],
        ];

        $createdCount = 0;
        foreach ($courses as $courseData) {
            $department = $departments->get($courseData['department_code']);
            $program = $programs->get($courseData['program_code'] ?? null);

            if ($department) {
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
                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} courses");
    }
}
