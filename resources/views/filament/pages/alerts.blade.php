<x-filament-panels::page>
    
    <script>
    function handleClassification(level, id, type) {
        console.log(`Clasificación seleccionada: ${level}, para la alarma con ID: ${id}`);
        
    };
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <div class="space-y-4">

        <div>
            <input 
                type="text" 
                placeholder="Buscar..." 
                class="p-2 border rounded"
                wire:model.live="search"
            />
        </div>
            @php
                $alarmsLeft = [];
                $alarmsRight = [];

                foreach ($filteredAlarms as $i => $alarm) {
                    if ($i % 2 === 0) {
                        $alarmsLeft[] = $alarm;
                    } else {
                        $alarmsRight[] = $alarm;
                    }
                }
            @endphp

        <div class="flex gap-4">

            <div class="flex flex-col gap-4 md:w-1/2">
                @foreach ($alarmsLeft as $alarm)

                <div x-data="{ showDetails: false }" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 space-y-3 border border-gray-100 dark:border-gray-700 transition-all duration-300 ">
                    <div class="flex items-start justify-between">
                    <div>
                    @if ($alarm['alarm_type'] == 'logrhythm')

                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($alarm['date_inserted'])->format('d/m/Y H:i') }}
                        </div>
                    @else

                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{
                            \Carbon\Carbon::createFromFormat(
                                'd/m/Y h:i:s A',
                                str_replace(['a. m.', 'p. m.'], ['AM', 'PM'], $alarm['datetime'])
                            )->format('d/m/Y H:i')
                        }}
                    </div>

                    @endif

                        @switch($alarm['model_classification'])
                            @case('High')
                                @php $color = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'; @endphp
                                @break
                            @case('Medium')
                                @php $color = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300'; @endphp
                                @break
                            @case('Low')
                                @php $color = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'; @endphp
                                @break
                            @default
                                @php $color = 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300'; @endphp
                        @endswitch

                        <div class="mt-1">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                               {{ $alarm['model_classification'] }}
                            </span>
                        </div>
  

                    </div>
                        <div class="text-xs px-2 py-1 text-red-600">
                        @if ($alarm['alarm_type'] == 'logrhythm')

                            @if ($alarm['count'] > 1)

                            {{ $alarm['count'] }}

                            @endif
                        @endif
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-600 dark:text-gray-300 ml-2">
                            @if ($alarm['alarm_type'] == 'logrhythm')

                            LogRhythm

                            @else

                            PRTG

                            @endif
                            </span>
  
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5 my-auto">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        </div>
                        <p class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        @if ($alarm['alarm_type'] == 'logrhythm')

                            {{ $alarm['alarm_rule_name'] }}

                        @else

                            {{ $alarm['message_raw'] }}

                        @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <button @click="showDetails = !showDetails" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors group">
                            Ver detalles
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                            
                        @if(empty($alarm['real_classification']))
                        <div x-data="{ open: false, classification: '' }" class="relative" x-init="
                            classification = '{{ $alarm['real_classification'] ?? '' }}';
                        ">
                            <template x-if="!classification">
                                <div>
                                    <button @click="open = !open" class="flex items-center px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm">
                                        Clasificar
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div 
                                    x-show="open" 
                                    x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 scale-95" 
                                    x-transition:enter-end="opacity-100 scale-100" 
                                    x-transition:leave="transition ease-in duration-150" 
                                    x-transition:leave-start="opacity-100 scale-100" 
                                    x-transition:leave-end="opacity-0 scale-95" 
                                    @click.away="open = false" 
                                    class="absolute right-0 z-10 mt-1 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden ring-1 ring-black/5"
                                    >
                                        <button 
                                            @click="classification = 'High'; open = false; handleClassification('High', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 11-8 8 8 8 0 018-8zm0 11a1 1 0 100 2 1 1 0 000-2zm0-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z"/></svg>
                                            High
                                        </button>
                                        <button 
                                            @click="classification = 'Medium'; open = false; handleClassification('Medium', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 11-8 8 8 8 0 018-8zm0 12a1 1 0 110 2 1 1 0 010-2zm-.293-7.707a1 1 0 011.415 1.414L10.414 9l.708.707a1 1 0 01-1.415 1.414L9 10.414l-.707.707a1 1 0 01-1.415-1.414L8.586 9l-.708-.707a1 1 0 011.415-1.414L9 7.586l.707-.707z"/></svg>
                                            Medium
                                        </button>
                                        <button 
                                            @click="classification = 'Low'; open = false; handleClassification('Low', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"/></svg>
                                            Low
                                        </button>
                                    </div>

                                </div>
                            </template>

                            <template x-if="classification">
                                <div class="mt-2 px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300">
                                    Clasificada: <strong x-text="classification"></strong>
                                </div>
                            </template>
                        </div>
                        @else
                            <div class="mt-2 px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 dark:border-gray-600">
                                Clasificada: <strong>$alarm['real_classification']</strong>
                            </div>
                        @endif


                    </div>
                                   
                    <div x-show="showDetails" x-transition class="text-sm text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-gray-600 pt-3 space-y-2">
                        @if ($alarm['alarm_type'] == 'logrhythm')
                            @if (!empty($alarm['alarm_status_name']))
                                <p><strong>Status:</strong> {{ $alarm['alarm_status_name'] }}</p>
                            @endif

                            @if (!empty($alarm['alarm_type']))
                                <p><strong>Type:</strong> {{ $alarm['alarm_type'] }}</p>
                            @endif

                            @if (!empty($alarm['classification_type_name']))
                                <p><strong>Classification Type:</strong> {{ $alarm['classification_type_name'] }}</p>
                            @endif

                            @if (!empty($alarm['common_event_id']))
                                <p><strong>Common Event ID:</strong> {{ $alarm['common_event_id'] }}</p>
                            @endif

                            @if (!empty($alarm['common_event_name']))
                                <p><strong>Common Event Name:</strong> {{ $alarm['common_event_name'] }}</p>
                            @endif

                            @if (!empty($alarm['date_inserted']))
                                <p><strong>Date Inserted:</strong> {{ $alarm['date_inserted'] }}</p>
                            @endif

                            @if (!empty($alarm['entity_name']))
                                <p><strong>Entity:</strong> {{ $alarm['entity_name'] }}</p>
                            @endif

                            @if (!empty($alarm['event_count']))
                                <p><strong>Event Count:</strong> {{ $alarm['event_count'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_entity_name']))
                                <p><strong>Impacted Entity:</strong> {{ $alarm['impacted_entity_name'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_host_name']))
                                <p><strong>Impacted Host:</strong> {{ $alarm['impacted_host_name'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_ip']))
                                <p><strong>Impacted IP:</strong> {{ $alarm['impacted_ip'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_port']) && $alarm['impacted_port'] != -1)
                                <p><strong>Impacted Port:</strong> {{ $alarm['impacted_port'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_zone']) && $alarm['impacted_zone'] !== 'Unknown')
                                <p><strong>Impacted Zone:</strong> {{ $alarm['impacted_zone'] }}</p>
                            @endif

                            @if (!empty($alarm['log_source_host_name']))
                                <p><strong>Log Source Host:</strong> {{ $alarm['log_source_host_name'] }}</p>
                            @endif

                            @if (!empty($alarm['log_source_type_name']))
                                <p><strong>Log Source Type:</strong> {{ $alarm['log_source_type_name'] }}</p>
                            @endif

                            @if (!empty($alarm['message_id']))
                                <p><strong>Message ID:</strong> {{ $alarm['message_id'] }}</p>
                            @endif

                            @if (!empty($alarm['person_id']))
                                <p><strong>Person ID:</strong> {{ $alarm['person_id'] }}</p>
                            @endif

                            @if (!empty($alarm['priority']))
                                <p><strong>Priority:</strong> {{ $alarm['priority'] }}</p>
                            @endif

                            @if (!is_null($alarm['rbp_avg']))
                                <p><strong>RBP Avg:</strong> {{ $alarm['rbp_avg'] }}</p>
                            @endif

                            @if (!is_null($alarm['rbp_max']))
                                <p><strong>RBP Max:</strong> {{ $alarm['rbp_max'] }}</p>
                            @endif

                            @if (!empty($alarm['severity']))
                                <p><strong>Severity:</strong> {{ $alarm['severity'] }}</p>
                            @endif

                            @if (!empty($alarm['smart_response_actions']))
                                <p><strong>Smart Response Actions:</strong> {{ $alarm['smart_response_actions'] }}</p>
                            @endif

                            @if (!empty($alarm['status']))
                                <p><strong>Status:</strong> {{ $alarm['status'] }}</p>
                            @endif
                        @else
                            
                            @if (!empty($alarm['name']))
                                <p><strong>Nombre:</strong> {{ $alarm['name'] }}</p>
                            @endif

                            @if (!empty($alarm['tags']))
                                <p><strong>Tags:</strong> {{ $alarm['tags'] }}</p>
                            @endif

                            @if (!empty($alarm['status']))
                                <p><strong>Estado:</strong> {{ $alarm['status'] }}</p>
                            @endif

                            @if (!empty($alarm['objid']))
                                <p><strong>ID de objeto:</strong> {{ $alarm['objid'] }}</p>
                            @endif

                            @if (!empty($alarm['sensor']))
                                <p><strong>Sensor:</strong> {{ $alarm['sensor'] }}</p>
                            @endif

                            @if (!empty($alarm['message']))
                                <p><strong>Mensaje:</strong> {!! $alarm['message'] !!}</p> {{-- Usa {!! !!} si quieres renderizar HTML --}}
                            @endif

                            @if (!empty($alarm['datetime']))
                                <p><strong>Fecha y hora:</strong> {{ $alarm['datetime'] }}</p>
                            @endif

                            @if (!empty($alarm['type']))
                                <p><strong>Tipo:</strong> {{ $alarm['type'] }}</p>
                            @endif

                            @if (!empty($alarm['priority']))
                                <p><strong>Prioridad:</strong> {{ $alarm['priority'] }}</p>
                            @endif

                            @if (!empty($alarm['parent']) && $alarm['parent'] !== 'Ninguno')
                                <p><strong>Padre:</strong> {{ $alarm['parent'] }}</p>
                            @endif

                            @if (!empty($alarm['active']))
                                <p><strong>Activo:</strong> {{ $alarm['active'] ? 'Sí' : 'No' }}</p>
                            @endif
                        @endif
                        <div class="pt-3 flex justify-end">
                        @if ($alarm['alarm_type'] == 'logrhythm')
                            <button 
                                wire:click="generateReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['common_event_name'] }} - LogRhythm')"
                                @click="showDetails = false"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @else
                            <button 
                                wire:click="generateReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['message_raw'] }} - PRTG')"
                                @click="showDetails = false"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @endif
                        </div>
                    </div> 

                
                </div>

                @endforeach 
            </div>
            <div class="flex flex-col gap-4 md:w-1/2">
                @foreach ($alarmsRight as $alarm)

                <div x-data="{ showDetails: false }" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 space-y-3 border border-gray-100 dark:border-gray-700 transition-all duration-300 ">
                    <div class="flex items-start justify-between">
                    <div>
                    @if ($alarm['alarm_type'] == 'logrhythm')

                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($alarm['date_inserted'])->format('d/m/Y H:i') }}
                        </div>
                    @else

                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{
                            \Carbon\Carbon::createFromFormat(
                                'd/m/Y h:i:s A',
                                str_replace(['a. m.', 'p. m.'], ['AM', 'PM'], $alarm['datetime'])
                            )->format('d/m/Y H:i')
                        }}
                    </div>

                    @endif

                        @switch($alarm['model_classification'])
                            @case('High')
                                @php $color = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'; @endphp
                                @break
                            @case('Medium')
                                @php $color = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300'; @endphp
                                @break
                            @case('Low')
                                @php $color = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'; @endphp
                                @break
                            @default
                                @php $color = 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300'; @endphp
                        @endswitch

                        <div class="mt-1">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                               {{ $alarm['model_classification'] }}
                            </span>
                        </div>
  

                    </div>
                        <div class="text-xs px-2 py-1 text-red-600">
                        @if ($alarm['alarm_type'] == 'logrhythm')

                            @if ($alarm['count'] > 1)

                            {{ $alarm['count'] }}

                            @endif
                        @endif
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-600 dark:text-gray-300 ml-2">
                            @if ($alarm['alarm_type'] == 'logrhythm')

                            LogRhythm

                            @else

                            PRTG

                            @endif
                            </span>
  
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5 my-auto">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        </div>
                        <p class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        @if ($alarm['alarm_type'] == 'logrhythm')

                            {{ $alarm['alarm_rule_name'] }}

                        @else

                            {{ $alarm['message_raw'] }}

                        @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <button @click="showDetails = !showDetails" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors group">
                            Ver detalles
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                            
                        @if(empty($alarm['real_classification']))
                        <div x-data="{ open: false, classification: '' }" class="relative" x-init="
                            classification = '{{ $alarm['real_classification'] ?? '' }}';
                        ">
                            <template x-if="!classification">
                                <div>
                                    <button @click="open = !open" class="flex items-center px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm">
                                        Clasificar
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                            <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-200" 
                            x-transition:enter-start="opacity-0 scale-95" 
                            x-transition:enter-end="opacity-100 scale-100" 
                            x-transition:leave="transition ease-in duration-150" 
                            x-transition:leave-start="opacity-100 scale-100" 
                            x-transition:leave-end="opacity-0 scale-95" 
                            @click.away="open = false" 
                            class="absolute right-0 z-10 mt-1 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden ring-1 ring-black/5"
                            >
                            <button 
                                @click="classification = 'High'; open = false; handleClassification('High', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition-colors duration-150"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 11-8 8 8 8 0 018-8zm0 11a1 1 0 100 2 1 1 0 000-2zm0-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z"/></svg>
                                High
                            </button>
                            <button 
                                @click="classification = 'Medium'; open = false; handleClassification('Medium', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-gray-700 transition-colors duration-150"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 11-8 8 8 8 0 018-8zm0 12a1 1 0 110 2 1 1 0 010-2zm-.293-7.707a1 1 0 011.415 1.414L10.414 9l.708.707a1 1 0 01-1.415 1.414L9 10.414l-.707.707a1 1 0 01-1.415-1.414L8.586 9l-.708-.707a1 1 0 011.415-1.414L9 7.586l.707-.707z"/></svg>
                                Medium
                            </button>
                            <button 
                                @click="classification = 'Low'; open = false; handleClassification('Low', {{ $alarm['id'] ?? 'null' }}, '{{ $alarm['alarm_type'] }}')" 
                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-gray-700 transition-colors duration-150"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"/></svg>
                                Low
                            </button>
                            </div>

                                </div>
                            </template>

                            <template x-if="classification">
                                <div class="mt-2 px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300">
                                    Clasificada: <strong x-text="classification"></strong>
                                </div>
                            </template>
                        </div>
                        @else
                            <div class="mt-2 px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 dark:border-gray-600">
                                Clasificada: <strong>$alarm['real_classification']</strong>
                            </div>
                        @endif


                    </div>
                                   
                    <div x-show="showDetails" x-transition class="text-sm text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-gray-600 pt-3 space-y-2">
                        @if ($alarm['alarm_type'] == 'logrhythm')
                            @if (!empty($alarm['alarm_status_name']))
                                <p><strong>Status:</strong> {{ $alarm['alarm_status_name'] }}</p>
                            @endif

                            @if (!empty($alarm['alarm_type']))
                                <p><strong>Type:</strong> {{ $alarm['alarm_type'] }}</p>
                            @endif

                            @if (!empty($alarm['classification_type_name']))
                                <p><strong>Classification Type:</strong> {{ $alarm['classification_type_name'] }}</p>
                            @endif

                            @if (!empty($alarm['common_event_id']))
                                <p><strong>Common Event ID:</strong> {{ $alarm['common_event_id'] }}</p>
                            @endif

                            @if (!empty($alarm['common_event_name']))
                                <p><strong>Common Event Name:</strong> {{ $alarm['common_event_name'] }}</p>
                            @endif

                            @if (!empty($alarm['date_inserted']))
                                <p><strong>Date Inserted:</strong> {{ $alarm['date_inserted'] }}</p>
                            @endif

                            @if (!empty($alarm['entity_name']))
                                <p><strong>Entity:</strong> {{ $alarm['entity_name'] }}</p>
                            @endif

                            @if (!empty($alarm['event_count']))
                                <p><strong>Event Count:</strong> {{ $alarm['event_count'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_entity_name']))
                                <p><strong>Impacted Entity:</strong> {{ $alarm['impacted_entity_name'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_host_name']))
                                <p><strong>Impacted Host:</strong> {{ $alarm['impacted_host_name'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_ip']))
                                <p><strong>Impacted IP:</strong> {{ $alarm['impacted_ip'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_port']) && $alarm['impacted_port'] != -1)
                                <p><strong>Impacted Port:</strong> {{ $alarm['impacted_port'] }}</p>
                            @endif

                            @if (!empty($alarm['impacted_zone']) && $alarm['impacted_zone'] !== 'Unknown')
                                <p><strong>Impacted Zone:</strong> {{ $alarm['impacted_zone'] }}</p>
                            @endif

                            @if (!empty($alarm['log_source_host_name']))
                                <p><strong>Log Source Host:</strong> {{ $alarm['log_source_host_name'] }}</p>
                            @endif

                            @if (!empty($alarm['log_source_type_name']))
                                <p><strong>Log Source Type:</strong> {{ $alarm['log_source_type_name'] }}</p>
                            @endif

                            @if (!empty($alarm['message_id']))
                                <p><strong>Message ID:</strong> {{ $alarm['message_id'] }}</p>
                            @endif

                            @if (!empty($alarm['person_id']))
                                <p><strong>Person ID:</strong> {{ $alarm['person_id'] }}</p>
                            @endif

                            @if (!empty($alarm['priority']))
                                <p><strong>Priority:</strong> {{ $alarm['priority'] }}</p>
                            @endif

                            @if (!is_null($alarm['rbp_avg']))
                                <p><strong>RBP Avg:</strong> {{ $alarm['rbp_avg'] }}</p>
                            @endif

                            @if (!is_null($alarm['rbp_max']))
                                <p><strong>RBP Max:</strong> {{ $alarm['rbp_max'] }}</p>
                            @endif

                            @if (!empty($alarm['severity']))
                                <p><strong>Severity:</strong> {{ $alarm['severity'] }}</p>
                            @endif

                            @if (!empty($alarm['smart_response_actions']))
                                <p><strong>Smart Response Actions:</strong> {{ $alarm['smart_response_actions'] }}</p>
                            @endif

                            @if (!empty($alarm['status']))
                                <p><strong>Status:</strong> {{ $alarm['status'] }}</p>
                            @endif
                        @else
                            
                            @if (!empty($alarm['name']))
                                <p><strong>Nombre:</strong> {{ $alarm['name'] }}</p>
                            @endif

                            @if (!empty($alarm['tags']))
                                <p><strong>Tags:</strong> {{ $alarm['tags'] }}</p>
                            @endif

                            @if (!empty($alarm['status']))
                                <p><strong>Estado:</strong> {{ $alarm['status'] }}</p>
                            @endif

                            @if (!empty($alarm['objid']))
                                <p><strong>ID de objeto:</strong> {{ $alarm['objid'] }}</p>
                            @endif

                            @if (!empty($alarm['sensor']))
                                <p><strong>Sensor:</strong> {{ $alarm['sensor'] }}</p>
                            @endif

                            @if (!empty($alarm['message']))
                                <p><strong>Mensaje:</strong> {!! $alarm['message'] !!}</p> 
                            @endif

                            @if (!empty($alarm['datetime']))
                                <p><strong>Fecha y hora:</strong> {{ $alarm['datetime'] }}</p>
                            @endif

                            @if (!empty($alarm['type']))
                                <p><strong>Tipo:</strong> {{ $alarm['type'] }}</p>
                            @endif

                            @if (!empty($alarm['priority']))
                                <p><strong>Prioridad:</strong> {{ $alarm['priority'] }}</p>
                            @endif

                            @if (!empty($alarm['parent']) && $alarm['parent'] !== 'Ninguno')
                                <p><strong>Padre:</strong> {{ $alarm['parent'] }}</p>
                            @endif

                            @if (!empty($alarm['active']))
                                <p><strong>Activo:</strong> {{ $alarm['active'] ? 'Sí' : 'No' }}</p>
                            @endif
                        @endif
                        
                        <div class="pt-3 flex justify-end">
                            @if ($alarm['alarm_type'] == 'logrhythm')
                            <button 
                                wire:click="generateReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['common_event_name'] }} - LogRhythm')"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @else
                            <button 
                                wire:click="generateReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['message_raw'] }} - PRTG')"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @endif
                        </div>
            
                    </div> 

                
                </div>

                @endforeach
            </div>
        </div>

    </div>

    @script
    <script>

    window.addEventListener('new-report', function(event) {
        window.showToastSuccess(event.detail.message);
    });

    const socket = new WebSocket("ws://192.168.40.1:8000/alarms");
    const notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [
                {
                    className: 'text-black rounded',
                    type: 'warning',
                    background: 'white',
                    icon: {
                        className: 'material-icons text-red-500 mt-2',
                        tagName: 'i',
                        text: 'warning',
                        color: 'red'
                    }
                },
            ]
            });
    socket.onopen = function () {
        console.log("Conectado al WebSocket");
    };

    socket.onmessage = function (event) {
        const data = JSON.parse(event.data);
        console.log(data);
        window.showToastInfo("Nuevas alarmas");
        $wire.dispatch('update_Alarms', {data: data});
    };


    socket.onclose = function () {
        console.log("WebSocket cerrado");
    };

    socket.onerror = function (error) {
        console.error("Error en WebSocket:", error);
    };
    </script>
    @endscript

</x-filament-panels::page>
