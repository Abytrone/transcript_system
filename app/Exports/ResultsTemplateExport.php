<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultsTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            // Sample data row
            [
                'STU001',           // student_id
                'MATH101',          // course_code
                85,                 // score
                2023,               // academic_year
                1,                  // semester
                'no',               // is_resit
            ],
            [
                'STU001',           // student_id
                'ENG101',           // course_code
                78,                 // score
                2023,               // academic_year
                1,                  // semester
                'no',               // is_resit
            ],
            [
                'STU002',           // student_id
                'MATH101',          // course_code
                92,                 // score
                2023,               // academic_year
                1,                  // semester
                'no',               // is_resit
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'student_id',
            'course_code',
            'score',
            'academic_year',
            'semester',
            'is_resit',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // student_id
            'B' => 15, // course_code
            'C' => 10, // score
            'D' => 15, // academic_year
            'E' => 10, // semester
            'F' => 10, // is_resit
        ];
    }
}
