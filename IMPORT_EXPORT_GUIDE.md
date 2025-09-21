# Import/Export Guide

## Student Data Import/Export

### Exporting Students
1. Go to **Student Management > Students**
2. Click **"Export Students"** button in the header
3. The system will download an Excel file with all student data

### Importing Students
1. Go to **Student Management > Students**
2. Click **"Import Students"** button in the header
3. Upload an Excel/CSV file with student data
4. The file should have the following columns:
   - `student_id` - Student index number (e.g., STU001)
   - `first_name` - Student's first name
   - `last_name` - Student's last name
   - `middle_name` - Student's middle name (optional)
   - `email` - Student's email address
   - `phone` - Student's phone number (optional)
   - `date_of_birth` - Date of birth (YYYY-MM-DD format)
   - `gender` - Gender (male/female/other)
   - `nationality` - Student's nationality (optional)
   - `address` - Student's address (optional)
   - `department` - Department name (must exist in system)
   - `program` - Program name (must exist in system)
   - `year_of_admission` - Year of admission (e.g., 2023)
   - `year_of_completion` - Year of completion (optional)
   - `status` - Status (active/graduated/dropped)

### Download Template
- Click **"Download Template"** to get a properly formatted Excel file
- Use this template to ensure correct column headers and format

## Academic Results Import

### Importing Results
1. Go to **Student Management > Students**
2. Click **"Import Results"** button in the header
3. Or navigate to **Student Management > Students > Import Results**
4. Upload an Excel/CSV file with academic results data

### Results File Format
The file should have the following columns:
- `student_id` - Student index number (must exist in system)
- `course_code` - Course code (must exist in system)
- `score` - Numeric score (0-100)
- `academic_year` - Academic year (e.g., 2023)
- `semester` - Semester (1 or 2)
- `is_resit` - Resit status (yes/no, optional)

### Automatic Calculations
- **GPA**: Automatically calculated based on score
- **Grade**: Automatically determined based on score
- **Score to Grade/GPA Mapping**:
  - 80-100: A (4.0 GPA)
  - 75-79: B+ (3.5 GPA)
  - 70-74: B (3.0 GPA)
  - 65-69: C+ (2.5 GPA)
  - 60-64: C (2.0 GPA)
  - 55-59: D+ (1.5 GPA)
  - 50-54: D (1.0 GPA)
  - 0-49: F (0.0 GPA)

### Download Results Template
- Click **"Download Template"** on the Import Results page
- This provides a sample file with the correct format

## Important Notes

### Data Validation
- All student IDs and course codes must exist in the system
- Email addresses must be unique
- Student IDs must be unique
- Invalid data will be reported during import

### File Requirements
- Supported formats: Excel (.xlsx) and CSV
- Maximum file size: 10MB
- First row should contain column headers
- Data should start from the second row

### Error Handling
- Import errors will be displayed with specific error messages
- Failed rows will be skipped and reported
- Successful imports will show a success notification

### Performance
- Large files are processed in batches for better performance
- Import progress is shown for large files
- System automatically handles memory optimization

## Troubleshooting

### Common Issues
1. **"Student not found"**: Ensure student ID exists in the system
2. **"Course not found"**: Ensure course code exists in the system
3. **"Department not found"**: Ensure department name matches exactly
4. **"Program not found"**: Ensure program name matches exactly
5. **"Invalid email format"**: Check email addresses are valid
6. **"Duplicate student ID"**: Student ID already exists in system

### Best Practices
1. Always download and use the provided templates
2. Test with a small file first
3. Backup your data before importing
4. Verify data accuracy before importing
5. Use consistent naming conventions
6. Ensure all referenced data exists in the system
