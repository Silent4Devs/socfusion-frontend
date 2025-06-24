<?php

namespace App\Jobs;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\View;
use App\Models\Client;

class CreateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportId;
    public $report;

    /**
     * Create a new job instance.
     */
    public function __construct($reportId)
    {
        $this->reportId = $reportId;
   
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        Log::info("Job iniciado con id: {$this->reportId}");
        $report = Report::find($this->reportId);
        if (!$report) {
            Log::error("Reporte con ID {$this->reportId} no encontrado.");
            return;
        }

        $alarmId = $report->alarm_id;
        $alarmType = strtolower($report->alarm_type); 


        try {
            if ($alarmType === 'logrhythm') {
                $response = Http::get(env('IA_SERVER') . "/alarms/logrhythm/{$alarmId}");
            } elseif ($alarmType === 'prtg') {
                $response = Http::get(env('IA_SERVER') . "/alarms/prtg/{$alarmId}");
            } else {
                Log::warning("Tipo de alarma '$alarmType' no reconocido.");
                $report->status = 'Error';
                $report->save();
                return;
            }

            if ($response->successful()) {
                $alarmData = $response->json();
                $client = $this->findClient($alarmData); // Se asocia con el cliente
                if ($client) {
                    $report->client()->associate($client);
                    $report->save();
                }
                Log::info("Datos obtenidos de {$alarmType}: " . json_encode($alarmData));
                $name = $alarmData['name'] ?? 'Sin nombre';
                $severity = $alarmData['severity'] ?? 'desconocida';
                $source = $alarmData['source'] ?? 'fuente desconocida';
                $description = $alarmData['description'] ?? '';
                
                $alarmJson = json_encode($alarmData, JSON_PRETTY_PRINT);
                $prompt = "Sugiere una acciones en un máximo de 30 palabras para la siguiente alerta::\n\n$alarmJson  \n\n SOLO DEVUELVE EL TEXTO EN FORMATO PLANO";

                $payload = [
                    'prompt' => $prompt,
                    'model' => 'llama3.1:8b',
                ];

                $response = Http::post(env('IA_SERVER') . '/model/prompt', $payload);
                $suggestion = "";


                if ($response->successful()) {
                    $suggestion = $response->json()['response'] ?? '';
                    Log::info("Sugerencia de acción: $suggestion");
                } else {
                    Log::error("Error al generar la respuesta IA: " . $response->body());
                }

                $path = public_path('images/logo.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                $html = View::make('templates.report', [
                    'alarm' => $alarmData,
                    'suggestion' => $suggestion,
                    'alarmType' => $alarmType,
                    'logo' => $base64,
                    'client' => $client,
                    'comments' => $report->comments,
                    'evidence' => $report->evidence,
                ])->render();

                $pdf = new Pdf([
                    'page-size' => 'A4',
                    'orientation' => 'Portrait',
                    'encoding' => 'UTF-8',
                    'zoom' => 1.2,
                    'enable-local-file-access', 
                ]);
       
                $pdf->addPage($html);

                $filename = 'report_' . time() . '.pdf';
                $path = 'reports/' . $filename;

                $content = $pdf->toString();

                if (!$content) {
                    Log::error('Error al generar PDF: ' . $pdf->getError());
                    $report->status = 'Error';
                    $report->save();
                    return;
                }

                Storage::put($path, $content);
                $report->filepath = $path;
                $report->status = 'Completed';
                $report->save();

            } else {
                Log::error("Error al obtener datos de {$alarmType} con ID {$alarmId}. Código: {$response->status()}");
                $report->status = 'Error';
                $report->save();
            }
            

        } catch (\Exception $e) {
            Log::error("Excepción al obtener la alarma {$alarmId}: " . $e->getMessage());
            $report->status = 'Error';
            $report->save();
        }
    }


    public function findClient(array $alarmData): ?Client
    {
        // Combina todos los valores del array de alarma
        $searchText = collect($alarmData)->implode(' ');

        // Normaliza el texto completo de la alarma
        $normalizedSearch = $this->normalizeText($searchText);

        // Busca el primer cliente cuyo nombre normalizado esté en el texto normalizado
        return Client::get()->first(function ($client) use ($normalizedSearch) {
            $normalizedName = $this->normalizeText($client->name);
            return str_contains($normalizedSearch, $normalizedName);
        });
    }

    /**
     * Normaliza un string: minúsculas y sin espacios ni saltos de línea.
     */
    private function normalizeText(string $text): string
    {
        return strtolower(preg_replace('/\s+/', '', $text));
    }
}
