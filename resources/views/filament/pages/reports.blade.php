<x-filament-panels::page>
    <div class="relative w-full max-w-xs mr-auto">
        <input 
            type="text" 
            placeholder="Buscar..." 
            class="flex-1 p-2 border rounded bg-white text-black dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600"
            wire:model.live="query"
        />
    </div>
    <x-filament::section>
        <x-slot name="heading">
            Reportes de Alertas
        </x-slot>
        
        <x-filament::grid default="1" sm="2" md="3" class="gap-4" wire:poll.10s="$refresh">
            
            @foreach($this->reports as $report)
            <x-filament::card>
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div>
                                {{$report['title']}}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($report['created_at'])->diffForHumans() }}
                            </div>
                        </div>
                            @if (!empty($report['classification']))
                                <x-filament::badge color="danger">
                                    {{ $report['classification'] }}
                                </x-filament::badge>
                            @endif
                    </div>
                    
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{$report['description']}}
                    </div>
                    
                                        
                    <div class="rounded-md overflow-hidden">
                        @if($report['status'] === 'Completed')
                        <div 
                            x-data="{ loaded: false }"
                            class="relative bg-gray-100 dark:bg-gray-800 rounded shadow flex justify-center items-center"
                        >
                            <div x-show="!loaded" class="absolute z-10 flex items-center justify-center w-full" style="height: 300px;">
                                <svg class="animate-spin h-12 w-12 text-gray-300 dark:text-gray-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                          
                            <img
                                src="{{ url('/api/reports/' . $report['id'] . '/preview-image') }}"
                                alt="Preview"
                                class="block w-full h-auto object-contain rounded transition-opacity duration-300"
                                loading="lazy"
                                @load="loaded = true"
                                :class="{ 'opacity-0': !loaded, 'opacity-100': loaded }"
                               
                            />
                        </div>


                        @elseif($report['status'] === 'Error')
                        <div class="flex flex-col items-center justify-center space-y-4 py-6 animate-fade-in">
                            
                        
                            <div class="text-center space-y-2">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    Error al procesar
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md px-4">
                                    No pudimos generar el reporte. Por favor verifica tu conexión e inténtalo nuevamente.
                                </p>
                            </div>
                            
            
                        </div>

                        <style>
                            @keyframes shake-x {
                                0%, 100% { transform: translateX(0); }
                                20% { transform: translateX(-5px); }
                                40% { transform: translateX(5px); }
                                60% { transform: translateX(-5px); }
                                80% { transform: translateX(5px); }
                            }
                            .animate-shake-x {
                                animation: shake-x 0.8s ease-in-out;
                            }
                            .animate-fade-in {
                                animation: fadeIn 0.5s ease-out;
                            }
                            @keyframes fadeIn {
                                from { opacity: 0; transform: translateY(10px); }
                                to { opacity: 1; transform: translateY(0); }
                            }
                        </style>
                        @else
                        <div class="flex flex-col items-center justify-center space-y-3 py-6">
                            <div class="relative w-12 h-12">
                                <div class="absolute inset-0 rounded-full border-4 border-blue-100 dark:border-gray-700"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-blue-500 dark:border-t-cyan-400 animate-spin"></div>
                            </div>
                            
                            <div class="text-center space-y-1">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                    Procesando tu reporte
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 animate-pulse">
                                    Esto puede tomar unos minutos...
                                </p>
                            </div>
                        </div>

            

                        @endif
                    </div>

                    @if($report['status'] === 'Completed')  
                    <div class="grid gap-3 p-4 bg-white dark:bg-gray-900 rounded-xl">
                        <a href="{{ route('reports.download', ['id' => $report->id]) }}" 
                        class="future-btn future-btn--primary group">
                            <span class="future-btn__icon">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </span>
                            <span class="future-btn__text">Reporte completo</span>
                            <span class="future-btn__badge">PDF</span>
                            <span class="future-btn__hover-effect"></span>
                            <span class="future-btn__border"></span>
                        </a>

                      
                        <button wire:click="confirmDeletion({{ $report->id }})"
                                class="future-btn future-btn--danger group">
                            <span class="future-btn__icon">
                               
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </span>
                            <span class="future-btn__text">Eliminar reporte</span>
                            <span class="future-btn__hover-effect"></span>
                            <span class="future-btn__border"></span>
                        </button>
                    </div>
                    @else
                        <div class="grid gap-3 p-4 dark:bg-gray-900  bg-white rounded-xl shadow-sm dark:shadow-none">
                        
                            <button wire:click="confirmDeletion({{ $report->id }})"
                                    class="future-btn future-btn--danger group">
                                <span class="future-btn__icon">
                                
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </span>
                                <span class="future-btn__text">Eliminar reporte</span>
                                <span class="future-btn__hover-effect"></span>
                                <span class="future-btn__border"></span>
                            </button>
                        </div>
                    @endif
                </div>
            </x-filament::card>
            @endforeach 
        </x-filament::grid>
            <div class="mt-6 flex justify-center items-center  select-none">

                {{-- Previous button --}}
                <button
                    wire:click="previousPage"
                    @if($this->reports->onFirstPage()) disabled @endif
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-white/20 text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 focus:outline-none"
                    aria-label="Anterior"
                >
                    <span class="sr-only">Anterior</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 111.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- One page before --}}
                @if($this->reports->currentPage() > 1)
                    <button
                        wire:click="setPage({{ $this->reports->currentPage() - 1 }})"
                        class="bg-gray-100 dark:bg-white/5 border-gray-300 dark:border-white/20 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:outline-none"
                    >
                        {{ $this->reports->currentPage() - 1 }}
                    </button>
                @endif

                {{-- Current page --}}
                <span 
                class="z-10 bg-blue-600 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    {{ $this->reports->currentPage() }}
                </span>

                {{-- One page after --}}
                @if($this->reports->currentPage() < $this->reports->lastPage())
                    <button
                        wire:click="setPage({{ $this->reports->currentPage() + 1 }})"
                        class="bg-gray-100 dark:bg-white/5 border-gray-300 dark:border-white/20 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:outline-none"
                    >
                        {{ $this->reports->currentPage() + 1 }}
                    </button>
                @endif

                {{-- Next button --}}
                <button
                    wire:click="nextPage"
                    @if(!$this->reports->hasMorePages()) disabled @endif
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-white/20 text-sm font-medium text-black dark:text-white bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 focus:outline-none"
                    aria-label="Siguiente"
                >
                    <span class="sr-only">Siguiente</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

            </div>



        <style>
            .future-btn {
                position: relative;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1.25rem;
                border-radius: 0.5rem;
                overflow: hidden;
                transition: all 0.3s ease;
                background-color: white;
                border: 1px solid #e5e7eb;
                color: #1f2937;
                font-weight: 500;
            }

            .dark .future-btn {
                background-color: #1f2937;
                border-color: #374151;
                color: #f3f4f6;
            }

            .future-btn__icon {
                display: flex;
                transition: transform 0.3s ease;
            }

            .future-btn__text {
                flex-grow: 1;
                text-align: left;
            }

            .future-btn__badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                border-radius: 9999px;
                background-color: #e5e7eb;
                color: #4b5563;
            }

            .dark .future-btn__badge {
                background-color: #374151;
                color: #d1d5db;
            }

            .future-btn__hover-effect {
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent 0%, rgba(99, 102, 241, 0.1) 50%, transparent 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .future-btn--danger .future-btn__hover-effect {
                background: linear-gradient(90deg, transparent 0%, rgba(239, 68, 68, 0.1) 50%, transparent 100%);
            }

            .future-btn__border {
                position: absolute;
                inset: 0;
                border-radius: 0.5rem;
                padding: 1px;
                background: linear-gradient(135deg, #6366f1 0%, transparent 30%, transparent 70%, #6366f1 100%);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                pointer-events: none;
                opacity: 0.7;
            }

            .future-btn--danger .future-btn__border {
                background: linear-gradient(135deg, #ef4444 0%, transparent 30%, transparent 70%, #ef4444 100%);
            }

            .future-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .dark .future-btn:hover {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            }

            .future-btn:hover .future-btn__hover-effect {
                opacity: 1;
                animation: scan 1.5s linear infinite;
            }

            .future-btn:hover .future-btn__icon {
                transform: translateX(2px);
            }

            @keyframes scan {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }
        </style>

        @script
        <script>

        window.addEventListener('swal-confirm', event => {

            const isDarkMode = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: "¿Estás seguro de eliminar el reporte?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: isDarkMode ? '#f3f4f6' : '#111827', 
                iconColor: isDarkMode ? '#ef4444' : '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    $dispatch('delete-report', {id: event.detail.id});
                }
            });
        });
        window.addEventListener('swal-deleted', () => {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: '¡Eliminado!',
                text: 'El reporte fue eliminado',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                background: isDarkMode ? '#1f2937' : '#ffffff',
                color: isDarkMode ? '#e5e7eb' : '#111827',
                iconColor: isDarkMode ? '#10b981' : '#059669', 
                toast: true,
                position: 'top-end',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            });
        });
        </script>
        @endscript
    </x-filament::section>
</x-filament-panels::page>