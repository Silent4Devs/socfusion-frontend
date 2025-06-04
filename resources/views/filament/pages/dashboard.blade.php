<x-filament-panels::page>
    <div class="flex flex-col space-y-6 dark:bg-gray-900 min-h-screen p-6">
        <div class="w-full p-8 bg-gray-50 dark:bg-gray-900 ">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
               

                <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800 dark:text-white p-6 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium dark:text-gray-200">Logrhythm SIEM</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1 dark:text-white">{{ $total_logrhythm }}</h3>
                        <p class="text-green-500 text-sm mt-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        12.5%
                        </p>
                    </div>

                
                        <div class="relative bg-red-100 p-3 rounded-full w-fit" aria-label="Alarmas">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                    </div>

                </div>

                
                <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 dark:bg-gray-800 dark:text-white transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between ">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-200">PRTG</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1 dark:text-white">{{ $total_prtg }}</h3>
                            <p class="text-green-500 text-sm mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                                8.2% 
                            </p>
                        </div>
                 <div class="relative bg-purple-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 dark:bg-gray-800 dark:text-white transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium dark:text-gray-200">Alertas Críticas</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1 dark:text-white">36</h3>
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            1.1% 
                            </p>
                        </div>

                        <div class="bg-orange-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                    </div>

                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 dark:bg-gray-800 dark:text-white transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                        <p class="text-gray-500 text-sm font-medium dark:text-gray-200">
                            Reportes Nuevos
                        </p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1 dark:text-white">{{ $today_reports }}</h3>
                        <p class="text-green-500 text-sm mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            5.3%
                        </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3H9m4-12H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V9l-6-6z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Clientes</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $total_clients }} total</span>
                </div>
                
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">

                @foreach ($clients as $client)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center overflow-hidden">
                            @if (!empty($client->logo))
                                <img src="{{ asset('storage/' . $client->logo) }}"
                                    alt="{{ $client->name }} Logo"
                                    class="h-10 w-10 rounded-full object-cover">
                            @else
                                <span class="text-red-600 dark:text-red-400 text-sm font-medium">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $client->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->email ?? 'sin correo' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $client->alerts_count ?? '0' }} alertas
                        </span>
                    </div>
                </div>

                @endforeach
 
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
              
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Alertas por Hora (últimas 24h)</h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Proyección próximas 6h</span>
                            <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                        </div>
                    </div>
                    
                    <div class="h-64">
                        <canvas id="hourlyAlertsChart"></canvas>
                    </div>
                    
       
                </div>
                
            
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Últimas alertas</h2>
                            <div class="flex space-x-2">
                                <button class="p-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button class="p-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            
                            @foreach ($last_alerts as $alert)
                            <div class="flex items-start p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <div class="mt-1 mr-3 text-red-500 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $alert["alarm_rule_name"] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $alert['entity_name']}} · {{ \Carbon\Carbon::parse($alert['date_inserted'])->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
            
                            
                        </div>
                    </div>
                    
    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Últimos reportes</h2>
                        
                        <div class="space-y-4">
                            
                        @foreach ($last_reports as $report)
                            <div class="flex items-start">
                                <div class="mr-3 mt-1">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $report["title"]}}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($report["created_at"])->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                            <button onclick="window.location.href='/admin/reports'" 
                                    class="mt-4 w-full py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Ver todos los reportes
                            </button>

                    </div>
                </div>
            </div>
        </div>  
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-gradient"></script>

    <script>

        
        const logrhythmTimeline = @json($logrhythm_timeline);

        const labels = logrhythmTimeline.map(([timestamp, value]) => {
            const date = new Date(timestamp);

            const hour = date.getHours().toString().padStart(2, '0');
            const day = date.getDate();
            const today = new Date().getDate();

            const prefix = day === today ? '' : `${day} -`; 

            return `${prefix} ${hour}:00`;
        });

        const alertData = logrhythmTimeline.map(([timestamp, value]) => value);
        
        const baseIndex = 21;
        const baseValue = alertData.length > baseIndex ? alertData[baseIndex] : alertData[alertData.length - 1];

        for (let i = 1; i <= 6; i++) {
            const index = alertData.length - 20 + i;
            const baseValue = index >= 0 ? alertData[index] : alertData[0];

            const prediction = Math.max(baseValue + Math.floor(Math.random()*2)-1, 0);
            alertData.push(prediction);

            const label = `P+${i}`;
            labels.push(label);
        }

        const ctx = document.getElementById('hourlyAlertsChart').getContext('2d');
        
     
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
        
        const areaGradient = ctx.createLinearGradient(0, 0, 0, 300);
        areaGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        areaGradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

        const projectionGradient = ctx.createLinearGradient(0, 0, 0, 300);
        projectionGradient.addColorStop(0, 'rgba(250, 40, 40, 0.93)');
        projectionGradient.addColorStop(1, 'rgba(247, 85, 85, 0.1)');
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Alertas por hora',
                    data: alertData,
                    borderColor: gradient,
                    backgroundColor: areaGradient,
                    borderWidth: 3,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#3B82F6',
                    pointHoverBackgroundColor: '#2563EB',
                    fill: true,
                    segment: {
                        borderColor: ctx => {
                            return ctx.p1DataIndex >= 24 ? 'rgba(247, 85, 85, 0.8)' : undefined;
                        },
                        backgroundColor: ctx => {
                            return ctx.p1DataIndex >= 24 ? projectionGradient : undefined;
                        }
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} alertas a las ${context.label}`;
                            },
                            title: function(context) {
                                return context[0].dataIndex >= 24 ? 
                                    `PROYECCIÓN: ${context[0].label}` : 
                                    `Alertas a las ${context[0].label}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 12,
                            callback: function(value) {
                             
                                if (value % 2 === 0) {
                                    return this.getLabelForValue(value);
                                }
                                return '';
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(209, 213, 219, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            precision: 0
                        },
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                elements: {
                    line: {
                        cubicInterpolationMode: 'monotone'
                    },
                    point: {
                        hoverBorderWidth: 2,
                        hoverBorderColor: '#fff'
                    }
                }
            }
        });
        
        const applyDarkMode = (isDark) => {
            const gridColor = isDark ? 'rgba(75, 85, 99, 0.3)' : 'rgba(209, 213, 219, 0.3)';
            const tickColor = isDark ? '#D1D5DB' : '#6B7280';
            
            chart.options.scales.x.ticks.color = tickColor;
            chart.options.scales.y.ticks.color = tickColor;
            chart.options.scales.y.grid.color = gridColor;
            
            chart.update();
        };
        
     
        const darkModeObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    const isDark = document.documentElement.classList.contains('dark');
                    applyDarkMode(isDark);
                }
            });
        });
        
        darkModeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>

    
</x-filament-panels::page>