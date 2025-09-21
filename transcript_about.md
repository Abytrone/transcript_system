You are an expert Laravel and Filament developer.  
Help me build a **Centralized Transcript Management System** for the **School of Hygiene, Tamale** using **Laravel 10, Filament v3, MySQL, and Tailwind CSS**.  

The system should include:  
1. **Authentication & Roles**  
   - Roles: Student, Alumni, Admin, and External Verifier.  
   - Students/Alumni: Can request transcripts, track request status, and download PDF transcripts when ready.  
   - Admin: Can approve/reject requests, generate transcripts, and manage users.  
   - Verifiers: Can validate transcripts using a unique transcript ID or QR code.  

2. **Transcript Request Module**  
   - Students/Alumni fill an online request form (basic biodata, program, year of completion).  
   - Requests are logged with status (Pending, Approved, Rejected, Completed).  
   - Admins update status and attach/generate transcripts.  

3. **Transcript Management**  
   - Admins generate transcripts from stored student records.  
   - Each transcript has a **unique ID and QR code** for verification.  
   - Transcripts are stored in the database and can be exported as PDF.  

4. **Verification Module**  
   - External verifiers can enter a transcript ID or scan a QR code to confirm authenticity.  
   - Verification results should show: Student name, program, year, and transcript status (valid/invalid).  

5. **Reporting & Dashboard**  
   - Admin dashboard with charts: number of requests per month, total transcripts issued, and pending requests.  
   - Logs of verification attempts for auditing.  

6. **System Requirements**  
   - Backend: Laravel 10 + Filament v3.  
   - Frontend: Tailwind CSS, Filament UI components.  
   - Database: MySQL 8 (tables for Users, Requests, Transcripts, Verifications).  
   - Security: Role-based access control, CSRF protection, hashed passwords, secure file storage.  
   - Testing: Include PHPUnit tests for critical features (login, request creation, transcript verification).  

Generate:  
- Proper Laravel migrations for all tables.  
- Models, Filament resources (CRUDs), and relationships.  
- Routes and controllers for transcript request submission and verification.  
- Blade views or Filament pages for user-facing forms and dashboards.  
- Code snippets for QR code generation and PDF export.  
- Example seeders (Admin account, sample users, and sample transcripts).  

Make the code production-ready, clean, and well-commented.  
