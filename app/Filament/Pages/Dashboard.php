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

    public $last_reports;
    public $clients;
    public $total_clients;
    public $ia_server;

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
        $this->prtg_percentage = $data['prtg_percentage'] ?? 0;
        $this->logrhythm_percentaje = $data['logrhythm_percentaje'] ?? 0;
        $this->high_percentage = $data['high_percentage'] ?? 0;
        $this->high_count = $data['high_count'] ?? 0;
        
        $this->clients = Client::withCount(['reports' => function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }])
        ->orderByDesc('reports_count')
        ->take(5)
        ->get();
        $this->total_clients = Client::count();
        $response = Http::get($this->ia_server . '/alarms/logrhythm');
        $data = $response->json() ?? [];
        $this->last_alerts = array_slice($data, 0,3);
    }
}
