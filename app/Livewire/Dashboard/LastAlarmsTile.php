<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LastAlarmsTile extends Component
{

    public $all_alerts = [];
    public $filteredAlarms = [];
    public $visible_alerts = [];
    public $alerts_per_page = 3;
    public $current_alert_page = 1;

    public function mount()
    {
        $this->iaServer = config('services.ia_server');
        $this->fetchData();
        $this->updateVisibleAlerts();

    }
    
    public function fetchData(){
        $response = Http::get($this->iaServer . '/alarms');
        if ($response->successful()) {
            $alarms = $response->json();

            $this->all_alerts = $alarms;
            $this->filteredAlarms = $alarms;
        }else{
            dd($response);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.last-alarms-tile');
    }
    
    public function updateVisibleAlerts()
    {
        $offset = ($this->current_alert_page - 1) * $this->alerts_per_page;
        $this->visible_alerts = array_slice($this->all_alerts, $offset, $this->alerts_per_page);
    }

    public function previousAlerts()
    {
        if ($this->current_alert_page > 1) {
            $this->current_alert_page--;
            $this->updateVisibleAlerts();
        }
    }

    public function nextAlerts()
    {
        $max_page = ceil(count($this->all_alerts) / $this->alerts_per_page);
        if ($this->current_alert_page < $max_page) {
            $this->current_alert_page++;
            $this->updateVisibleAlerts();
        }
    }

}
