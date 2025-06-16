<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;

class ChatHistorial extends Component
{

    public $chats;

    public function render()
    {
        return view('livewire.chat-historial');
    }

    public function mount(): void
    {
        $this->chats = Chat::where('user_id', auth()->id())
            ->with('messages')
            ->orderBy('created_at', 'desc') 
            ->get();
    }
    
}
