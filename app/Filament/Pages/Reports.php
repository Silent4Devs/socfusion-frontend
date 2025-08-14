<?php

namespace App\Filament\Pages;

use Livewire\Attributes\On; 
use Filament\Pages\Page;
use App\Models\Report;
use Livewire\WithPagination;
use App\Models\Client;

class Reports extends Page
{

    use WithPagination;
    
    protected static ?string $title = 'Reportes';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected ?string $heading = '';

    protected static string $view = 'filament.pages.reports';

    public $iaServer;
    public $query;
    public $clients = [];

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function confirmDeletion($id)
    {
        $this->dispatch('swal-confirm', id: $id); 
    }
    
    #[On('delete-report')]
    public function deleteReport($id)
    {
        Report::find($id)->delete();
        $this->getReportsProperty();        
        $this->dispatch('swal-deleted');
    }

    public function mount()
    {
        $this->clients = Client::orderBy('name')->get();
        $this->iaServer = config('services.ia_server');
        $this->getReportsProperty();
    }

    public function getReportsProperty()
    {
        $query = Report::orderBy('created_at', 'desc');

        if ($this->query) {
            $q = $this->query;
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        return $query->paginate(9)->onEachSide(1);; 
    }

    public function sendEmail(){
        
    }
}
