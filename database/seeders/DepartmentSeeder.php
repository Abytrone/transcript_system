<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyHealthSciences = Faculty::where('code', 'FHS')->first();
        $facultyEnvironmentalHealth = Faculty::where('code', 'FEH')->first();
        $facultyPublicHealth = Faculty::where('code', 'FPH')->first();
        $facultyNutrition = Faculty::where('code', 'FND')->first();

        $departments = [
            // Faculty of Health Sciences
            [
                'name' => 'Health Information Management',
                'code' => 'HIM',
                'description' => 'Managing health information systems and data analytics.',
                'faculty_id' => $facultyHealthSciences->id,
                'head_name' => 'Dr. Grace Adjei',
                'head_email' => 'grace.adjei@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 567 8901',
                'status' => 'active',
            ],
            [
                'name' => 'Health Promotion',
                'code' => 'HPR',
                'description' => 'Promoting health awareness and community health programs.',
                'faculty_id' => $facultyHealthSciences->id,
                'head_name' => 'Dr. Comfort Nyarko',
                'head_email' => 'comfort.nyarko@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 678 9012',
                'status' => 'active',
            ],

            // Faculty of Environmental Health
            [
                'name' => 'Environmental Health',
                'code' => 'ENV',
                'description' => 'Environmental health assessment and management.',
                'faculty_id' => $facultyEnvironmentalHealth->id,
                'head_name' => 'Dr. Samuel Owusu',
                'head_email' => 'samuel.owusu@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 789 0123',
                'status' => 'active',
            ],
            [
                'name' => 'Water and Sanitation',
                'code' => 'WAS',
                'description' => 'Water quality management and sanitation practices.',
                'faculty_id' => $facultyEnvironmentalHealth->id,
                'head_name' => 'Dr. Mary Agyemang',
                'head_email' => 'mary.agyemang@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 890 1234',
                'status' => 'active',
            ],

            // Faculty of Public Health
            [
                'name' => 'Public Health',
                'code' => 'PUB',
                'description' => 'Public health policy and community health management.',
                'faculty_id' => $facultyPublicHealth->id,
                'head_name' => 'Dr. Joseph Appiah',
                'head_email' => 'joseph.appiah@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 901 2345',
                'status' => 'active',
            ],
            [
                'name' => 'Epidemiology',
                'code' => 'EPI',
                'description' => 'Disease surveillance and epidemiological research.',
                'faculty_id' => $facultyPublicHealth->id,
                'head_name' => 'Dr. Akosua Bonsu',
                'head_email' => 'akosua.bonsu@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 012 3456',
                'status' => 'active',
            ],

            // Faculty of Nutrition and Dietetics
            [
                'name' => 'Nutrition and Dietetics',
                'code' => 'NUT',
                'description' => 'Nutritional science and dietary therapy.',
                'faculty_id' => $facultyNutrition->id,
                'head_name' => 'Dr. Patience Asiedu',
                'head_email' => 'patience.asiedu@schoolofhygiene.edu.gh',
                'head_phone' => '+233 24 123 4567',
                'status' => 'active',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
