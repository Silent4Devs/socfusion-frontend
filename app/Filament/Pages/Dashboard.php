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

    public $today_reports;
    public $total_logrhythm;
    public $total_prtg;
    public $logrhythm_timeline;
    public $last_alerts;

    public $last_reports;
    public $clients;
    public $total_clients;

    public function mount(): void
    {
        $this->ia_server = config('services.ia_server');

        $this->today_reports = Report::whereDate('created_at', Carbon::today())->count();
        
        $this->last_reports = Report::orderBy('created_at', 'desc')->take(3)->get();
        
        $response = Http::get($this->ia_server . '/dashboard/soc');

        $data = $response->json();

        $this->total_logrhythm = $data['total_logrhythm'] ?? 0;
        $this->total_prtg = $data['total_prtg'] ?? 0;
        $this->logrhythm_timeline = $data['logrhythm_timeline'] ?? [];
        
        $this->clients = Client::take(5)->get();
        $this->total_clients = Client::count();
        $response = Http::get($this->ia_server . '/alarms/logrhythm');
        $data = $response->json() ?? [];
        $this->last_alerts = array_slice($data, 0,3);
    }
}
