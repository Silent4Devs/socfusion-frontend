<x-filament-panels::page>
    
    @script
    <script>

        window.handleClassification = async function(level, id, type) {
            console.log(`Clasificación seleccionada: ${level}, para la alarma con ID: ${id}`);
            const url = "{{ $iaServer }}/alarms/classify";
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        type: type,
                        alarm_id: id,
                        classification: level
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    window.showToastSuccess(`Alerta clasificada correctamente`);
                } else {
                    const errorData = await response.json();
                    window.showToastError(`Hubo un error al clasificar la alerta`);
                }
            } catch (error) {
                alert('Error de red al intentar clasificar la alarma.');
                console.error('Network error:', error);
            }
        }

        let after = null;

        async function fetchAlarms(limit = 50) {
            console.log("Fetching data...");
            let url = `{{ $iaServer }}/alarms?limit=${limit}`;
            if (after) {
                url += `&after=${encodeURIComponent(after)}`;
            }

            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log(data);
                if (data.length > 0) {
                    const first = data[0];
                    after = first.date_inserted || first.created_at;
                    if (data.length === 1){
                        window.showToastWarning(`Hay 1 nueva alerta.`);
                    }else{
                        window.showToastWarning(`Hay ${data.length} nuevas alertas.`);
                    }
                }
                

                $wire.updateNewAlarms(data);
                return data;

            } catch (err) {
                console.error('Error fetching alarms:', err);
                return [];
            }
        }

        fetchAlarms(100);

        setInterval(() => {
            fetchAlarms();
        }, 10000);

    </script>
    @endscript

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <div class="space-y-4" x-data="{ openReportModal: false,  
            createTicket: false,
            commentsTicket: '',
            assingTicket: null,
            comments: '',
            evidence: null,
            alarmId: null,
            alarmType: null,
            alarmMessage: null,
            previewEvidence: null,
            handleFileUpload(event) {
                const file = event.target.files[0];
                this.evidence = file;
                if (file && file.type.startsWith('image/')) {
                    this.previewEvidence = URL.createObjectURL(file);
                } else {
                    this.previewEvidence = null;
                }
            },
            confirmReport(id, type, message) {
                this.alarmId = id;
                this.alarmType = type;
                this.alarmMessage = message;
                this.openReportModal = true;
            },

            submitReport() {
                $wire.generateReport(this.alarmId, this.alarmType, this.alarmMessage, this.comments, this.createTicket, this.commentsTicket, this.assingTicket);

                this.resetForm();
                this.openReportModal = false;
            },

            resetForm() {
                this.comments = '';
                this.evidence = null;
                this.previewEvidence = null;
                this.alarmId = null;
                this.alarmType = '';
                this.alarmMessage = '';
            }
                
            }">

        <div>
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <input 
                    type="text" 
                    placeholder="Buscar..." 
                    class="flex-1 p-2 border rounded bg-white text-black dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600"
                    wire:model.live="search"
                />

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full md:w-auto">
                    <select 
                        class="p-2 border rounded bg-white dark:bg-gray-800 dark:text-white dark:border-gray-600"
                        wire:model.live="alarmType"
                    >
                        <option value="">Todos los sistemas</option>
                        <option value="logrhythm">LogRhythm</option>
                        <option value="prtg">PRTG</option>
                    </select>

                    <select 
                        class="p-2 border rounded bg-white dark:bg-gray-800 dark:text-white dark:border-gray-600"
                        wire:model.live="classification"
                    >
                        <option value="">Todas las clasificaciones</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>    
                    </select>

                    <!-- <select 
                        class="p-2 border rounded bg-white dark:bg-gray-800 dark:text-white dark:border-gray-600"
                        wire:model.live="client"
                    >
                        <option value="">Todos los clientes</option>
                        <option value="client1">Cliente 1</option>
                        <option value="client2">Cliente 2</option>
                        <option value="client3">Cliente 3</option>
                    </select>             -->
                </div>
            </div>
        </div>
 

        <div class="flex gap-4">

           @php
                $alarmsLeft = [];
                $alarmsRight = [];

                foreach ($visibleAlerts as $i => $alarm) {
                    if ($i % 2 === 0) {
                        $alarmsLeft[] = $alarm;
                    } else {
                        $alarmsRight[] = $alarm;
                    }
                }
            @endphp

            <div class="flex flex-col gap-4 md:w-1/2">
                @foreach ($alarmsLeft as $alarm)

                <div x-data="{ showDetails: false }" wire:key="alarm-{{ $alarm['alarm_type'] }}-{{ $alarm['id'] }}" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 space-y-3 border border-gray-100 dark:border-gray-700 transition-all duration-300 ">
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

                        @php
                            $classification_show = $alarm['real_classification'] ?? $alarm['model_classification'];

                            switch ($classification_show) {
                                case 'High':
                                    $color = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300';
                                    break;
                                case 'Medium':
                                    $color = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300';
                                    break;
                                case 'Low':
                                    $color = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300';
                                    break;
                                default:
                                    $color = 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300';
                            }

                            $isManual = !empty($alarm['real_classification']);
                        @endphp

                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                @if ($isManual)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a4 4 0 100 8 4 4 0 000-8zm-6 14a6 6 0 1112 0H4z" />
                                    </svg>
                                @endif
                                {{ $classification_show }}
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

                            @if (!empty($alarm['message_raw']))
                                {{ $alarm['message_raw'] }}
                            @else
                                Alerta sin mensaje
                            @endif

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
                        <div 
                                x-data="{
                                    open: false, 
                                    classification: ''
                                }" 
                                class="relative"
                            >
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
                                @click="confirmReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['common_event_name'] }} - LogRhythm'); showDetails = false"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @else
                            <button 
                                @click="confirmReport(
                                    {{ $alarm['id'] }},
                                    '{{ $alarm['alarm_type'] }}',
                                    '{{ str_replace("'", "\\'", $alarm['message_raw']) }}'
                                ); showDetails = false"
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

                <div x-data="{ showDetails: false }" wire:key="alarm-{{ $alarm['alarm_type'] }}-{{ $alarm['id'] }}" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-5 space-y-3 border border-gray-100 dark:border-gray-700 transition-all duration-300 ">
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

                        @php
                            $classification_show = $alarm['real_classification'] ?? $alarm['model_classification'];

                            switch ($classification_show) {
                                case 'High':
                                    $color = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300';
                                    break;
                                case 'Medium':
                                    $color = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300';
                                    break;
                                case 'Low':
                                    $color = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300';
                                    break;
                                default:
                                    $color = 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300';
                            }

                            $isManual = !empty($alarm['real_classification']);
                        @endphp

                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                @if ($isManual)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a4 4 0 100 8 4 4 0 000-8zm-6 14a6 6 0 1112 0H4z" />
                                    </svg>
                                @endif
                                {{ $classification_show }}
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

                            @if (!empty($alarm['message_raw']))
                                {{ $alarm['message_raw'] }}
                            @else
                                Alerta sin mensaje
                            @endif

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
                        <div 
                                x-data="{
                                    open: false, 
                                    classification: ''
                                }" 
                                class="relative"
                            >
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
                                @click="confirmReport({{ $alarm['id'] }}, '{{ $alarm['alarm_type'] }}', '{{ $alarm['common_event_name'] }} - LogRhythm'); showDetails = false"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 border border-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span>Generar reporte</span>
                            </button>
                            @else
                            <button 
                             @click="confirmReport(
                                    {{ $alarm['id'] }},
                                    '{{ $alarm['alarm_type'] }}',
                                    '{{ str_replace("'", "\\'", $alarm['message_raw']) }}'
                                ); showDetails = false"
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

            <div class="flex items-center justify-center gap-2 my-6">
                <button
                    wire:click="previousPage"
                    @disabled($page <= 1)
                    class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition disabled:opacity-30 disabled:pointer-events-none"
                    {{ $page <= 1 ? 'disabled' : '' }}
                    aria-label="Página anterior"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span class="text-sm font-mono text-gray-500 dark:text-gray-300 select-none">
                    {{ $page }}
                </span>
                <button
                    wire:click="nextPage"
                    @disabled($page >= ceil(count($filteredAlarms) / $perPage))
                    class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition disabled:opacity-30 disabled:pointer-events-none"
                    {{ $page >= ceil(count($filteredAlarms) / $perPage) ? 'disabled' : '' }}
                    aria-label="Página siguiente"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

        <div x-show="openReportModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div @click="openReportModal = false" 
                class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
            
            <div class="relative w-full max-w-md rounded-xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-600/30 shadow-2xl overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-blue-500 to-cyan-400"></div>
                
            <div class="p-6 space-y-6">
                <h3 class="text-xl font-light tracking-wide text-gray-800 dark:text-white/90">
                    <span class="font-medium">Nuevo</span> Reporte 
                </h3>
                
                <div class="space-y-3">
                    <div class="group relative">
                        <input x-model="comments" 
                            type="text" 
                            class="w-full bg-transparent border-0 border-b border-gray-300 dark:border-gray-500 focus:border-blue-500 focus:ring-0 px-0 py-2 text-gray-800 dark:text-white/90 placeholder-transparent peer"
                            placeholder=" " />
                        <label 
                            class="pointer-events-none absolute left-0 -top-3.5 text-gray-500 dark:text-gray-400 text-sm transition-all 
                                peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 
                                peer-focus:-top-3.5 peer-focus:text-blue-500 peer-focus:text-sm">
                            Comentarios adicionales  
                            <small class="ml-1 text-xs text-gray-400 dark:text-gray-500 font-normal">(opcional)</small>
                        </label>

                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-500 transition-all duration-300 group-focus-within:w-full"></div>
                    </div>
                    
                    <div class="group">
                        <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Evidencia visual <small class="ml-1 text-xs text-gray-400 dark:text-gray-500 font-normal">(opcional)</small></label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 transition-all group-hover:border-blue-400 group-focus-within:border-blue-500">
                            <div x-show="!previewEvidence" class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium text-blue-500 hover:text-blue-400 cursor-pointer">Haz clic para subir</span>
                                    <span class="hidden sm:inline"> o arrastra una imagen</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG (Max. 5MB)</p>
                            </div>
                            <input @change="handleFileUpload" wire:model="evidence" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            
                            <div x-show="previewEvidence" class="relative">
                                <img :src="previewEvidence" alt="Preview" class="rounded-md w-full h-32 object-cover border border-gray-200 dark:border-gray-600">
                                <button @click="evidence = null; previewEvidence = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer select-none mb-4">
                        <input
                            type="checkbox"
                            x-model="createTicket"
                            class="form-checkbox h-5 w-5 rounded text-primary-500 dark:text-primary-400 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 transition duration-150"
                        >
                        <span class="text-gray-700 dark:text-gray-200 font-medium">Crear ticket</span>
                    </label>
                <div x-show="createTicket" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="p-2 mt-0">
            
                        <div class="mb-3">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Comentarios para el ticket (opcional)</label>
                            <textarea
                                x-model="commentsTicket"
                                class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                rows="3"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Asignar a*</label>
                            <select
                                x-model="assingTicket"
                                class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="">Selecciona usuario</option>
                                @foreach($assignees as $user)
                                <option value="$user">{{ $user }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
                        
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/50 flex justify-end space-x-3">
                    <button 
                        @click="openReportModal = false; resetForm(); createTicket = false" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition-colors">
                        Cancelar
                    </button>

                    <button 
                        @click="submitReport(); openReportModal = false" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md transition-colors shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Enviar Reporte
                    </button>
                </div>
            </div>
        </div>

    </div>

    @script
    <script>

    window.addEventListener('new-report', function(event) {
        window.showToastSuccess(event.detail.message);
    });

    window.addEventListener('new-issue', function(event) {
        window.showToastError(event.detail.message);
    });

    </script>
    @endscript

</x-filament-panels::page>
