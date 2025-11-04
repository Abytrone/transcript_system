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
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1463453091185-61582044d556?w=400&h=400&fit=crop',
        ];

        $femalePhotos = [
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400&h=400&fit=crop',
        ];

        $photoIndex = 0;

        $students = [
            // 2021 Admissions - Using Northern Ghana names
            ['student_id' => 'SOH2021001', 'first_name' => 'Alhassan', 'last_name' => 'Mumuni', 'middle_name' => 'Abdul', 'email' => 'alhassan.mumuni@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 111 1111', 'date_of_birth' => '2000-05-15', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '123 Tamale Central, Tamale, Northern Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2021002', 'first_name' => 'Fuseina', 'last_name' => 'Yakubu', 'middle_name' => 'Amina', 'email' => 'fuseina.yakubu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 222 2222', 'date_of_birth' => '1999-08-22', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '456 Bolgatanga Township, Bolgatanga, Upper East Region', 'department_code' => 'HPR', 'program' => 'Certificate in Health Promotion', 'year_of_admission' => 2021, 'year_of_completion' => 2022, 'status' => 'graduated'],
            ['student_id' => 'SOH2021003', 'first_name' => 'Abukari', 'last_name' => 'Salifu', 'middle_name' => 'Issah', 'email' => 'abukari.salifu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 333 3333', 'date_of_birth' => '2001-12-10', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '789 Wa Central, Wa, Upper West Region', 'department_code' => 'ENV', 'program' => 'Diploma in Environmental Health', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2021004', 'first_name' => 'Rahinatu', 'last_name' => 'Iddrisu', 'middle_name' => 'Ayisha', 'email' => 'rahinatu.iddrisu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 444 4444', 'date_of_birth' => '2000-03-18', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '321 Yendi Road, Yendi, Northern Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2021, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2021005', 'first_name' => 'Ibrahim', 'last_name' => 'Seidu', 'middle_name' => 'Alhassan', 'email' => 'ibrahim.seidu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 555 5555', 'date_of_birth' => '2002-07-05', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '654 Savelugu Street, Savelugu, Northern Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2021, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2021006', 'first_name' => 'Ayishetu', 'last_name' => 'Haruna', 'middle_name' => 'Zenabu', 'email' => 'ayishetu.haruna@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 666 6666', 'date_of_birth' => '2001-11-30', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '987 Navrongo Road, Navrongo, Upper East Region', 'department_code' => 'EPI', 'program' => 'Diploma in Epidemiology', 'year_of_admission' => 2021, 'year_of_completion' => 2023, 'status' => 'graduated'],

            // 2022 Admissions
            ['student_id' => 'SOH2022001', 'first_name' => 'Dawuda', 'last_name' => 'Issah', 'middle_name' => 'Mohammed', 'email' => 'dawuda.issah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 777 7777', 'date_of_birth' => '2003-01-12', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '147 Gushegu Road, Gushegu, Northern Region', 'department_code' => 'NUT', 'program' => 'BSc in Nutrition and Dietetics', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022002', 'first_name' => 'Salamatu', 'last_name' => 'Nashiru', 'middle_name' => 'Fati', 'email' => 'salamatu.nashiru@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 888 8888', 'date_of_birth' => '2002-09-20', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '258 Damongo Street, Damongo, Savannah Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022003', 'first_name' => 'Musah', 'last_name' => 'Alidu', 'middle_name' => 'Abdul', 'email' => 'musah.alidu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 999 9999', 'date_of_birth' => '2003-04-25', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '369 Bawku Street, Bawku, Upper East Region', 'department_code' => 'ENV', 'program' => 'Diploma in Environmental Health', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022004', 'first_name' => 'Asana', 'last_name' => 'Yakubu', 'middle_name' => 'Memuna', 'email' => 'asana.yakubu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 101 1010', 'date_of_birth' => '2002-06-14', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '471 Walewale Road, Walewale, Northern Region', 'department_code' => 'PUB', 'program' => 'Certificate in Public Health', 'year_of_admission' => 2022, 'year_of_completion' => 2023, 'status' => 'graduated'],
            ['student_id' => 'SOH2022005', 'first_name' => 'Zakaria', 'last_name' => 'Abdul-Razak', 'middle_name' => 'Salifu', 'email' => 'zakaria.razak@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 202 2020', 'date_of_birth' => '2003-02-28', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '582 Salaga Street, Salaga, Savannah Region', 'department_code' => 'HPR', 'program' => 'Diploma in Health Promotion', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2022006', 'first_name' => 'Lariba', 'last_name' => 'Mohammed', 'middle_name' => 'Rahinatu', 'email' => 'lariba.mohammed@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 303 3030', 'date_of_birth' => '2002-10-08', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '693 Kumbungu Road, Kumbungu, Northern Region', 'department_code' => 'NUT', 'program' => 'Diploma in Nutrition and Dietetics', 'year_of_admission' => 2022, 'year_of_completion' => null, 'status' => 'active'],

            // 2023 Admissions
            ['student_id' => 'SOH2023001', 'first_name' => 'Sumani', 'last_name' => 'Bawa', 'middle_name' => 'Ibrahim', 'email' => 'sumani.bawa@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 404 4040', 'date_of_birth' => '2004-05-17', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '741 Karaga Road, Karaga, Northern Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023002', 'first_name' => 'Memuna', 'last_name' => 'Iddrisu', 'middle_name' => 'Asana', 'email' => 'memuna.iddrisu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 505 5050', 'date_of_birth' => '2003-12-03', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '852 Gambaga Street, Gambaga, Northern Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023003', 'first_name' => 'Mahama', 'last_name' => 'Rashid', 'middle_name' => 'Alhassan', 'email' => 'mahama.rashid@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 606 6060', 'date_of_birth' => '2004-08-22', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '963 Nalerigu Road, Nalerigu, Northern Region', 'department_code' => 'ENV', 'program' => 'Certificate in Environmental Health', 'year_of_admission' => 2023, 'year_of_completion' => 2024, 'status' => 'graduated'],
            ['student_id' => 'SOH2023004', 'first_name' => 'Fati', 'last_name' => 'Amadu', 'middle_name' => 'Lariba', 'email' => 'fati.amadu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 707 7070', 'date_of_birth' => '2004-03-11', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '147 Saboba Road, Saboba, Northern Region', 'department_code' => 'EPI', 'program' => 'Diploma in Epidemiology', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023005', 'first_name' => 'Haruna', 'last_name' => 'Iddris', 'middle_name' => 'Dawuda', 'email' => 'haruna.iddris@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 808 8080', 'date_of_birth' => '2004-07-29', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '258 Bimbilla Street, Bimbilla, Northern Region', 'department_code' => 'HIM', 'program' => 'BSc in Health Information Management', 'year_of_admission' => 2023, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2023006', 'first_name' => 'Ziblim', 'last_name' => 'Adam', 'middle_name' => 'Serwaa', 'email' => 'ziblim.adam@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 909 9090', 'date_of_birth' => '2004-01-16', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '369 Wulensi Road, Wulensi, Northern Region', 'department_code' => 'NUT', 'program' => 'Certificate in Nutrition', 'year_of_admission' => 2023, 'year_of_completion' => 2024, 'status' => 'graduated'],

            // 2024 Admissions
            ['student_id' => 'SOH2024001', 'first_name' => 'Yahaya', 'last_name' => 'Masawudu', 'middle_name' => 'Ibrahim', 'email' => 'yahaya.masawudu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 010 1010', 'date_of_birth' => '2005-06-09', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '471 Sang Road, Sang, Northern Region', 'department_code' => 'PUB', 'program' => 'BSc in Public Health', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024002', 'first_name' => 'Azuma', 'last_name' => 'Fatawu', 'middle_name' => 'Serwaa', 'email' => 'azuma.fatawu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 020 2020', 'date_of_birth' => '2005-11-25', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '582 Kpandai Street, Kpandai, Northern Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024003', 'first_name' => 'Hamza', 'last_name' => 'Abdullah', 'middle_name' => 'Musah', 'email' => 'hamza.abdullah@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 030 3030', 'date_of_birth' => '2005-04-07', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '693 Chereponi Road, Chereponi, Northern Region', 'department_code' => 'ENV', 'program' => 'BSc in Environmental Health', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024004', 'first_name' => 'Zenabu', 'last_name' => 'Sulemana', 'middle_name' => 'Ama', 'email' => 'zenabu.sulemana@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 040 4040', 'date_of_birth' => '2005-09-13', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '741 Tolon Street, Tolon, Northern Region', 'department_code' => 'HPR', 'program' => 'Certificate in Health Promotion', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024005', 'first_name' => 'Salifu', 'last_name' => 'Yakubu', 'middle_name' => 'Kwame', 'email' => 'salifu.yakubu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 050 5050', 'date_of_birth' => '2005-02-19', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '852 Kpasenkpe Road, Kpasenkpe, Northern Region', 'department_code' => 'NUT', 'program' => 'BSc in Nutrition and Dietetics', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024006', 'first_name' => 'Amina', 'last_name' => 'Iddrisu', 'middle_name' => 'Adwoa', 'email' => 'amina.iddrisu@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 060 6060', 'date_of_birth' => '2005-08-31', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '963 Zabzugu Street, Zabzugu, Northern Region', 'department_code' => 'HIM', 'program' => 'Diploma in Health Information Management', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],

            // Additional students for more data
            ['student_id' => 'SOH2024007', 'first_name' => 'Issah', 'last_name' => 'Banda', 'middle_name' => 'Alhassan', 'email' => 'issah.banda@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 070 7070', 'date_of_birth' => '2005-03-14', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '147 Tatale Road, Tatale, Northern Region', 'department_code' => 'EPI', 'program' => 'Diploma in Epidemiology', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024008', 'first_name' => 'Hajia', 'last_name' => 'Sulemana', 'middle_name' => 'Fati', 'email' => 'hajia.sulemana@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 080 8080', 'date_of_birth' => '2005-07-21', 'gender' => 'female', 'nationality' => 'Ghanaian', 'address' => '258 Yagaba Street, Yagaba, Northern Region', 'department_code' => 'HPR', 'program' => 'Diploma in Health Promotion', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
            ['student_id' => 'SOH2024009', 'first_name' => 'Sulemana', 'last_name' => 'Yahaya', 'middle_name' => 'Abukari', 'email' => 'sulemana.yahaya@student.schoolofhygiene.edu.gh', 'phone' => '+233 24 090 9090', 'date_of_birth' => '2005-05-03', 'gender' => 'male', 'nationality' => 'Ghanaian', 'address' => '369 Sabari Road, Sabari, Northern Region', 'department_code' => 'WAS', 'program' => 'BSc in Water and Sanitation', 'year_of_admission' => 2024, 'year_of_completion' => null, 'status' => 'active'],
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

        $this->command->info("Created " . count($students) . " students");
    }
}
