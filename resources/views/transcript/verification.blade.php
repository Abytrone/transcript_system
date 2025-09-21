<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript Verification - School of Hygiene, Tamale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Transcript Verification</h1>
                <p class="mt-2 text-gray-600">School of Hygiene, Tamale</p>
            </div>

            <!-- Verification Form -->
            <div class="bg-white shadow-lg rounded-lg p-8" x-data="verificationApp()">
                <div class="mb-6">
                    <label for="transcript-uuid" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter Transcript UUID or Scan QR Code
                    </label>
                    <div class="flex space-x-4">
                        <input
                            type="text"
                            id="transcript-uuid"
                            x-model="uuid"
                            placeholder="Enter transcript UUID..."
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button
                            @click="verifyTranscript()"
                            :disabled="loading"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                        >
                            <span x-show="!loading">Verify</span>
                            <span x-show="loading">Verifying...</span>
                        </button>
                    </div>
                </div>

                <!-- QR Code Scanner Placeholder -->
                <div class="mb-6 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                    <p class="text-gray-500">QR Code Scanner will be implemented here</p>
                </div>

                <!-- Results -->
                <div x-show="result" class="mt-6">
                    <!-- Security Level Indicator -->
                    <div x-show="result.valid" class="mb-4">
                        <div class="flex items-center justify-between p-3 rounded-lg"
                             :class="{
                                'bg-green-50 border border-green-200': result.security_level === 'high',
                                'bg-yellow-50 border border-yellow-200': result.security_level === 'medium',
                                'bg-red-50 border border-red-200': result.security_level === 'low'
                             }">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2"
                                     :class="{
                                        'bg-green-500': result.security_level === 'high',
                                        'bg-yellow-500': result.security_level === 'medium',
                                        'bg-red-500': result.security_level === 'low'
                                     }"></div>
                                <span class="font-medium"
                                      :class="{
                                        'text-green-800': result.security_level === 'high',
                                        'text-yellow-800': result.security_level === 'medium',
                                        'text-red-800': result.security_level === 'low'
                                      }">
                                    Security Level: <span x-text="result.security_level?.toUpperCase()"></span>
                                </span>
                            </div>
                            <div class="text-sm"
                                 :class="{
                                    'text-green-700': result.security_level === 'high',
                                    'text-yellow-700': result.security_level === 'medium',
                                    'text-red-700': result.security_level === 'low'
                                 }">
                                Verified <span x-text="result.transcript?.verification_count || 0"></span> times
                            </div>
                        </div>
                    </div>

                    <div x-show="result.valid" class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-green-800">Transcript Verified Successfully</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Transcript Number:</p>
                                <p class="font-semibold" x-text="result.transcript.transcript_number"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Student Name:</p>
                                <p class="font-semibold" x-text="result.transcript.student_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Student ID:</p>
                                <p class="font-semibold" x-text="result.transcript.student_id"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Program:</p>
                                <p class="font-semibold" x-text="result.transcript.program"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Year of Completion:</p>
                                <p class="font-semibold" x-text="result.transcript.year_of_completion"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">CGPA:</p>
                                <p class="font-semibold" x-text="result.transcript.cgpa"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Class of Degree:</p>
                                <p class="font-semibold" x-text="result.transcript.class_of_degree"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status:</p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" x-text="result.transcript.status"></span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Watermark:</p>
                                <p class="font-mono text-xs" x-text="result.transcript.watermark"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Last Verified:</p>
                                <p class="text-sm" x-text="result.transcript.last_verified_at ? new Date(result.transcript.last_verified_at).toLocaleString() : 'Never'"></p>
                            </div>
                        </div>

                        <!-- Security Issues -->
                        <div x-show="result.issues && result.issues.length > 0" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Security Warnings:</h4>
                            <ul class="list-disc list-inside text-yellow-700">
                                <template x-for="issue in result.issues" :key="issue">
                                    <li x-text="issue"></li>
                                </template>
                            </ul>
                        </div>

                        <!-- Suspicious Activity -->
                        <div x-show="result.suspicious_activity && result.suspicious_activity.length > 0" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Suspicious Activity Detected:</h4>
                            <ul class="list-disc list-inside text-red-700">
                                <template x-for="activity in result.suspicious_activity" :key="activity">
                                    <li x-text="activity"></li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <div x-show="!result.valid" class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-red-800">Verification Failed</h3>
                        </div>
                        <p class="mt-2 text-red-700" x-text="result.message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verificationApp() {
            return {
                uuid: '',
                loading: false,
                result: null,

                async verifyTranscript() {
                    if (!this.uuid.trim()) {
                        alert('Please enter a transcript UUID');
                        return;
                    }

                    this.loading = true;
                    this.result = null;

                    try {
                        const response = await fetch(`/verify/${this.uuid}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        });

                        const data = await response.json();
                        this.result = data;
                    } catch (error) {
                        this.result = {
                            valid: false,
                            message: 'An error occurred during verification'
                        };
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
