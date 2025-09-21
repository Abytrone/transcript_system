<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Student::with(['department', 'program'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Student ID',
            'First Name',
            'Last Name',
            'Middle Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'Nationality',
            'Address',
            'Department',
            'Program',
            'Year of Admission',
            'Year of Completion',
            'Status',
            'Cumulative GPA',
            'Total Courses',
        ];
    }

    /**
     * @param Student $student
     * @return array
     */
    public function map($student): array
    {
        return [
            $student->student_id,
            $student->first_name,
            $student->last_name,
            $student->middle_name,
            $student->email,
            $student->phone,
            $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : null,
            $student->gender,
            $student->nationality,
            $student->address,
            $student->department?->name,
            $student->program?->name,
            $student->year_of_admission,
            $student->year_of_completion,
            $student->status,
            $student->getCumulativeGPA(),
            $student->getTotalCourses(),
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
            'A' => 15, // Student ID
            'B' => 15, // First Name
            'C' => 15, // Last Name
            'D' => 15, // Middle Name
            'E' => 25, // Email
            'F' => 15, // Phone
            'G' => 15, // Date of Birth
            'H' => 10, // Gender
            'I' => 15, // Nationality
            'J' => 30, // Address
            'K' => 20, // Department
            'L' => 25, // Program
            'M' => 15, // Year of Admission
            'N' => 15, // Year of Completion
            'O' => 10, // Status
            'P' => 15, // Cumulative GPA
            'Q' => 15, // Total Courses
        ];
    }
}
