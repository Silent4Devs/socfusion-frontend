<x-filament-panels::page x-data="{ open: false, showDetail: false }">
    <div class="p-6 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Histórico de Alarmas</h1>
            <p class="text-gray-600 dark:text-gray-400">Registro de todas las alertas del sistema</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce="search" placeholder="Buscar alarmas..." 
                           class="pl-10 pr-4 py-2 w-full border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-400">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <select wire:model.live="selectedSystem" 
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">Todos los sistemas</option>
                    <option value="logrhythm">LogRhythm</option>
                    <option value="prtg">PRTG</option>
                </select>
                
                <select wire:model.live="severityFilter" 
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">Todas las severidades</option>
                    <option value="critical">Crítico</option>
                    <option value="high">Alto</option>
                    <option value="medium">Medio</option>
                    <option value="low">Bajo</option>
                </select>
            </div>
            
        
            <button wire:click="exportToExcel"
                    class="px-5 text-white py-2.5 rounded-lg bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 transition-all 
                           shadow-lg shadow-green-600/20 hover:shadow-green-600/30 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                EXPORTAR
            </button>
        </div>

        <div class="backdrop-blur-lg rounded-2xl shadow-md border border-dark/20 dark:border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                    <thead class="bg-white dark:bg-black">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sistema</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mensaje</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Severidad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-white/10">
                        @foreach($this->paginatedAlarms as $alarm)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            @if(!empty($alarm['id']))
                                {{ $alarm['id'] }}
                            @else
                                <span class="text-gray-400 italic">Sin ID</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ ($alarm['alarm_type'] ?? null) === 'logrhythm' 
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' 
                                            : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                {{ strtoupper($alarm['alarm_type'] ?? 'N/A') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 max-w-xs truncate">
                            @if($alarm['alarm_type'] === 'logrhythm')
                                {{ !empty($alarm['alarm_rule_name']) ? $alarm['alarm_rule_name'] : 'Alarma sin descripción' }}
                            @else
                                {{ !(empty($alarm['message_raw'])) ? $alarm['message_raw'] :'Alarma sin descripción' }}

                            @endif
                        </td>

                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $severity = !empty($alarm['real_classification']) ? $alarm['real_classification'] : $alarm['model_classification'];
                                $severityClass = match($severity) {
                                    'Critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'High'     => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                    'Medium'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    default    => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $severityClass }}">
                                {{ ucfirst($severity ?? 'N/A') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                       @if(isset($alarm['created_at']) && !empty($alarm['created_at']))
                            {{ \Carbon\Carbon::parse($alarm['created_at'])->format('d/m/Y H:i') }}
                        @elseif(isset($alarm['date_inserted']) && !empty($alarm['date_inserted']))
                            {{ \Carbon\Carbon::parse($alarm['date_inserted'])->format('d/m/Y H:i') }}
                        @else
                            <span class="text-gray-400 italic">Sin fecha</span>
                        @endif

                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(!empty($alarm['id']))
                                <button @click="showDetail = true; $wire.setSelectedAlarm({{$alarm['id']}}, '{{$alarm['alarm_type']}}')"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    Detalles
                                </button>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                    </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
             <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200 dark:border-white/10 bg-white text-black dark:bg-black dark:text-white">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-white/20 rounded-md text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10">
                        Anterior
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-white/20 rounded-md text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10">
                        Siguiente
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    @php
                        $start = ($totalPages === 0) ? 0 : (($currentPage - 1) * $perPage) + 1;
                        $end = max(min($start + $perPage - 1, $total),0);
                    @endphp

                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Mostrando <span class="font-medium">{{ $start }}</span> a <span class="font-medium">{{ $end }}</span> de <span class="font-medium">{{ $total }}</span> resultados
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button
                                wire:click="previousPage"
                                @if($currentPage == 1) disabled @endif
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-white/20 text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 focus:outline-none"
                                aria-label="Anterior"
                            >
                                <span class="sr-only">Anterior</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            @foreach($pages as $page)
                                @if($page == $currentPage)
                                    <span
                                        aria-current="page"
                                        class="z-10 bg-blue-600 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                    >
                                        {{ $page }}
                                    </span>
                                @else
                                    <button
                                        wire:click="goToPage({{ $page }})"
                                        class="bg-gray-100 dark:bg-white/5 border-gray-300 dark:border-white/20 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:outline-none"
                                    >
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach

                            <button
                                wire:click="nextPage"
                                @if($currentPage == count($pages)) disabled @endif
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-white/20 text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 focus:outline-none"
                                aria-label="Siguiente"
                            >
                                <span class="sr-only">Siguiente</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
        


    <div 
        x-show="showDetail"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div 
            x-show="showDetail"
            @click="showDetail = false, $wire.selectedAlarm = null"
            class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm"
            x-transition.opacity
        ></div>


        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                x-show="showDetail"
                class="relative w-full max-w-2xl p-6 overflow-hidden bg-gray-800 rounded-xl shadow-2xl border border-gray-700/50"
                @click.away="showDetail = false"
            >
                <button 
                    @click="showDetail = false, $wire.selectedAlarm = null"
                    class="absolute top-4 right-4 p-1 rounded-full hover:bg-gray-700/50 transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-center mb-6 space-x-3">
                    <div class="p-2 rounded-lg bg-blue-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Alarm Details</h2>
                    <span class="px-3 py-1 ml-2 text-xs font-medium tracking-wider text-blue-100 bg-blue-500/30 rounded-full">
                        {{ strtoupper($selectedAlarm['alarm_type'] ?? 'N/A') }}
                    </span>
                </div>

                    <div class="space-y-4">
                        @if($selectedAlarm)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($selectedAlarm as $key => $value)
                                    @if(!empty($value) && $value !== '')
                                        <div class="group flex flex-col p-3 bg-gray-800/40 rounded-lg border border-gray-700/20 hover:border-gray-600/50 transition-all duration-150">
                                            <span class="text-[0.7rem] font-mono font-semibold text-gray-400/80 uppercase tracking-wider truncate">
                                                {{ $key }}
                                            </span>
                                            <span class="mt-1 text-xs font-medium text-gray-100 truncate" title="{{ is_array($value) || is_object($value) ? json_encode($value) : $value }}">
                                                @if(is_array($value) || is_object($value))
                                                    {{ Str::limit(json_encode($value), 40) }}
                                                @else
                                                    {{ Str::limit($value, 50) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

        
                <div class="flex justify-end mt-6 space-x-3">
                    <button 
                        @click="showDetail = false, $wire.selectedAlarm = null"
                        class="px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors duration-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-filament-panels::page>