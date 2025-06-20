<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On; 
use App\Models\Report;
use App\Jobs\CreateReport;
use Illuminate\Support\Facades\Redis;
use Livewire\WithFileUploads;
use App\Jobs\CreateTicket;

class Alerts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected ?string $heading = '';

    protected static string $view = 'filament.pages.alerts';

    use WithFileUploads;

    public $alarmId;
    public $comments;
    public $evidence; 
    public $alarms = [];
    public $search = "";
    public $filteredAlarms = [];
    public $iaServer;
    public $assignees;
    
    public function mount()
    {
        $this->iaServer = config('services.ia_server');
        $response = Http::get($this->iaServer  . '/tickets/');
        $data = $response->json();


        if ($response->successful()) {
            
            $distinct = $data['distinct_values'] ?? [];

            $this->assignees = $distinct['assignees']     ?? [];
        }
    }

    #[On('update_Alarms')] 
    public function update_Alarms($data)
    {   
        $newAlarms = collect($data)->values()->toArray();
        $this->alarms = array_merge($newAlarms, $this->alarms);
        $this->filteredAlarms = $this->filterAlarms();
    }

    public function updatedSearch($value)
    {
        $this->filteredAlarms = $this->filterAlarms();
    }

    public function filterAlarms()
    {
        if (empty($this->search)) {
            return $this->alarms;
        }

        $searchTerm = strtolower($this->search);

        return array_filter($this->alarms, function ($alarm) use ($searchTerm) {
            return (
                (isset($alarm['alarm_rule_name']) && 
                str_contains(strtolower($alarm['alarm_rule_name']), $searchTerm)) ||
                
                (isset($alarm['message']) && 
                str_contains(strtolower($alarm['message']), $searchTerm)) ||
                
                (isset($alarm['impacted_entity']) && 
                str_contains(strtolower($alarm['impacted_entity']), $searchTerm))
            );
        });
    }

    public function generateReport($alarmId, $alarmType, $title, $comments, $create_ticket, $comments_ticket, $assign_ticket)
    {

        if ($create_ticket)
        {
            $this->dispatch('new-issue', message: 'Hubo un problema al conectarse con Remedy');
        }
        
        $evidencePath = null;
        if ($this->evidence) {
            $evidencePath = $this->evidence->store('reports_images', 'public');
        }

        $report = Report::create([
            'title' => $title,
            'description' => null,
            'filepath' => null,
            'alarm_id' => $alarmId,
            'alarm_type' => $alarmType,
            'client' => null,
            'status' => 'In process',
            'comments' => $comments,
            'evidence' => $evidencePath ? 'storage/' . $evidencePath : null,
        ]);

        CreateReport::dispatch(
            reportId: $report->id,
        );

        $this->dispatch('new-report', message: 'El reporte se está generando');

        return response()->json(['message' => 'Your file is being processed.'], 200);
    }

}


