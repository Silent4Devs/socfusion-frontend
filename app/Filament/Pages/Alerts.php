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
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Alerts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected ?string $heading = '';

    protected static string $view = 'filament.pages.alerts';

    use WithFileUploads;
    use WithPagination;

    public $alarmId;
    public $comments;
    public $evidence; 
    public $alarms = [];
    public $search = "";
    public $filteredAlarms = [];
    public $iaServer;
    public $assignees = [];

    public $page = 1;
    public $perPage = 20; 
    
    public $classification = '';
    public $client = '';
    public $alarmType = '';

    public $clients;
    public $visibleAlerts;

    public function mount()
    {
        $this->iaServer = config('services.ia_server');

        $response = Http::get($this->iaServer  . '/tickets/');
        $data = $response->json();

        if ($response->successful()) {
            
            $distinct = $data['distinct_values'] ?? [];

            $this->assignees = $distinct['assignees']     ?? [];
        }

        $response = Http::get($this->iaServer . '/alarms/clients');
        if ($response->successful()) {
            $clients = $response->json();

            $this->clients = $clients;
            
        } 
        
        $this->getVisibleAlerts();

    }

    public function nextPage()
    {
        $maxPage = ceil(count($this->filteredAlarms) / $this->perPage);
        if ($this->page < $maxPage) {
            $this->page++;
        }

        $this->getVisibleAlerts();
    }

    public function updateNewAlarms($newAlarms){
        if ($newAlarms){
            $this->alarms = array_merge($newAlarms, $this->alarms);
            $this->filterAlarms();
            $this->getVisibleAlerts();
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }

        $this->getVisibleAlerts();
    }

    public function resetPage()
    {
        $this->page = 1;
        $this->getVisibleAlerts();
    }

    public function getVisibleAlerts()
    {
        $offset = ($this->page - 1) * $this->perPage;
        
        $this->visibleAlerts = array_slice($this->filteredAlarms, $offset, $this->perPage);
    }

    public function update_Alarms()
    {   
        $start = microtime(true);

        $response = Http::get($this->iaServer . '/alarms');
        if ($response->successful()) {
            $newAlarms = $response->json();

            if (empty($this->alarms)) {
                $this->alarms = $newAlarms;
            } else {
                $latestDate = $this->alarms[0]['created_at'] ?? $this->alarms[0]['date_inserted'] ?? null;

                $appendIndex = null;
                foreach ($newAlarms as $i => $alarm) {
                    $date = $alarm['created_at'] ?? $alarm['date_inserted'] ?? null;
                    if ($date === $latestDate) {
                        $appendIndex = $i;
                        break;
                    }
                }

                if ($appendIndex === null) {
                    $this->alarms = array_merge($newAlarms, $this->alarms);
                } elseif ($appendIndex > 0) {
                    $onlyNew = array_slice($newAlarms, 0, $appendIndex);
                    $this->alarms = array_merge($onlyNew, $this->alarms);
                }
            }

            $this->filterAlarms();

            if (isset($onlyNew) && count($onlyNew) > 0) {
                $count = count($onlyNew);
                $this->dispatch('new-report', message: "$count nueva" . ($count > 1 ? 's alertas' : ' alerta'));
            }
        }
        $this->getVisibleAlerts();
      
        \Log::info("Function runs in " . ($end - $start) . "seconds");
    }

    public function updatedClassification($value)
    {
        $this->filterAlarms();
    }

    public function updatedSearch($value)
    {
        $this->filterAlarms();
    }

    public function updatedAlarmType($value)
    {
        $this->filterAlarms();
    }


    public function filterAlarms()
    {
        
        $start = microtime(true);

        $alarms = $this->alarms;

        if (!empty($this->alarmType)) {
            $alarms = array_filter($alarms, function ($alarm) {
                return isset($alarm['alarm_type']) && $alarm['alarm_type'] == $this->alarmType;
            });
        }
        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);

            $alarms = array_filter($alarms, function ($alarm) use ($searchTerm) {
                return (
                    (isset($alarm['alarm_rule_name']) && str_contains(strtolower($alarm['alarm_rule_name']), $searchTerm)) ||
                    (isset($alarm['message']) && str_contains(strtolower($alarm['message']), $searchTerm)) ||
                    (isset($alarm['impacted_entity']) && str_contains(strtolower($alarm['impacted_entity']), $searchTerm))
                );
            });
        }

        if (!empty($this->classification)){
            $alarms = array_filter($alarms, function ($alarm) {
                return isset($alarm['model_classification']) && $alarm['model_classification'] == $this->classification;
            });
        }
        
        $this->filteredAlarms = $alarms;
        $this->resetPage();
        $end = microtime(true);


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


