<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('code');
        $programs = Program::all()->keyBy('name');

        // Unsplash profile image URLs (diverse professional photos)
        $malePhotos = [
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop',
        ];

        $femalePhotos = [
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop',
        ];

        $photoIndex = 0;

        $students = [
            // 2021 Admissions
            ['student_id' => 'SOH2021001', 'first_name' => 'Kwame', 'last_name' => 'Asante', 'middle_name' => 'Kofi', 'email' => 'kwame.asante@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 111 1111', 'date_of_birth' => '2000-05-15', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '123 Tamale Street, Tamale, Northern Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2021002', 'first_name' => 'Ama', 'last_name' => 'Osei', 'middle_name' => 'Serwaa', 'email' => 'ama.osei@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 222 2222', 'date_of_birth' => '1999-08-22', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '456 Bolgatanga Road, Bolgatanga, Upper East Region', 'department_code' => 'HPR', 'program' => 'Certificate in Health Promotion', 'year_of_admission' => 2021, 'year_of_completion' => 2022, 'status' => 'graduated'],
            ['student_id' => 'SOH2021003', 'first_name' => 'Kofi', 'last_name' => 'Mensah', 'middle_name' => 'Nana', 'email' => 'kofi.mensah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 333 3333', 'date_of_birth' => '2001-12-10', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '789 Wa Avenue, Wa, Upper West Region', 'department_code' => 'ENV', 'program' => 'Diploma in Environmental Health', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2021004', 'first_name' => 'Akosua', 'last_name' => 'Boateng', 'middle_name' => 'Adwoa', 'email' => 'akosua.boateng@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 444 4444', 'date_of_birth' => '2000-03-18', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '321 Kumasi Street, Kumasi, Ashanti Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2021, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2021005', 'first_name' => 'Samuel', 'last_name' => 'Appiah', 'middle_name' => 'Kwame', 'email' => 'samuel.appiah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 555 5555', 'date_of_birth' => '2002-07-05', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '654 Accra Road, Accra, Greater Accra Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2021, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2021006', 'first_name' => 'Mary', 'last_name' => 'Agyemang', 'middle_name' => 'Serwaa', 'email' => 'mary.agyemang@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 666 6666', 'date_of_birth' => '2001-11-30', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '987 Cape Coast Street, Cape Coast, Central Region', 'department_code' => 'EPI', 'program' => 'Diploma in Epidemiology', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],

            // 2022 Admissions
            ['student_id' => 'SOH2022001', 'first_name' => 'Joseph', 'last_name' => 'Bonsu', 'middle_name' => 'Kofi', 'email' => 'joseph.bonsu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 777 7777', 'date_of_birth' => '2003-01-12', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '147 Takoradi Avenue, Takoradi, Western Region', 'department_code' => 'NUT', 'program' => 'BSc in Nutrition and Dietetics', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022002', 'first_name' => 'Grace', 'last_name' => 'Adjei', 'middle_name' => 'Ama', 'email' => 'grace.adjei@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 888 8888', 'date_of_birth' => '2002-09-20', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '258 Sunyani Road, Sunyani, Bono Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022003', 'first_name' => 'Emmanuel', 'last_name' => 'Yeboah', 'middle_name' => 'Kofi', 'email' => 'emmanuel.yeboah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 999 9999', 'date_of_birth' => '2003-04-25', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '369 Ho Street, Ho, Volta Region', 'department_code' => 'ENV', 'program' => 'Diploma in Environmental Health', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022004', 'first_name' => 'Comfort', 'last_name' => 'Nyarko', 'middle_name' => 'Serwaa', 'email' => 'comfort.nyarko@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 101 1010', 'date_of_birth' => '2002-06-14', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '471 Koforidua Road, Koforidua, Eastern Region', 'department_code' => 'PUB', 'program' => 'Certificate in Public Health', 'year_of_admission' => 2022, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2022005', 'first_name' => 'Prince', 'last_name' => 'Owusu', 'middle_name' => 'Nana', 'email' => 'prince.owusu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 202 2020', 'date_of_birth' => '2003-02-28', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '582 Techiman Street, Techiman, Bono East Region', 'department_code' => 'HPR', 'program' => 'Diploma in Health Promotion', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022006', 'first_name' => 'Patience', 'last_name' => 'Asiedu', 'middle_name' => 'Adwoa', 'email' => 'patience.asiedu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 303 3030', 'date_of_birth' => '2002-10-08', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '693 Tamale Avenue, Tamale, Northern Region', 'department_code' => 'NUT', 'program' => 'Diploma in Nutrition and Dietetics', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],

            // 2023 Admissions
            ['student_id' => 'SOH2023001', 'first_name' => 'David', 'last_name' => 'Tetteh', 'middle_name' => 'Kofi', 'email' => 'david.tetteh@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 404 4040', 'date_of_birth' => '2004-05-17', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '741 Accra Road, Accra, Greater Accra Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023002', 'first_name' => 'Ruth', 'last_name' => 'Mensah', 'middle_name' => 'Serwaa', 'email' => 'ruth.mensah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 505 5050', 'date_of_birth' => '2003-12-03', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '852 Kumasi Street, Kumasi, Ashanti Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023003', 'first_name' => 'Benjamin', 'last_name' => 'Amoah', 'middle_name' => 'Nana', 'email' => 'benjamin.amoah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 606 6060', 'date_of_birth' => '2004-08-22', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '963 Cape Coast Road, Cape Coast, Central Region', 'department_code' => 'ENV', 'program' => 'Certificate in Environmental Health', 'year_of_admission' => 2023, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2023004', 'first_name' => 'Joyce', 'last_name' => 'Boateng', 'middle_name' => 'Ama', 'email' => 'joyce.boateng@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 707 7070', 'date_of_birth' => '2004-03-11', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '147 Tamale Street, Tamale, Northern Region', 'department_code' => 'EPI', 'program' => 'Diploma in Epidemiology', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023005', 'first_name' => 'Daniel', 'last_name' => 'Osei', 'middle_name' => 'Kwame', 'email' => 'daniel.osei@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 808 8080', 'date_of_birth' => '2004-07-29', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '258 Bolgatanga Road, Bolgatanga, Upper East Region', 'department_code' => 'HIM', 'program' => 'BSc in Health Information Management', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023006', 'first_name' => 'Sarah', 'last_name' => 'Asante', 'middle_name' => 'Adwoa', 'email' => 'sarah.asante@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 909 9090', 'date_of_birth' => '2004-01-16', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '369 Wa Avenue, Wa, Upper West Region', 'department_code' => 'NUT', 'program' => 'Certificate in Nutrition', 'year_of_admission' => 2023, 'year_of_completion' => 2024, 'status' => 'graduated'],

            // 2024 Admissions
            ['student_id' => 'SOH2024001', 'first_name' => 'Michael', 'last_name' => 'Appiah', 'middle_name' => 'Kofi', 'email' => 'michael.appiah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 010 1010', 'date_of_birth' => '2005-06-09', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '471 Kumasi Street, Kumasi, Ashanti Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024002', 'first_name' => 'Esther', 'last_name' => 'Yeboah', 'middle_name' => 'Serwaa', 'email' => 'esther.yeboah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 020 2020', 'date_of_birth' => '2005-11-25', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '582 Accra Road, Accra, Greater Accra Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024003', 'first_name' => 'Isaac', 'last_name' => 'Mensah', 'middle_name' => 'Nana', 'email' => 'isaac.mensah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 030 3030', 'date_of_birth' => '2005-04-07', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '693 Tamale Avenue, Tamale, Northern Region', 'department_code' => 'ENV', 'program' => 'BSc in Environmental Health', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024004', 'first_name' => 'Deborah', 'last_name' => 'Bonsu', 'middle_name' => 'Ama', 'email' => 'deborah.bonsu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 040 4040', 'date_of_birth' => '2005-09-13', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '741 Cape Coast Street, Cape Coast, Central Region', 'department_code' => 'HPR', 'program' => 'Certificate in Health Promotion', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024005', 'first_name' => 'Joshua', 'last_name' => 'Owusu', 'middle_name' => 'Kwame', 'email' => 'joshua.owusu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 050 5050', 'date_of_birth' => '2005-02-19', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '852 Takoradi Avenue, Takoradi, Western Region', 'department_code' => 'NUT', 'program' => 'BSc in Nutrition and Dietetics', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024006', 'first_name' => 'Priscilla', 'last_name' => 'Adjei', 'middle_name' => 'Adwoa', 'email' => 'priscilla.adjei@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 060 6060', 'date_of_birth' => '2005-08-31', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '963 Sunyani Road, Sunyani, Bono Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
        ];

        foreach ($students as $studentData) {
            $department = $departments->where('code', $studentData['department_code'])->first();
            if (!$department) {
                continue;
            }

            $programName = $studentData['program'];
            $program = $programs->get($programName);

            // Assign photo based on gender
            $photoUrl = null;
            if ($studentData['gender'] === 'male') {
                $photoUrl = $malePhotos[$photoIndex % count($malePhotos)];
            } else {
                $photoUrl = $femalePhotos[$photoIndex % count($femalePhotos)];
            }
            $photoIndex++;

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
                'program_id' => $program?->id,
                'year_of_admission' => $studentData['year_of_admission'],
                'year_of_completion' => $studentData['year_of_completion'],
                'status' => $studentData['status'],
                'photo_path' => $photoUrl,
            ]);
        }
    }
}
