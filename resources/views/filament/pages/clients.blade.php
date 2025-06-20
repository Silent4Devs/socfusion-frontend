<x-filament-panels::page x-data="{ open: false }"
>
    <div class="p-6 min-h-screen">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Clientes</h1>
            <p class="text-gray-600 dark:text-gray-400">Gestión de clientes SOC</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div lass="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce="search" placeholder="Search clients..." 
                           class="pl-10 pr-4 py-2 w-full border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-400">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <button @click="open = true"
                    class="px-5 text-white py-2.5 rounded-lg bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-400 hover:to-blue-600 transition-all 
                           shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    NUEVO CLIENTE
            </button>
        </div>

        <div class="backdrop-blur-lg rounded-2xl shadow-md border border-dark/20 dark:border-white/20 overflow-hidden">
    

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                    <thead class="bg-white dark:bg-black">
                        <tr>
                            <th></th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">CLIENT</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">PHONE</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">ADDRESS</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5 bg-white dark:bg-black">
                        @foreach ($clients as $client)
                            
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <td class="px-4 py-2">
                                    <img 
                                        src="{{ asset('storage/' . $client->logo) }}"                                         
                                        alt="{{ $client->name }} Logo"
                                        class="w-10 h-10 rounded object-cover"
                                        loading="lazy"
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $client["name"] }}</div>
                                            <div class="text-sm text-blue-600 dark:text-blue-300">{{ $client["email" ]}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $client["phone"] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $client["address"] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                                <button 
                                                    wire:click="edit({{ $client->id }})" 
                                                    class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                                                >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button onclick="deleteClient({{ $client["id"] }},'{{ $client["name"] }}')" class="text-pink-600 hover:text-pink-500 dark:text-pink-400 dark:hover:text-pink-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
  
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
                        $start = ($total === 0) ? 0 : (($currentPage - 1) * $perPage) + 1;
                        $end = max(min($start + count($clients) - 1, $total),0);
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
                                        wire:click="gotoPage({{ $page }})"
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

    <div id="client-modal"   
        x-show="open"
         x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @open-client-modal.window="open = true"
     @close-client-modal.window="open = false"
      class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black/50 dark:bg-gray-900/80 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200 dark:border-white/10">
            <form id="client-form" enctype="multipart/form-data" wire:submit.prevent="{{ $clientId ? 'update' : 'save' }}">
                <div class="p-6 space-y-6">
                    <h3 id="modal-title" class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-400 dark:to-blue-600">
                        Datos del Cliente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Nombre del Cliente</label>
                        <input
                            type="text"
                            wire:model.defer="name"
                            required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 px-3 py-2 text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Ej. Grupo Alfa"
                            autocomplete="off"
                        >
                        @error('name')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Email</label>
                        <input
                            type="email"
                            wire:model.defer="email"
                            required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 px-3 py-2 text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Ej. ejemplo@empresa.com"
                            autocomplete="off"
                        >
                        @error('email')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Teléfono</label>
                        <input
                            type="text"
                            wire:model.defer="phone"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 px-3 py-2 text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Ej. 55 5555 5555"
                            autocomplete="off"
                        >
                        @error('phone')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Dirección</label>
                        <input
                            type="text"
                            wire:model.defer="address"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 px-3 py-2 text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Dirección del cliente"
                            autocomplete="off"
                        >
                        @error('address')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                        <div>
                            <label ...>Logo</label>
                            <input type="file" wire:model.live="logo" accept="image/*" class="block w-full ...">
                            @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @if ($logo)
                                @if (is_object($logo) && method_exists($logo, 'temporaryUrl'))
                                    <img src="{{ $logo->temporaryUrl() }}" class="mt-2 w-14 h-14 rounded object-cover" />
                                @else
                               
                                    <img src="{{ Storage::url($logo) }}" class="mt-2 w-14 h-14 rounded object-cover" />
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-800/50 border-t border-gray-200 dark:border-white/10 flex justify-end space-x-3">
                    <button type="button" @click="open = false" class="px-6 py-2.5 border border-gray-300 dark:border-white/20 rounded-lg text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        @success-action.window="open = false"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-600 rounded-lg text-white hover:from-blue-400 hover:to-blue-600 transition-all shadow-lg shadow-blue-600/20">

                        {{ $clientId ? 'Actualizar Cliente' : 'Guardar Cliente' }}
                    </button>
                    
                </div>
            </form>
            </div>
        </div>
    </div>

    @script
    <script>
        
        deleteClient = function(clientId, clientName) {
            window.showModalConfirm(
                `¿Seguro que quieres eliminar al cliente <b>${clientName}</b>?`,
                'Sí, eliminar',
                'Cancelar'
            ).then((result) => {
                if (result.isConfirmed) {
                    $dispatch('delete-client', {id: clientId});
                }
            });
        };

        window.addEventListener('swal-deleted', () => {
            window.showToastSuccess("El cliente ha sido eliminado correctamente.");
            
        });

        window.addEventListener('client-created', () => {
            window.showToastSuccess("Cliente creado con éxito");
            
        });

        window.addEventListener('client-edited', () => {
            window.showToastSuccess("Cliente editado con éxito");
            
        });
    </script>
    @endscript

    <style>
        table {
            --tw-divide-opacity: 0.1;
        }
        
        tr {
            transition: all 0.2s ease;
        }
    </style>
</x-filament-panels::page>