<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $programsByName = Program::all()->keyBy('name');
        $programAliasMap = [
            'Bachelor of Science in Water and Sanitation' => 'BSc in Water and Sanitation',
            'Bachelor of Science in Public Health' => 'BSc in Public Health',
            'Bachelor of Science in Nutrition and Dietetics' => 'BSc in Nutrition and Dietetics',
        ];

        $students = [
            [
                'student_id' => 'SOH2021001',
                'first_name' => 'Kwame',
                'last_name' => 'Asante',
                'middle_name' => 'Kofi',
                'email' => 'kwame.asante@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 111 1111',
                'date_of_birth' => '2000-05-15',
                'gender' => 'male',
                'nationality' => 'Ghanaian',
                'address' => '123 Tamale Street, Tamale, Northern Region',
                'department_code' => 'HIM',
                'program' => 'Diploma in Health Information Management',
                'year_of_admission' => 2021,
                'year_of_completion' => 2023,
                'status' => 'graduated',
            ],
            [
                'student_id' => 'SOH2021002',
                'first_name' => 'Ama',
                'last_name' => 'Osei',
                'middle_name' => 'Serwaa',
                'email' => 'ama.osei@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 222 2222',
                'date_of_birth' => '1999-08-22',
                'gender' => 'female',
                'nationality' => 'Ghanaian',
                'address' => '456 Bolgatanga Road, Bolgatanga, Upper East Region',
                'department_code' => 'HPR',
                'program' => 'Certificate in Health Promotion',
                'year_of_admission' => 2021,
                'year_of_completion' => 2022,
                'status' => 'graduated',
            ],
            [
                'student_id' => 'SOH2022001',
                'first_name' => 'Kofi',
                'last_name' => 'Mensah',
                'middle_name' => 'Nana',
                'email' => 'kofi.mensah@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 333 3333',
                'date_of_birth' => '2001-12-10',
                'gender' => 'male',
                'nationality' => 'Ghanaian',
                'address' => '789 Wa Avenue, Wa, Upper West Region',
                'department_code' => 'ENV',
                'program' => 'Diploma in Environmental Health',
                'year_of_admission' => 2022,
                'year_of_completion' => null,
                'status' => 'active',
            ],
            [
                'student_id' => 'SOH2022002',
                'first_name' => 'Akosua',
                'last_name' => 'Boateng',
                'middle_name' => 'Adwoa',
                'email' => 'akosua.boateng@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 444 4444',
                'date_of_birth' => '2000-03-18',
                'gender' => 'female',
                'nationality' => 'Ghanaian',
                'address' => '321 Kumasi Street, Kumasi, Ashanti Region',
                'department_code' => 'WAS',
                'program' => 'Bachelor of Science in Water and Sanitation',
                'year_of_admission' => 2022,
                'year_of_completion' => null,
                'status' => 'active',
            ],
            [
                'student_id' => 'SOH2023001',
                'first_name' => 'Samuel',
                'last_name' => 'Appiah',
                'middle_name' => 'Kwame',
                'email' => 'samuel.appiah@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 555 5555',
                'date_of_birth' => '2002-07-05',
                'gender' => 'male',
                'nationality' => 'Ghanaian',
                'address' => '654 Accra Road, Accra, Greater Accra Region',
                'department_code' => 'PUB',
                'program' => 'Bachelor of Science in Public Health',
                'year_of_admission' => 2023,
                'year_of_completion' => null,
                'status' => 'active',
            ],
            [
                'student_id' => 'SOH2023002',
                'first_name' => 'Mary',
                'last_name' => 'Agyemang',
                'middle_name' => 'Serwaa',
                'email' => 'mary.agyemang@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 666 6666',
                'date_of_birth' => '2001-11-30',
                'gender' => 'female',
                'nationality' => 'Ghanaian',
                'address' => '987 Cape Coast Street, Cape Coast, Central Region',
                'department_code' => 'EPI',
                'program' => 'Diploma in Epidemiology',
                'year_of_admission' => 2023,
                'year_of_completion' => null,
                'status' => 'active',
            ],
            [
                'student_id' => 'SOH2024001',
                'first_name' => 'Joseph',
                'last_name' => 'Bonsu',
                'middle_name' => 'Kofi',
                'email' => 'joseph.bonsu@student.schoolofhygiene.edu.gh',
                'phone' => '+233 24 777 7777',
                'date_of_birth' => '2003-01-12',
                'gender' => 'male',
                'nationality' => 'Ghanaian',
                'address' => '147 Takoradi Avenue, Takoradi, Western Region',
                'department_code' => 'NUT',
                'program' => 'Bachelor of Science in Nutrition and Dietetics',
                'year_of_admission' => 2024,
                'year_of_completion' => null,
                'status' => 'active',
            ],
        ];

        foreach ($students as $studentData) {
            $department = $departments->where('code', $studentData['department_code'])->first();
            if ($department) {
                $programName = $studentData['program'];
                $programLookupName = $programAliasMap[$programName] ?? $programName;
                $program = $programsByName->get($programLookupName);

                Student::create([
                    'student_id' => $studentData['student_id'],
                    'first_name' => $studentData['first_name'],
                    'last_name' => $studentData['last_name'],
                    'middle_name' => $studentData['middle_name'],
                    'email' => $studentData['email'],
                    'phone' => $studentData['phone'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'gender' => $studentData['gender'],
                    'nationality' => $studentData['nationality'],
                    'address' => $studentData['address'],
                    'department_id' => $department->id,
                    'program_id' => optional($program)->id,
                    'year_of_admission' => $studentData['year_of_admission'],
                    'year_of_completion' => $studentData['year_of_completion'],
                    'status' => $studentData['status'],
                ]);
            }
        }
    }
}
