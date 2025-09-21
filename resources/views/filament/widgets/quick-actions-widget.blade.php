<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <x-slot name="description">
            Common tasks and shortcuts
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($this->getViewData()['actions'] as $action)
                <a href="{{ $action['url'] }}"
                   class="group relative bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-{{ $action['color'] }}-300">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 rounded-lg flex items-center justify-center group-hover:bg-{{ $action['color'] }}-200 dark:group-hover:bg-{{ $action['color'] }}-800 transition-colors">
                                <x-heroicon-o-{{ str_replace('heroicon-o-', '', $action['icon']) }} class="w-6 h-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:text-{{ $action['color'] }}-600 dark:group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                {{ $action['title'] }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $action['description'] }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-400 group-hover:text-{{ $action['color'] }}-500 transition-colors" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
