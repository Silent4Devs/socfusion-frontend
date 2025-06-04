<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Tickets extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static string $view = 'filament.pages.tickets';

    public $tickets;
    public $items;
    public $perPage = 10;
    public $page = 1;
    public $total;
    public $totalPages = 1;
    public $search = '';
    public $status = '';

    public function mount(): void
    {
        $baseUrl = config('services.ia_server');
        $response = Http::get($baseUrl . '/tickets/');

        if ($response->successful()) {
            $this->total = $response->json('total') ?? 300;
            $this->items = $response->json('items') ?? [];
            $this->totalPages = ceil(count($this->items) / $this->perPage);
            $this->updateTickets();
        } else {
            $this->items = [];
            $this->tickets = [];
            $this->totalPages = 1;
        }
    }

    public function updateTickets()
    {
        $filtered = $this->items;
        if (trim($this->search) !== '') {
            $filtered = array_filter($filtered, function ($item) {
                $haystack = strtolower(
                    ($item['description'] ?? '') . ' ' .
                    ($item['assignee'] ?? '') . ' ' .
                    ($item['entry_id'] ?? '')
                );
                return str_contains($haystack, strtolower($this->search));
            });
        }

        if ($this->status !== '') {
            $filtered = array_filter($filtered, function ($item) {
                return isset($item['status']) && $item['status'] === $this->status;
            });
        }

        $this->total = count($filtered);
        $this->totalPages = max(1, ceil(count($filtered) / $this->perPage));
        if ($this->page > $this->totalPages) {
            $this->page = $this->totalPages;
        }
        $offset = ($this->page - 1) * $this->perPage;
        $this->tickets = array_slice($filtered, $offset, $this->perPage);
    }


    public function updatedSearch()
    {
        $this->page = 1;
        $this->updateTickets();
    }

    public function updatedStatus()
    {
        $this->page = 1;
        $this->updateTickets();
    }


    public function goToPage($page)
    {
        if ($page < 1 || $page > $this->totalPages) {
            return;
        }
        $this->page = $page;
        $this->updateTickets();
    }
}
