@php
    $results = $results ?? collect();
    // Sort results by semester in ascending order
    $results = $results->sortBy('semester');
@endphp

@if($results->isEmpty())
    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
        No course records found
    </div>
@else
    <div class="w-full">
        <div class="fi-ta-content overflow-hidden overflow-x-auto ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl bg-white dark:bg-gray-900 shadow-sm">
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 dark:divide-white/5">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr class="divide-x divide-gray-200 dark:divide-white/5">
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">GPA</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester</th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Year</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @foreach($results as $result)
                        @php
                            $gradeColor = match ($result->grade) {
                                'A', 'A+' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'B', 'B+' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'C', 'C+' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'D', 'D+' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                'F' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                            };
                        @endphp
                        <tr class="fi-ta-row hover:bg-gray-50 dark:hover:bg-white/5 divide-x divide-gray-200 dark:divide-white/5">
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $result->course->code ?? 'N/A' }}
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 text-sm text-gray-900 dark:text-white">
                                {{ $result->course->title ?? 'N/A' }}
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeColor }}">
                                    {{ $result->grade }}
                                </span>
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                {{ $result->course->credits ?? 'N/A' }}
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                {{ number_format($result->gpa, 2) }}
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                {{ $result->semester }}
                            </td>
                            <td class="fi-ta-col-wrp px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                {{ $result->academic_year }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
