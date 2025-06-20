<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Tickets extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static string $view = 'filament.pages.tickets';
    protected ?string $heading = '';

    public $tickets;
    public $items;
    public $perPage = 10;
    public $page = 1;
    public $total;
    public $totalPages = 1;
    public $search = '';
    public $status = '';
    
    public $description;
    public $ticketType;
    public $company;
    public $assignedTo;
    public $comments;
    public $showModal = true;
    public $assignees, $ownerGroups, $organizations, $priorities, $serviceTypes;
    public $selectedTicket, $error, $loading;

    protected $rules = [
        'description' => 'required|min:10',
        'ticketType' => 'required',
        'company' => 'required',
        'assignedTo' => 'required',
        'comments' => 'nullable',
    ];

    public function mount(): void
    {
        $baseUrl = config('services.ia_server');
        $response = Http::get($baseUrl . '/tickets/');
        $data = $response->json();


        if ($response->successful()) {
            $this->total = $data['total'] ?? 300;
            $this->items = $data['tickets'] ?? [];
            $this->totalPages = ceil(count($this->items) / $this->perPage);

            $distinct = $data['distinct_values'] ?? [];

            $this->assignees     = $distinct['assignees']     ?? [];
            $this->ownerGroups   = $distinct['owner_groups']  ?? [];
            $this->organizations = $distinct['organizations'] ?? [];
            $this->priorities    = $distinct['priorities']    ?? [];
            $this->serviceTypes  = $distinct['service_types'] ?? [];

            $this->updateTickets();
        } else {
            $this->total = 0;
            $this->items = [];
            $this->tickets = [];
            $this->totalPages = 1;

            $this->assignees = [];
            $this->ownerGroups = [];
            $this->organizations = [];
            $this->priorities = [];
            $this->serviceTypes = [];
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

    public function submitTicket()
    {
        $this->dispatch('ticket-error', [
            'message' => 'Error al conectarse a Remedy.'
        ]);
    }

    public function goToPage($page)
    {
        if ($page < 1 || $page > $this->totalPages) {
            return;
        }
        $this->page = $page;
        $this->updateTickets();
    }

    public function reasignarTicket($person)    
    {
        $this->dispatch('ticket-error', [
            'message' => 'Error al conectarse a Remedy.'
        ]);
    }

    public function changeStatus($status){
        $this->dispatch('ticket-error', [
            'message' => 'Error al conectarse a Remedy.'
        ]);
    }

    public function getTicketDetails($id)
    {
        try {
            $this->loading = true;
            $this->error = null;
            
    
            $ticket = collect($this->items)->firstWhere('id', $id);

            if (!$ticket) {
                throw new \Exception("Ticket no encontrado");
            }

            $this->selectedTicket = $this->formatTicketDetails($ticket);

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->selectedTicket = null;
        } finally {
            $this->loading = false;
        }
    }

    /**
     * Formatea los detalles del ticket para visualización
     * 
     * @param array $ticket Datos del ticket
     * @return array Datos formateados
     */
    protected function formatTicketDetails($ticket)
    {
        return [
            'Información Básica' => [
                'ID' => $ticket['id'],
                'Número de Incidente' => $ticket['incident_number'],
                'Solicitante' => $ticket['submitter'],
                'Fecha de Creación' => $this->formatDate($ticket['submit_date']),
                'Estado' => $ticket['status'],
                'Prioridad' => $ticket['priority'],
                'Tipo de Servicio' => $ticket['service_type']
            ],
            'Asignación' => [
                'Asignado a' => $ticket['assignee'],
                'Grupo' => $ticket['owner_group'],
                'Organización' => $ticket['organization'],
                'Empresa' => $ticket['company'],
                'Sitio' => $ticket['site']
            ],
            'Categorización' => [
                'Categoría Nivel 1' => $ticket['categorization_tier_1'],
                'Categoría Nivel 2' => $ticket['categorization_tier_2'],
                'Categoría Nivel 3' => $ticket['categorization_tier_3'],
                'Producto Nivel 1' => $ticket['product_categorization_tier_1'],
                'Producto Nivel 2' => $ticket['product_categorization_tier_2'],
                'Producto Nivel 3' => $ticket['product_categorization_tier_3']
            ],
            'Detalles' => [
                'Descripción' => $ticket['description'],
                'Detalles Adicionales' => $ticket['detailed_description'] ?? 'N/A',
                'Fecha del Evento' => $this->formatDate($ticket['time_of_event']),
                'Historial de Estados' => $this->formatStatusHistory($ticket['status_history'] ?? [])
            ],
            'Contacto' => [
                'Email' => $ticket['internet_email'],
                'Compañía de Soporte' => $ticket['assigned_support_company']
            ]
        ];
    }

    protected function formatDate($dateString)
    {
        try {
            return \Carbon\Carbon::parse($dateString)->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            return $dateString;
        }
    }


    protected function formatStatusHistory($history)
    {
        if (empty($history)) return 'No disponible';
        
        $formatted = [];
        foreach ($history as $status => $data) {
            $formatted[] = sprintf(
                "%s: %s (%s)",
                $status,
                $data['user'],
                $this->formatDate($data['timestamp'])
            );
        }
        
        return implode("\n", $formatted);
    }
}
