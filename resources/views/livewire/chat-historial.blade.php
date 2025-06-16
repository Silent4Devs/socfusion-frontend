<div x-data="{ isOpen: false }" 
     @mouseenter="isOpen = true" 
     @mouseleave="isOpen = false"
     class="relative inline-block w-auto">
    <button class="flex items-center justify-end space-x-1 text-indigo-400 hover:text-indigo-300 transition-colors duration-200">
        <span>Historial</span>
        <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 transform transition-transform duration-200"
            :class="{ 'rotate-180': isOpen }"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-12"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-12"
        class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 nded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-y-auto max-h-96">    
        
        <div class="max-h-80 overflow-y-auto bg-white dark:bg-gray-800">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($chats as $chat)
                <div @click="isOpen = false; window.getConversation({{ $chat['id'] }})" class="px-4 py-3 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/90 transition-colors duration-200 cursor-pointer mx-2 my-1">
                    <div class="flex justify-between items-center">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100 truncate text-[15px]">{{ $chat['title'] }}</h4>
                        </div>
                        <div class="flex flex-col items-end ml-3">
                            <span class="text-xs font-medium text-blue-600 dark:text-blue-300 whitespace-nowrap">{{ \Carbon\Carbon::parse($chat['created_at'])->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
        
    </div>

    <style>
        .thin-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .thin-scrollbar::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 3px;
        }
        .thin-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(129, 140, 248, 0.5);
            border-radius: 3px;
        }
        .thin-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(129, 140, 248, 0.7);
        }
    </style>

</div>
