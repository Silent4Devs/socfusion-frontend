<?php

namespace App\Filament\Pages;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Filament\Pages\Page;
use App\Models\Report;
use App\Models\Client;
use App\Mail\ReportEmail;

class Reports extends Page
{

    use WithPagination, WithFileUploads;

    protected static ?string $title = 'Reportes';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected ?string $heading = '';
    protected static string $view = 'filament.pages.reports';

    protected $rules = [
        'selectedClientId'      => 'required',
        'description'           => 'required',
        'recomendations'        => 'required',
        'ticket_de_seguimiento' => 'required',
        'actions_taken'         => 'required',
        'csv_file'              => 'nullable|file|mimes:csv,txt|max:25600',
        'evidence.*'            => 'image|max:5120'
    ];
    protected $messages = [
        'selectedClientId.required'         => 'Favor de seleccionar al cliente al que se le mandará el reporte',
        'description.required'              => 'Favor de describir la incidencia',
        'recomendations.required'           => 'Favor de sugerir recomendaciones',
        'ticket_de_seguimiento.required'    => 'Ticket de seguimiento obligatorio',
        'actions_taken.required'            => 'Favor de escribir las acciones tomadas',
    ];

    public $csv_file;
    public $iaServer;
    public $query;
    public $clients = [];
    public $selectedClientId;
    public $emailSubject = '';
    public $description;
    public $recomendations;
    public $log_time;
    public $tool_info = [];
    public $stored_tool_info = [];
    public $ticket_de_seguimiento;
    public $orden_de_trabajo;
    public $actions_taken;
    public $evidence = [];
    public $stored_evidence = [];
    public $notes;


    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function confirmDeletion($id)
    {
        $this->dispatch('swal-confirm', id: $id);
    }

    #[On('delete-report')]
    public function deleteReport($id)
    {
        Report::find($id)->delete();
        $this->getReportsProperty();
        $this->dispatch('swal-deleted');
    }

    public function mount()
    {
        $this->clients = Client::orderBy('name')->get();
        $this->iaServer = config('services.ia_server');
        $this->getReportsProperty();
    }

    public function getReportsProperty()
    {
        $query = Report::orderBy('created_at', 'desc');

        if ($this->query) {
            $q = $this->query;
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        return $query->paginate(9)->onEachSide(1);;
    }

    public function sendEmail($report){
        $this->validate();

        $alarm = Http::get($this->iaServer  . "/alarms/{$report['alarm_type']}/{$report['alarm_id']}")->json();
        $this->emailSubject = $this->emailSubject=='' ? "Notificación de Actividad Sospechosa || ".$report['title'] : $this->emailSubject;
        $emails = Client::find($this->selectedClientId)->emails ?? [];

        $csvData = [];
        $ips = [];
        $urls = [];

        if ($this->csv_file) {
            $path = $this->csv_file->store('uploads', 'public');
            $fullPath = public_path('storage/' . $path);
            $rows = array_map('str_getcsv', file($fullPath));

            if (!empty($rows)) {
                $header = array_map('trim', $rows[0]);

                foreach ($header as $col) {
                    $csvData[$col] = [];
                }

                for ($i = 1; $i < count($rows); $i++) {
                    foreach ($header as $index => $col) {
                        $csvData[$col][] = $rows[$i][$index] ?? null;
                    }
                }

                foreach ($csvData as $col => $values) {
                    $csvData[$col] = array_values(array_unique($values, SORT_STRING));
                }
            }
            unlink($fullPath);
        }

        $log_time_path = null;
        if ($this->log_time) {
            $log_time_path = $this->log_time->store('log-time', 'public');
        }

        foreach ($this->tool_info as $file) {
            $path = $file->store('evidence', 'public');
            $this->stored_tool_info[] = $path;
        }

        foreach ($this->evidence as $file) {
            $path = $file->store('evidence', 'public');
            $this->stored_evidence[] = $path;
        }

        foreach ($csvData as $key) {
            foreach ($key as $value) {
                if (filter_var($value, FILTER_VALIDATE_IP)) {
                    $ips[$value] = Http::timeout(86400)->post($this->iaServer  . '/scan/ip', ['ip' => $value])->json();
                } else if ($this->validateUrlRegex($value)) {
                    $urls[$value] = Http::timeout(86400)->post($this->iaServer  . '/scan/url', ['url' => $value])->json();
                }
            }
        }

        $details = [
            'description' => $this->description,
            'recomendations' => $this->recomendations,
            'log_time' => $log_time_path,
            'tool_info' => $this->stored_tool_info,
            'ticket_de_seguimiento' => $this->ticket_de_seguimiento,
            'orden_de_trabajo' => $this->orden_de_trabajo,
            'actions_taken' => $this->actions_taken,
            'stored_evidence' => $this->stored_evidence,
            'notes' => $this->notes,
        ];

        foreach ($emails as $email) {
            Mail::to($email)
                ->cc(['soc.s4b@silent4business.com','calidad@silent4business.com'])
                ->queue(new ReportEmail($this->emailSubject, $alarm, $csvData, $ips, $urls, $report, $details));
        }

        // Previsualización del diseño del correo

        // $data = [
        //     'emailSubject' => $this->emailSubject,
        //     'alarm' => $alarm,
        //     'csvData' => $csvData,
        //     'ips' => $ips,
        //     'urls' => $urls,
        //     'report' => $report,
        //     'details' => $details,
        // ];

        // // Store the data temporarily in the session
        // session(['email_preview_data' => $data]);

        // // Redirect to the preview route
        // return redirect()->to('/preview-email');
    }

    public function validateUrlRegex($url) {
        // Regex for URLs without protocol (e.g., example.com, www.example.com/path)
        $regex = "/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}(:[0-9]{1,5})?(\/.*)?$/";
        $proto_regex = "/^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/";
        return preg_match($regex, $url) || preg_match($proto_regex, $url);
    }
}
