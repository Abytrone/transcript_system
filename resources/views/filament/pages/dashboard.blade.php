<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ $this->getTitle() }}</h1>
                    <p class="text-blue-100 mt-2">
                        @if(auth()->user()->hasRole('Super Admin'))
                            Monitor system performance, manage users, and oversee all transcript operations.
                        @elseif(auth()->user()->hasRole('Faculty Admin'))
                            Manage your faculty's students, transcripts, and academic records.
                        @elseif(auth()->user()->hasRole('Department Admin'))
                            Handle department-specific transcript requests and student records.
                        @elseif(auth()->user()->hasRole('Verifier'))
                            Verify transcript authenticity and monitor verification activities.
                        @else
                            Welcome to the Transcript Management System.
                        @endif
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <div class="text-sm text-blue-100">Current Time</div>
                        <div class="text-lg font-semibold">{{ now()->format('M d, Y - h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Status Alerts --}}
        @if(auth()->user()->hasRole('Super Admin'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $pendingRequests = \App\Models\TranscriptRequest::where('status', 'pending')->count();
                    $recentVerifications = \App\Models\VerificationLog::where('created_at', '>=', now()->subHours(24))->count();
                    $systemHealth = 'good';
                @endphp

                @if($pendingRequests > 10)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-600 mr-2" />
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">High Pending Requests</h3>
                                <p class="text-sm text-yellow-700">{{ $pendingRequests }} requests awaiting processing</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($recentVerifications > 50)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 mr-2" />
                            <div>
                                <h3 class="text-sm font-medium text-green-800">High Verification Activity</h3>
                                <p class="text-sm text-green-700">{{ $recentVerifications }} verifications in last 24h</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-shield-check class="w-5 h-5 text-blue-600 mr-2" />
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">System Status</h3>
                            <p class="text-sm text-blue-700">All systems operational</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Widgets Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Main Content Area --}}
            <div class="lg:col-span-8 space-y-6">
                @foreach($this->getWidgets() as $widget)
                    @if(!in_array($widget, [\App\Filament\Widgets\QuickActionsWidget::class]))
                        @livewire($widget)
                    @endif
                @endforeach
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Quick Actions --}}
                @if(in_array(\App\Filament\Widgets\QuickActionsWidget::class, $this->getWidgets()))
                    @livewire(\App\Filament\Widgets\QuickActionsWidget::class)
                @endif

                {{-- System Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">System Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Laravel Version</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">PHP Version</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Environment</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ now()->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="{{ route('filament.admin.resources.transcript-requests.index') }}"
                           class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <x-heroicon-o-clipboard-document-list class="w-4 h-4 mr-2" />
                            Process Requests
                        </a>
                        <a href="{{ route('filament.admin.resources.students.index') }}"
                           class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <x-heroicon-o-user-group class="w-4 h-4 mr-2" />
                            Manage Students
                        </a>
                        <a href="{{ route('filament.admin.resources.transcripts.index') }}"
                           class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <x-heroicon-o-document-text class="w-4 h-4 mr-2" />
                            View Transcripts
                        </a>
                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('filament.admin.resources.users.index') }}"
                               class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <x-heroicon-o-users class="w-4 h-4 mr-2" />
                                Manage Users
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
