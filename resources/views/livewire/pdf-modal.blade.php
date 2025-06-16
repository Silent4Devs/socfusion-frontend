
<div>
@if($show)
    <div class="fixed inset-0 z-50 bg-gray-900/90 flex items-center justify-center backdrop-blur-sm py-5 ">
        <div class="bg-gray-800 rounded-xl border border-gray-700 relative w-full max-w-5xl flex h-full flex-col overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-700 bg-gray-900/50 h-full">
                <div class="flex-1 overflow-hidden flex items-center justify-center h-full bg-gray-900/20 backdrop-blur-sm">
                    @if($url)
                    <iframe src="{{ $url }}#toolbar=0&navpanes=0" class="w-full h-full" frameborder="0"></iframe>
                    @else
                    <div class="text-center text-gray-400">Formato no soportado</div>
                    @endif
                </div>
                <button wire:click="close" class="rounded-lg hover:bg-gray-700/50 p-2 transition-all duration-200 group">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
@endif
</div>