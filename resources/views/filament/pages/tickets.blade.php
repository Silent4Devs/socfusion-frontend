<x-filament-panels::page>
    
    <script>
        window.addEventListener('ticket-error', (event) => {
            const message = event.detail?.[0]?.message || "Hubo un error inesperado.";
            window.showToastError(message);
        });

        window.addEventListener('ticket-success', (event) => {
            const message = event.detail?.[0]?.message || "Se ha guardado exitosamente.";
            window.showToastSuccess(message);
        });

        window.addEventListener('page-reload', event => {
            window.location.reload();
        });

    </script>

    <div class="p-6 min-h-screen transition-colors duration-300" x-data="{ showEditModal : false, showDetailModal: false, showNewModal: false, showReassignModal : false}">
       
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tickets</h1>
            <p class="text-gray-600 dark:text-gray-400">Gestión de tickets del sistema BMC Remedy</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="search" placeholder="Buscar tickets..." 
                           class="pl-10 pr-4 py-2 w-full border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-400">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <select wire:model.live="status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="Pending">Pendiente</option>
                    <option value="In Process">En proceso</option>
                    <option value="Resolved">Resueltos</option>
                    <option value="Closed">Cerrados</option>
                </select>
            </div>
            
            <button @click="showNewModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 flex items-center gap-2 w-full md:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Ticket
            </button>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Identificador Único</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $ticket["unique_identifier"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket["description"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $ticket["assignee"] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($ticket["status"])
                                        @case('In Progress')
                                            <span class="px-2 py-1 text-xs text-blue-800  dark:text-blue-200">En proceso</span>
                                            @break
                                        @case('Resolved')
                                            <span class="px-2 py-1 text-xs  text-green-800  dark:text-green-200">Resuelto</span>
                                            @break
                                        @case('Pending')
                                            <span class="px-2 py-1 text-xs text-yellow-800 dark:text-yellow-200">Pendiente</span>
                                            @break
                                        @case('Closed')
                                            <span class="px-2 py-1 text-xs  text-purple-800  dark:text-blue-300">Cerrado</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs  text-gray-800  dark:text-gray-200">{{ $ticket["status"] ?? 'Desconocido' }}</span>
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($ticket["priority"])
                                        @case('Low')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 dark:text-green-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-green-400 dark:bg-green-600"></span>
                                                Baja
                                            </span>
                                            @break
                                        @case('Medium')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700 dark:text-yellow-200">
                                                <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 dark:bg-yellow-600"></span>
                                                Media
                                            </span>
                                            @break
                                        @case('High')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-orange-700 dark:text-orange-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-orange-400 dark:bg-orange-600"></span>
                                                Alta
                                            </span>
                                            @break
                                        @case('Critical')
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700 dark:text-red-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-red-400 dark:bg-red-600"></span>
                                                Critica
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                                                <span class="inline-block w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-600"></span>
                                                {{ $ticket["priority"] ?? 'Desconocida' }}
                                            </span>
                                    @endswitch
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $ticket["submit_date"] ? \Carbon\Carbon::parse($ticket["submit_date"])->translatedFormat('d M Y, H:i') : '' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2" x-data="{showReassignSelect : false, showStatusSelect : false}">
                                        <div class="relative group">
                                            <button 
                                                @click="$wire.getTicketDetails({{ $ticket['id'] }}); showDetailModal = true"                                                
                                                class="relative p-1.5 rounded-full group transition-all duration-200"
                                                aria-label="Ver detalle"
                                                >
                                                <div class="relative">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                    </svg>
                                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="h-1 w-1 rounded-full bg-blue-600 dark:bg-blue-300"></div>
                                                    </div>
                                                </div>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    Ver detalles
                                                </span>
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <button 
                                                @click="showReassignSelect = !showReassignSelect"
                                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group"
                                                aria-label="Reasignar ticket"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                                <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                                                Reasignar ticket
                                                </span>
                                            </button>
                                            
                                                <div 
                                                    x-show="showReassignSelect"
                                                    @click.away="showReassignSelect = false"
                                                    class="absolute right-0 z-10 mt-1 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 focus:outline-none"
                                                    style="display: none;"
                                                >
                                                    <div class="py-1">
                                                        <p class="px-3 py-1.5 text-xs text-gray-500 dark:text-gray-400">Asignar a</p>
                                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                                        
                                                        @foreach($assignees as $assignee)
                                                            <button 
                                                                wire:click.prevent="reasignarTicket('{{ $ticket['id'] }}','{{ $assignee }}')"
                                                                @click="showReassignSelect = false"
                                                                class="w-full text-left px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center"
                                                            >
                                                                <span class="truncate">{{ $assignee }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="relative group overflow-x-show">
                                            <button 
                                            @click="showStatusSelect = !showStatusSelect"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 p-1 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                            aria-label="Cambiar estatus"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            </button>
                                            <span class="group-hover:opacity-100 opacity-0 absolute bottom-full left-1/2 transform -translate-x-1/2 mt-1 px-2 py-1 text-xs bg-gray-800 text-white rounded whitespace-nowrap transition-opacity duration-200">
                                                Estatus
                                            </span>
                                                <div 
                                                x-show="showStatusSelect"
                                                @click.away="showStatusSelect = false"
                                                class="absolute right-0 z-10 mt-1 w-40 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 focus:outline-none"
                                                style="display: none;"
                                                >
                                                <div class="py-1">
                                                    <p class="px-3 py-1.5 text-xs text-gray-500 dark:text-gray-400">Estado</p>
                                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                                    <button 
                                                        wire:click="changeStatus('{{ $ticket['id'] }}','Pending')"
                                                        @click="showStatusSelect = false"
                                                        class="w-full text-left px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center"
                                                        >
                                                        <span class="w-2 h-2 mr-2 bg-yellow-500 rounded-full flex-shrink-0"></span>
                                                        <span>Pendiente</span>
                                                    </button>
                                                    <button 
                                                        wire:click="changeStatus('{{ $ticket['id'] }}','In Progress')"
                                                        @click="showStatusSelect = false"
                                                        class="w-full text-left px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center"
                                                        >
                                                        <span class="w-2 h-2 mr-2 bg-gray-500 rounded-full flex-shrink-0"></span>
                                                        <span>En proceso</span>
                                                    </button>
                                                    <button 
                                                        wire:click="changeStatus('{{ $ticket['id'] }}','Resolved')"
                                                        @click="showStatusSelect = false"
                                                        class="w-full text-left px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center"
                                                        >
                                                        <span class="w-2 h-2 mr-2 bg-green-500 rounded-full flex-shrink-0"></span>
                                                        <span>Resuelto</span>
                                                    </button>
                                                    <button 
                                                        wire:click="changeStatus('{{ $ticket['id'] }}','Closed')"
                                                        @click="showStatusSelect = false"
                                                        class="w-full text-left px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center"
                                                        >
                                                        <span class="w-2 h-2 mr-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                                                        <span>Cerrado</span>
                                                    </button>
                                                </div>
                                                </div>
                                        </div>
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

        <div 
            x-show="showNewModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            @click.away="showNewModal = false">
            <div @click.stop class="relative w-50 rounded-xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-600/30 shadow-2xl overflow-hidden">

                <div class="h-1 bg-gradient-to-r from-blue-500 to-cyan-400"></div>
            
                <div class="p-6 flex justify-between">
                    <h3 class="text-xl font-light tracking-wide text-gray-800 dark:text-white/90">
                        <span class="font-medium">Nuevo Ticket</span>
                    </h3>
                    <button 
                        @click="showNewModal = false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none transition-colors"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

             <form wire:submit.prevent="submitTicket">
                <div class="p-6 space-y-6">
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción*</label>
                        <textarea 
                            wire:model.defer="description"
                            id="description" 
                            rows="4" 
                            class="w-full px-3 py-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Describe el problema o solicitud..."
                            required
                        ></textarea>
                        @error('description') <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="ticketType" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Ticket*</label>
                            <select 
                                wire:model.defer="ticketType"
                                id="ticketType" 
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            >
                                <option value="">Seleccionar...</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach

                            </select>
                            @error('ticketType') <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Compañía*</label>
                            <select 
                                wire:model.defer="company"
                                id="company" 
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            >
                                <option value="">Seleccionar...</option>
                                @foreach ($organizations as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                                

                            </select>
                            @error('company') <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="assignedTo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignado a*</label>
                            <select 
                                wire:model.defer="assignedTo"
                                id="assignedTo" 
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            >
                                <option value="">Seleccionar...</option>

                               @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee }}">{{ $assignee }}</option>
                                @endforeach

                            </select>
                            @error('assignedTo') <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="comments" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comentarios Adicionales</label>
                        <textarea 
                            wire:model.defer="comments"
                            id="comments" 
                            rows="2" 
                            class="w-full px-3 py-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Notas adicionales..."
                        ></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end p-4 border-t dark:border-gray-700 space-x-3">
                    <button 
                        type="button"
                        wire:loading.attr="disabled"
                        @click="showNewModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-700 dark:text-white"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 flex items-center gap-2"
                    >
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span wire:loading.remove>Crear Ticket</span>
                        <span wire:loading>
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            </div>
        </div>


            <div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showDetailModal = false">
                        <div class="absolute inset-0 bg-black opacity-70"></div>
                    </div>
                    
                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        Detalles del Ticket
                        </h3>
                        <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        </button>
                    </div>
                    
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        @if ($selectedTicket)
                            @foreach ($selectedTicket as $section => $fields)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 border-b pb-1 mb-3">{{ $section }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($fields as $label => $value)
                                            <p class="text-sm">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">{{ $label }}:</span>
                                                <span class="text-gray-900 dark:text-white">
                                                    @if(is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {!! nl2br(e($value)) !!}
                                                    @endif
                                                </span>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">No se encontraron datos para este ticket.</p>
                        @endif
                    </div>

                    
                    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 flex justify-end">
                        <button 
                        @click="showDetailModal = false" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-md text-sm font-medium transition-colors"
                        >
                        Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</x-filament-panels::page>