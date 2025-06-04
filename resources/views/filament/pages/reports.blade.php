<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Reportes de Alertas
        </x-slot>
        
        <x-filament::grid default="1" sm="2" md="3" class="gap-4">
            
            @foreach($reports as $report)
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
                            <img 
                              src="{{ url('/api/reports/' . $report['id'] . '/preview-image') }}"
                                alt="Loading preview..."
                                class="w-auto h-auto object-cover mx-auto rounded"
                            />

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
                            
                            <button class="mt-2 px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-500/20 transition-colors">
                                Reintentar
                            </button>
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