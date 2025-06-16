<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ChatSoc extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected ?string $heading = '';

    protected static string $view = 'filament.pages.chat-soc';

    public $iaServer;

    public function mount()
    {
        $this->iaServer = config('services.ia_server');
    }

}
