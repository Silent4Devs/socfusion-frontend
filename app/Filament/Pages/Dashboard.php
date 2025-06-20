<?php

namespace App\Filament\Pages;
use Illuminate\Support\Facades\Http;
use Filament\Pages\Page;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\Client;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?int $navigationSort = -2;
    protected ?string $heading = '';

    public $today_reports;
    public $total_logrhythm;
    public $total_prtg;
    public $logrhythm_timeline;
    public $last_alerts;
    public $logrhythm_percentaje;
    public $high_percentage;
    public $high_count;
    public $prtg_percentage;

    public $reports_percentage;

    public $last_reports;
    public $clients;
    public $total_clients;
    public $ia_server;

    public $alerts_per_page = 3;
    public $current_alert_page = 1;
    public $all_alerts = [];
    public $visible_alerts = [];

    public function mount(): void
    {
        $this->ia_server = config('services.ia_server');

        $this->today_reports = Report::whereDate('created_at', Carbon::today())->count();
        
        $this->last_reports = Report::orderBy('created_at', 'desc')->take(3)->get();
        $this->fetchData();
     

    }

    public function fetchData(){
        $response = Http::get($this->ia_server . '/dashboard/soc');

        $data = $response->json();

        $this->total_logrhythm = $data['total_logrhythm'] ?? 0;
        $this->total_prtg = $data['total_prtg'] ?? 0;
        $this->logrhythm_timeline = $data['logrhythm_timeline'] ?? [];

        $this->prtg_percentage = isset($data['prtg_percentage']) ? round($data['prtg_percentage'], 1) : 0;
        $this->logrhythm_percentaje = isset($data['logrhythm_percentaje']) ? round($data['logrhythm_percentaje'], 1) : 0;
        $this->high_percentage = isset($data['high_percentage']) ? round($data['high_percentage'], 1) : 0;
        $this->high_count = $data['high_count'] ?? 0;

        $now = Carbon::now();

        $this->today_reports = Report::whereBetween('created_at', [
            $now->copy()->startOfDay(),
            $now,
        ])->count();

        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayNow = $now->copy()->subDay();

        $this->reports_yesterday_count = Report::whereBetween('created_at', [
            $yesterdayStart,
            $yesterdayNow,
        ])->count();

        if ($this->reports_yesterday_count > 0) {
            $this->reports_percentage = round(
                (($this->today_reports - $this->reports_yesterday_count) / $this->reports_yesterday_count) * 100,
                1
            );
        } else {
            $this->reports_percentage = $this->today_reports > 0 ? 100.0 : 0.0;
        }
        
        $this->clients = Client::withCount(['reports' => function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }])
        ->orderByDesc('reports_count')
        ->take(5)
        ->get();
        
        $this->total_clients = Client::count();
        $response = Http::get($this->ia_server . '/alarms/');
        $data = $response->json() ?? [];
        $this->all_alerts = array_slice($data, 0,21);
        $this->updateVisibleAlerts();
        $this->dispatch('update-chart');
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
