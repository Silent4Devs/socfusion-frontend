<x-filament-panels::page>
    <div x-data="{ tab: 'MT' }">
        <div class="mb-6 space-y-4">
            <div class="flex space-x-2 border-b border-gray-200 dark:border-gray-800">
                <button 
                    @click="tab = 'MT'" 
                    :class="tab === 'MT' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 font-semibold">
                    MT
                </button>
                <button                 
                    @click="tab = 'SLA'" 
                    :class="tab === 'SLA' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 font-semibold">
                    SLA
                </button>
                <button                 
                    @click="tab = 'BD'" 
                    :class="tab === 'BD' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 font-semibold">
                    BD
                </button>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <input
                    type="text"
                    wire:model.live="search" 
                    placeholder="Buscar documento..."
                    class="w-full md:w-64 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >

                <select wire:model.live="selectedClient" class="w-full md:w-64 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos los clientes</option>
                    @foreach ($clients as $client) 
                        <option value="{{ $client }}">{{ mb_strtoupper($client, 'UTF-8') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div x-show="tab === 'MT'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


            @foreach ($filteredDocuments as $doc) 
                @if ($doc['file_type'] == 'MT')
          
                <x-filament::card class="hover:shadow-lg transition-shadow">
                
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $doc['filename']}}
                            </h3>
                            <div class="flex items-center mt-1 space-x-2">
                                <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-full">
                                {{ $doc['file_type'] }}
                                </span>
                                <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-full">
                                    {{ $doc['client'] }}
                                </span>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                <div class="py-1">
                                    <a href="http://192.168.40.1:8000/documents/file/?file_name={{ $doc['fullpath']}}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                            </div>
                            
                        
                            <div class="hidden absolute right-0 z-10 mt-1 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $doc['summarize']}}                    
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-800">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $doc['type'] }} 
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $doc['created_at'] ? \Carbon\Carbon::parse($doc['created_at'])->format('Y-m-d') : '' }}
                        </span>
                    </div>
                </x-filament::card>
            
                @endif
            @endforeach

        </div>


        <div x-show="tab === 'SLA'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($filteredDocuments as $doc) 
            @if ($doc['file_type'] == 'SLA')
            <x-filament::card class="hover:shadow-lg transition-shadow">
                
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $doc['filename']}}
                        </h3>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-full">
                            {{ $doc['file_type'] }}
                            </span>
                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-full">
                                {{ $doc['client'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <button class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                        
                    
                        <div class="hidden absolute right-0 z-10 mt-1 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700">
                            <div class="py-1">
                                <a href="http://192.168.40.1:8000/documents/file/?file_name={{ $doc['fullpath']}}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            
                <div class="mt-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $doc['summarize']}}                    
                    </p>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $doc['type'] }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $doc['created_at'] ? \Carbon\Carbon::parse($doc['created_at'])->format('Y-m-d') : '' }}
                    </span>
                </div>
            </x-filament::card>
        
            @endif
        @endforeach
        </div>
   
        <div x-show="tab === 'BD'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
        @foreach ($filteredDocuments as $doc) 
            @if ($doc['file_type'] == 'BD')            
            <x-filament::card class="hover:shadow-lg transition-shadow">    
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $doc['filename']}}
                        </h3>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-full">
                            BD
                            </span>
                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-full">
                                {{ $doc['client'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <button class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                        
                    
                        <div class="hidden absolute right-0 z-10 mt-1 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700">
                            <div class="py-1">
                                <a href="http://192.168.40.1:8000/documents/file/?file_name={{ $doc['fullpath']}}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            
                <div class="mt-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $doc['summarize']}}                    
                    </p>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $doc['type'] }} 
                        </span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $doc['created_at'] ? \Carbon\Carbon::parse($doc['created_at'])->format('Y-m-d') : '' }}
                    </span>
                </div>
            </x-filament::card>
        
            @endif
        @endforeach
        </div>

    </div>
</x-filament-panels::page>