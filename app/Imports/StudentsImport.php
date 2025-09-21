<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Department;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find department by name
        $department = Department::where('name', $row['department'])->first();
        if (!$department) {
            throw new \Exception("Department '{$row['department']}' not found. Please ensure the department exists in the system.");
        }

        // Find program by name
        $program = Program::where('name', $row['program'])->first();
        if (!$program) {
            throw new \Exception("Program '{$row['program']}' not found. Please ensure the program exists in the system.");
        }

        return new Student([
            'student_id' => strtoupper(trim($row['student_id'])),
            'first_name' => trim($row['first_name']),
            'last_name' => trim($row['last_name']),
            'middle_name' => trim($row['middle_name'] ?? ''),
            'email' => trim($row['email']),
            'phone' => trim($row['phone'] ?? ''),
            'date_of_birth' => $row['date_of_birth'] ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
            'gender' => trim($row['gender'] ?? ''),
            'nationality' => trim($row['nationality'] ?? ''),
            'address' => trim($row['address'] ?? ''),
            'department_id' => $department->id,
            'program_id' => $program->id,
            'year_of_admission' => (int) $row['year_of_admission'],
            'year_of_completion' => $row['year_of_completion'] ? (int) $row['year_of_completion'] : null,
            'status' => trim($row['status'] ?? 'active'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'string', 'max:255', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'department' => ['required', 'string', 'exists:departments,name'],
            'program' => ['required', 'string', 'exists:programs,name'],
            'year_of_admission' => ['required', 'integer', 'min:2000', 'max:2030'],
            'year_of_completion' => ['nullable', 'integer', 'min:2000', 'max:2030'],
            'status' => ['nullable', 'string', 'in:active,graduated,dropped'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'student_id.required' => 'Student ID is required.',
            'student_id.unique' => 'Student ID already exists.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists.',
            'department.required' => 'Department is required.',
            'department.exists' => 'Department does not exist in the system.',
            'program.required' => 'Program is required.',
            'program.exists' => 'Program does not exist in the system.',
            'year_of_admission.required' => 'Year of admission is required.',
            'year_of_admission.integer' => 'Year of admission must be a number.',
            'status.in' => 'Status must be one of: active, graduated, dropped.',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
