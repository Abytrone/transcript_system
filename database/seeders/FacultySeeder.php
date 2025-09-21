<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            [
                'name' => 'Faculty of Health Sciences',
                'code' => 'FHS',
                'description' => 'The Faculty of Health Sciences focuses on comprehensive health education and research.',
                'dean_name' => 'Dr. Sarah Mensah',
                'dean_email' => 'sarah.mensah@schoolofhygiene.edu.gh',
                'dean_phone' => '+233 24 123 4567',
                'status' => 'active',
            ],
            [
                'name' => 'Faculty of Environmental Health',
                'code' => 'FEH',
                'description' => 'Dedicated to environmental health education and sustainable practices.',
                'dean_name' => 'Prof. Kwame Asante',
                'dean_email' => 'kwame.asante@schoolofhygiene.edu.gh',
                'dean_phone' => '+233 24 234 5678',
                'status' => 'active',
            ],
            [
                'name' => 'Faculty of Public Health',
                'code' => 'FPH',
                'description' => 'Leading public health education and community health initiatives.',
                'dean_name' => 'Dr. Ama Osei',
                'dean_email' => 'ama.osei@schoolofhygiene.edu.gh',
                'dean_phone' => '+233 24 345 6789',
                'status' => 'active',
            ],
            [
                'name' => 'Faculty of Nutrition and Dietetics',
                'code' => 'FND',
                'description' => 'Promoting nutrition education and healthy dietary practices.',
                'dean_name' => 'Dr. Kofi Boateng',
                'dean_email' => 'kofi.boateng@schoolofhygiene.edu.gh',
                'dean_phone' => '+233 24 456 7890',
                'status' => 'active',
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
