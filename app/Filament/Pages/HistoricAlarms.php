<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Lazy;
 
class HistoricAlarms extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $title = 'Histórico de Alarmas';
    protected ?string $heading = '';
    
    protected static string $view = 'filament.pages.historic-alarms';

    use WithPagination;

    public $iaServer = '';
    public $alarms = [];
    public $after = null;
    public $loading = false;
    public $hasMore = true;
    public $selectedAlarm;   
    public ?string $prevCursor = null;
    public int $perPage = 15;
    public int $currentPage = 1;
    public $totalPages = 1;
    public $total;
    public $pages = [];
    public $severityFilter;
    public $search = '';
    public $selectedSystem;
    public $alarmsByKey = [];

    public function loadAlarms(): void
    {
        if ($this->loading) {
            return;
        }

        $this->loading = true;

        try {
            $params = ['limit' => 40];
            
            if (!empty($this->prevCursor)) {
                $params['before'] = $this->prevCursor;
            }

            $response = Http::get("{$this->iaServer}/alarms", $params);

            $data = $response->json();

            if (is_array($data) && count($data) > 0) {
                $this->alarms = array_merge($this->alarms, $data);
               

                foreach ($data as $alarm) {
                    $key = $this->makeAlarmKey($alarm['id'], $alarm['alarm_type']);
                    $this->alarmsByKey[$key] = $alarm;
                }

                $lastAlarm = end($data);
                $timestamp = $lastAlarm['timestamp'] ?? 
                            $lastAlarm['created_at'] ?? 
                            $lastAlarm['date_inserted'] ?? null;

                if ($timestamp) {
                    $this->prevCursor = $timestamp;
                    $this->hasMore = true;
                } else {
                    $this->hasMore = false;
                }

            } else {
                $this->hasMore = false;
            }

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al cargar alarmas: ' . $e->getMessage()
            ]);
            $this->hasMore = false;
        } finally {
            $this->loading = false;
            $this->totalPages = (int) ceil(count($this->alarms) / $this->perPage);

        }
    }

    public function mount()
    {
        $this->iaServer = config('services.ia_server');
        $this->fetchTotal();

        $this->loadAlarms();
        $this->totalPages = (int) ceil(count($this->alarms) / $this->perPage);
        $this->goToPage(1);
    }

    public function fetchTotal()
    {
        try {
            $response = Http::get("{$this->iaServer}/excel/alarms/count");

            if ($response->successful()) {
                $data = $response->json();

                $logrhythmCount = $data['logrhythm'] ?? 0;
                $prtgCount = $data['prtg'] ?? 0;
                $this->total = $logrhythmCount + $prtgCount;
            } else {
            
                $this->total = 0;
            }

        } catch (\Exception $e) {         
            $this->total = 0;
        }

    }
    public function loadMore()
    {
        if ($this->hasMore) {
            $this->loadAlarms();
        }
    }

    public function getPaginatedAlarmsProperty()
    {
        $start = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->alarms, $start, $this->perPage);
    }


    public function lastPage(): int
    {
        return (int) ceil(count($this->alarms) / $this->perPage);
    }

    public function goToPage(int $page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->loadAlarms();
            $this->currentPage = $page;
            $this->pages = range(
                max(1, $this->currentPage - 2),
                min($this->totalPages, $this->currentPage + 2)
            );
        }

    }

    public function nextPage()
    {
       
        $this->goToPage($this->currentPage + 1);
    }

    public function previousPage()
    {
        $this->goToPage($this->currentPage - 1);
    }

    public function setSelectedAlarm($id, $type)
    {
        $key = $this->makeAlarmKey($id, $type);
        $this->selectedAlarm = $this->alarmsByKey[$key] ?? null;
    }


   private function makeAlarmKey($id, $type)
    {
        return "{$type}_{$id}";
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->loadAlarms();
    }
}
