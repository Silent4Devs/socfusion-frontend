<?php

namespace App\Filament\Pages;

use Livewire\Attributes\On; 
use Filament\Pages\Page;
use App\Models\Client;

class Clients extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static string $view = 'filament.pages.clients';

    public $clients;
    public $total;
    public $name, $email, $phone, $address, $logo;
    public $search;
    public $perPage;
    public $currentPage = 1;
    public $pages = [1,2,3]; 

    public function gotoPage($page)
    {
        $this->currentPage = $page;
    }

    public function nextPage()
    {
        if ($this->currentPage < count($this->pages)) {
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function mount(): void
    {
        $this->clients = Client::orderBy('name')->get();
        $this->total = $this->clients->count();
    }


    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'logo' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        Client::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'logo' => $logoPath,
        ]);

        $this->reset();
        $this->clients = Client::orderBy('name')->get();
        $this->total = $this->clients->count();

        
    }

    #[On('delete-client')]
    public function deleteClient($id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
        }
        $this->clients = Client::orderBy('name')->get();
        $this->dispatch('swal-deleted');
        $this->total = $this->clients->count();

    
    }

}
