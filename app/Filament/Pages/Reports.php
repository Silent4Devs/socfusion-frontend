<?php

namespace App\Filament\Pages;

use Livewire\Attributes\On; 
use Filament\Pages\Page;
use App\Models\Report;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';

    public $iaServer;
    public $reports;

    public function confirmDeletion($id)
    {
        $this->dispatch('swal-confirm', id: $id); 
    }
    
    #[On('delete-report')]
    public function deleteReport($id)
    {
        Report::find($id)->delete();
        $this->reports = Report::orderBy('created_at', 'desc')->take(10)->get();
        $this->dispatch('swal-deleted');

    }

    public function mount()
    {
        $this->iaServer = config('services.ia_server');
        $this->reports = Report::orderBy('created_at', 'desc')->get();    
    }
}
