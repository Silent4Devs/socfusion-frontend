<?php

namespace App\Filament\Pages;

use Livewire\Attributes\On; 
use Filament\Pages\Page;
use App\Models\Client;
use Livewire\WithFileUploads;

class Clients extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static string $view = 'filament.pages.clients';
    protected ?string $heading = '';

    public $clients;
    public $total;
    public $name, $email, $phone, $address, $logo;
    public $perPage = 10; 
    public $currentPage = 1;
    public $pages = [];
    public $totalPages;
    public $start, $end;
    public $allClients;
    public $search = '';
    public $query;

    public function updatedSearch()
    {
        $query = Client::orderBy('name');
        
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $this->total = $query->count();

        $this->currentPage = 1;

        $this->setPagesArray();

        $this->clients = $query
            ->skip(($this->currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }
    
    public function getTotalPages()
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function setPagesArray()
    {
        $this->totalPages = $this->getTotalPages();
        $start = max(1, $this->currentPage - 2);
        $end = min($this->totalPages, $this->currentPage + 2);

        $this->pages = range($start, $end);
    }

    public function mount(): void
    {
        $this->allClients  = Client::orderBy('name')->get();
        $this->total = $this->allClients->count();
        $this->setPagesArray();
        $this->updateClientsForCurrentPage();
    }

    public function updateClientsForCurrentPage()
    {
        $filteredClients = $this->allClients;

        if ($this->search) {
            $filteredClients = $filteredClients->filter(function ($client) {
                return stripos($client->name, $this->search) !== false;
            });
        }

        $this->total = $filteredClients->count();

        $start = ($this->currentPage - 1) * $this->perPage;
        $this->clients = $filteredClients->slice($start, $this->perPage);
    }

    public function gotoPage($page)
    {
        $this->currentPage = $page;
        $this->setPagesArray();
        $this->updateClientsForCurrentPage();
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
            $this->setPagesArray();
            $this->updateClientsForCurrentPage();
        }
    }
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->setPagesArray();
            $this->updateClientsForCurrentPage();
        }
    }


    public $clientId = null;

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->address = $client->address;
        
        $this->dispatch('open-client-modal');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:clients',
            'email' => 'required|email|unique:clients,email,' . $this->clientId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $client = Client::findOrFail($this->clientId);

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        $client->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'logo' => $logoPath,
        ]);
        $this->reset(['name', 'email', 'phone', 'address', 'logo']);
        $this->allClients  = Client::orderBy('name')->get();
        $this->dispatch('success-action');
        $this->dispatch('client-edited');
        $this->mount();
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
        $this->dispatch('success-action');
        $this->dispatch('client-created');
        
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

    public function getCurrentClients()
    {
        return Client::orderBy('name')
            ->skip(($this->currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

}
