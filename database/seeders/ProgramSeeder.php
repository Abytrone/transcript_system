<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('code');

        $programs = [
            // Certificate programs (1 year)
            ['code' => 'CPH', 'name' => 'Certificate in Public Health', 'department_code' => 'PUB', 'level' => 'certificate', 'duration_years' => 1],
            ['code' => 'CHP', 'name' => 'Certificate in Health Promotion', 'department_code' => 'HPR', 'level' => 'certificate', 'duration_years' => 1],
            ['code' => 'CEH', 'name' => 'Certificate in Environmental Health', 'department_code' => 'ENV', 'level' => 'certificate', 'duration_years' => 1],
            ['code' => 'CNU', 'name' => 'Certificate in Nutrition', 'department_code' => 'NUT', 'level' => 'certificate', 'duration_years' => 1],

            // Diploma programs (2 years)
            ['code' => 'DEH', 'name' => 'Diploma in Environmental Health', 'department_code' => 'ENV', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'DHM', 'name' => 'Diploma in Health Information Management', 'department_code' => 'HIM', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'DEN', 'name' => 'Diploma in Epidemiology', 'department_code' => 'EPI', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'DPH', 'name' => 'Diploma in Public Health', 'department_code' => 'PUB', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'DND', 'name' => 'Diploma in Nutrition and Dietetics', 'department_code' => 'NUT', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'DHP', 'name' => 'Diploma in Health Promotion', 'department_code' => 'HPR', 'level' => 'diploma', 'duration_years' => 2],

            // Degree programs (3 years)
            ['code' => 'BWS', 'name' => 'BSc in Water and Sanitation', 'department_code' => 'WAS', 'level' => 'degree', 'duration_years' => 3],
            ['code' => 'BPH', 'name' => 'BSc in Public Health', 'department_code' => 'PUB', 'level' => 'degree', 'duration_years' => 3],
            ['code' => 'BND', 'name' => 'BSc in Nutrition and Dietetics', 'department_code' => 'NUT', 'level' => 'degree', 'duration_years' => 3],
            ['code' => 'BEH', 'name' => 'BSc in Environmental Health', 'department_code' => 'ENV', 'level' => 'degree', 'duration_years' => 3],
            ['code' => 'BHM', 'name' => 'BSc in Health Information Management', 'department_code' => 'HIM', 'level' => 'degree', 'duration_years' => 3],
        ];

        foreach ($programs as $data) {
            $department = $departments->get($data['department_code']);
            if (! $department) {
                continue;
            }

            Program::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'department_id' => $department->id,
                    'level' => $data['level'],
                    'duration_years' => $data['duration_years'],
                    'status' => 'active',
                ]
            );
        }
    }
}
