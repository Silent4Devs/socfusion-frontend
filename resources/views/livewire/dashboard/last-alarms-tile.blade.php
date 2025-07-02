<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6" wire:poll.5s="updateVisibleAlerts">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Últimas alertas</h2>
        <div class="flex space-x-2">
    <button wire:click="previousAlerts" class="p-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300" @if($current_alert_page === 1) disabled @endif>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
    <button wire:click="nextAlerts" class="p-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300" @if($current_alert_page >= ceil(count($all_alerts)/$alerts_per_page)) disabled @endif>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
        </div>
    </div>


    <span class="bg-green-50 dark:bg-green-900/20 opacity-1 h-[50px]"></span>
    <div class="space-y-3">
            
        @foreach ($visible_alerts as $alert)
            @php
                switch(strtolower($alert['model_classification'] ?? '')) {
                    case 'high':
                        $bgColor = 'bg-red-50 dark:bg-red-800/20';
                        $iconColor = 'text-red-500 dark:text-red-400';
                        break;
                    case 'medium':
                        $bgColor = 'bg-yellow-50 dark:bg-yellow-800/20';
                        $iconColor = 'text-yellow-500 dark:text-yellow-400';
                        break;
                    case 'low':
                        $bgColor = 'bg-green-50 dark:bg-green-800/20';
                        $iconColor = 'text-green-500 dark:text-green-400';
                        break;
                    default:
                        $bgColor = 'bg-gray-50 dark:bg-gray-800/20';
                        $iconColor = 'text-gray-400 dark:text-gray-300';
                }
            @endphp
        
        <div class="flex items-center p-3 rounded-lg {{ $bgColor }}" style="min-height: 70px;">
            <div class="mt-1 mr-3 {{ $iconColor }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
                @if (!empty($alert['alarm_rule_name']) && !empty($alert['entity_name']) && !empty($alert['date_inserted']))
                    <p class="font-medium text-xs text-gray-800 dark:text-white">{{ $alert['alarm_rule_name'] }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300">
                        {{ $alert['entity_name'] }} · {{ \Carbon\Carbon::parse($alert['date_inserted'])->diffForHumans() }}
                    </p>
                @else
                    <p class="text-xs text-gray-800 dark:text-white">{{ !empty($alert['message_raw']) ? $alert['message_raw'] : 'Alerta sin información' }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300 ml-6">
                        {{ \Carbon\Carbon::parse($alert['created_at'] ?? now())->diffForHumans() }}
                    </p>
                @endif
        </div>
        @endforeach


        
    </div>
</div>
