<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Documents extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string $view = 'filament.pages.documents';
    protected ?string $heading = '';

    public $documents;
    public $clients;
    
    public $search = '';
    public $selectedClient = '';
    public $iaServer;
    public $filteredDocuments = [];

    public function mount(): void
    {
        $this->iaServer = config('services.ia_server');
         
        $response = Http::get($this->iaServer . '/documents/all-files');
        

        if ($response->successful()) {
            $this->documents = $response->json();
            $this->documents = collect($this->documents)
            ->sortBy('filename')
            ->values() 
            ->all();
            $this->clients = collect($this->documents)
            ->pluck('client')
            ->filter() 
            ->map(fn($c) => mb_strtolower(trim($c), 'UTF-8'))
            ->unique()
            ->values()
            ->all();
            
        } else {
            $this->documents = [];
        }

        $this->filteredDocuments = $this->documents;
    }


    public function updatedSearch()
    {
        $this->filter();
    }
    
    public function updatedSelectedClient()
    {
        $this->filter();
    }
    
    public function filter()
    {
        $this->filteredDocuments = collect($this->documents)
            ->filter(function ($doc) {
                $matchesSearch = empty($this->search) || str_contains(
                    mb_strtolower($doc['filename'] ?? '', 'UTF-8'),
                    mb_strtolower($this->search, 'UTF-8')
                );
    
                $matchesClient = empty($this->selectedClient) || mb_strtolower($doc['client'] ?? '', 'UTF-8') === mb_strtolower($this->selectedClient, 'UTF-8');
    
                return $matchesSearch && $matchesClient;
            })
            ->values()
            ->all();
    }

}
