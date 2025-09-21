<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Transcript Access - School of Hygiene, Tamale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="studentAccessApp()">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Student Transcript Access</h1>
                <p class="mt-2 text-gray-600">School of Hygiene, Tamale</p>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading transcript...</p>
            </div>

            <!-- Transcript Display -->
            <div x-show="!loading && transcript" class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Transcript Header -->
                <div class="bg-blue-600 text-white p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold">Academic Transcript</h2>
                            <p class="mt-1">School of Hygiene, Tamale</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Transcript Number</p>
                            <p class="font-semibold" x-text="transcript.transcript_number"></p>
                        </div>
                    </div>
                </div>

                <!-- Student Information -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="font-semibold" x-text="transcript.student.name"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Student ID</p>
                            <p class="font-semibold" x-text="transcript.student.student_id"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold" x-text="transcript.student.email"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Department</p>
                            <p class="font-semibold" x-text="transcript.student.department"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Faculty</p>
                            <p class="font-semibold" x-text="transcript.student.faculty"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Program</p>
                            <p class="font-semibold" x-text="transcript.program"></p>
                        </div>
                    </div>
                </div>

                <!-- Academic Summary -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Year of Completion</p>
                            <p class="text-2xl font-bold text-blue-600" x-text="transcript.year_of_completion"></p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">CGPA</p>
                            <p class="text-2xl font-bold text-green-600" x-text="transcript.cgpa"></p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Class of Degree</p>
                            <p class="text-lg font-semibold text-purple-600" x-text="transcript.class_of_degree"></p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800" x-text="transcript.status"></span>
                        </div>
                    </div>
                </div>

                <!-- Course Results -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Results</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="course in transcript.courses" :key="course.course_code">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="course.course_code"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="course.course_title"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="course.grade"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="course.credit_hours"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="course.gpa"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="course.semester"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="course.academic_year"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <p>Issued on: <span x-text="new Date(transcript.issued_at).toLocaleDateString()"></span></p>
                            <p x-show="transcript.issued_by">Issued by: <span x-text="transcript.issued_by"></span></p>
                        </div>
                        <div class="flex space-x-4">
                            <button
                                @click="downloadTranscript()"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Download PDF
                            </button>
                            <button
                                @click="printTranscript()"
                                class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div x-show="!loading && !transcript && error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-red-800 mb-2">Error Loading Transcript</h3>
                <p class="text-red-700" x-text="error"></p>
            </div>
        </div>
    </div>

    <script>
        function studentAccessApp() {
            return {
                loading: true,
                transcript: null,
                error: null,

                async init() {
                    await this.loadTranscript();
                },

                async loadTranscript() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`/student-access/{{ $transcript->uuid }}/data`);
                        const data = await response.json();

                        if (data.transcript) {
                            this.transcript = data.transcript;
                        } else {
                            this.error = data.error || 'Failed to load transcript';
                        }
                    } catch (error) {
                        this.error = 'An error occurred while loading the transcript';
                    } finally {
                        this.loading = false;
                    }
                },

                downloadTranscript() {
                    window.open(`/student-access/{{ $transcript->uuid }}/download`, '_blank');
                },

                printTranscript() {
                    window.print();
                }
            }
        }
    </script>
</body>
</html>
