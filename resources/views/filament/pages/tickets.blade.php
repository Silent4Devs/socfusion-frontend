<x-filament-panels::page>
    <div class="p-6 min-h-screen transition-colors duration-300">
       
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tickets</h1>
            <p class="text-gray-600 dark:text-gray-400">Gestión de tickets del sistema BMC Remedy</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="search" placeholder="Search tickets..." 
                           class="pl-10 pr-4 py-2 w-full border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-400">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <select wire:model.live="status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="In Process">In Process</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            
            <!-- <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 flex items-center gap-2 w-full md:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Ticket
            </button> -->
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Asunto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Asignado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Fecha</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @foreach ($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $ticket["entry_id"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket["description"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $ticket["assignee"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($ticket["status"])
                                        @case('In Process')
                                            <span class="px-2 py-1 text-xs text-blue-800  dark:text-blue-200">In Process</span>
                                            @break
                                        @case('Resolved')
                                            <span class="px-2 py-1 text-xs  text-green-800  dark:text-green-200">Resolved</span>
                                            @break
                                        @case('Pending')
                                            <span class="px-2 py-1 text-xs text-yellow-800 dark:text-yellow-200">Pending</span>
                                            @break
                                        @case('Closed')
                                            <span class="px-2 py-1 text-xs  text-purple-800  dark:text-blue-300">Closed</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs  text-gray-800  dark:text-gray-200">{{ $ticket["status"] ?? 'Unknown' }}</span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($ticket["priority"])
                                        @case('Low')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 dark:text-green-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-green-400 dark:bg-green-600"></span>
                                                Low
                                            </span>
                                            @break
                                        @case('Medium')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700 dark:text-yellow-200">
                                                <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 dark:bg-yellow-600"></span>
                                                Medium
                                            </span>
                                            @break
                                        @case('High')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-orange-700 dark:text-orange-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-orange-400 dark:bg-orange-600"></span>
                                                High
                                            </span>
                                            @break
                                        @case('Critical')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700 dark:text-red-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-red-400 dark:bg-red-600"></span>
                                                Critical
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-600"></span>
                                                {{ $ticket["priority"] ?? 'Unknown' }}
                                            </span>
                                    @endswitch
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $ticket["submit_date"] ? \Carbon\Carbon::parse($ticket["submit_date"])->translatedFormat('d M Y, H:i') : '' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 5.943 7.523 3 10 3c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Anterior
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Siguiente
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    @php
                        $start = ($total === 0) ? 0 : (($page - 1) * $perPage) + 1;
                        $end = min($start + count($tickets) - 1, $total);
                    @endphp

                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Mostrando <span class="font-medium">{{ $start }}</span> a <span class="font-medium">{{ $end }}</span> de <span class="font-medium">{{ $total }}</span> resultados
                        </p>
                    </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        
                        <button
                            wire:click="goToPage({{ $page - 1 }})"
                            @if($page <= 1) disabled @endif
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600"
                        >
                            <span class="sr-only">Anterior</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        @php
                        
                            $start = max(1, $page - 1);
                            $end = min($totalPages, $page + 1);
                            if ($page === 1) {
                                $end = min($totalPages, 3);
                            }
                            if ($page === $totalPages) {
                                $start = max(1, $totalPages - 2);
                            }
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            <button
                                wire:click="goToPage({{ $i }})"
                                @if($page === $i)
                                    aria-current="page"
                                    class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300"
                                @else
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600"
                                @endif
                            >
                                {{ $i }}
                            </button>
                        @endfor

                   
                        <button
                            wire:click="goToPage({{ $page + 1 }})"
                            @if($page >= $totalPages) disabled @endif
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600"
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
</x-filament-panels::page>