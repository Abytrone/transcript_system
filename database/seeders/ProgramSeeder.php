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
            ['code' => 'DEH', 'name' => 'Diploma in Environmental Health', 'department_code' => 'ENV', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'CPH', 'name' => 'Certificate in Public Health', 'department_code' => 'PUB', 'level' => 'certificate', 'duration_years' => 1],
            ['code' => 'DHM', 'name' => 'Diploma in Health Information Management', 'department_code' => 'HIM', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'CHP', 'name' => 'Certificate in Health Promotion', 'department_code' => 'HPR', 'level' => 'certificate', 'duration_years' => 1],
            ['code' => 'BWS', 'name' => 'BSc in Water and Sanitation', 'department_code' => 'WAS', 'level' => 'degree', 'duration_years' => 4],
            ['code' => 'BPH', 'name' => 'BSc in Public Health', 'department_code' => 'PUB', 'level' => 'degree', 'duration_years' => 4],
            ['code' => 'DEN', 'name' => 'Diploma in Epidemiology', 'department_code' => 'EPI', 'level' => 'diploma', 'duration_years' => 2],
            ['code' => 'BND', 'name' => 'BSc in Nutrition and Dietetics', 'department_code' => 'NUT', 'level' => 'degree', 'duration_years' => 4],
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
